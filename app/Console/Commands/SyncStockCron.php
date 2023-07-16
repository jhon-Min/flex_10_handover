<?php

namespace App\Console\Commands;

use App\Models\Branch;
use App\Models\Product;
use Illuminate\Console\Command;
use App\Models\ProductImportStatus;
use App\Models\ProductQuantity;
use Illuminate\Support\Facades\Log;

class SyncStockCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stock:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run stock service';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        ini_set('memory_limit', '-1');
        echo "Stock Start \n";

        $filepath = env('STOCK_PATH');

        try {
            if (file_exists($filepath)) {
                Log::info("Stock Started : start working now");
                echo "Start qty import";
                $file = fopen($filepath, "r");
                $start_time = date('Y-m-d H:i:s');

                // Read the CSV file line by line
                $i = 1;
                while (($row = fgetcsv($file)) !== false) {
                    // Push the row data into the array
                    $product_nr = $row[0];
                    $qty = $row[1];
                    $branch_code = $row[2];
                    $product = Product::where("product_nr", $product_nr)->first();
                    $branch = Branch::where("code", $branch_code)->first();
                    if ($product) {
                        $product_id = $product->id;
                        $update = $product->update([
                            'product_nr' => $product_nr,
                            'qty' => $qty,
                        ]);
                        if ($product && $branch) {
                            // ProductQuantity::updateOrCreate([
                            //     "product_id" => $product_id,
                            //     "branch_id" => $branch->id,
                            // ], ["qty" => $qty, "company_sku" => $product->company_sku]);

                            ProductQuantity::updateOrCreate(["product_id" => $product_id, "branch_id" => $branch->id], ["qty" => $qty, "company_sku" => $product->company_sku]);
                            echo ("branch id $branch->id product id $product_id sku $product->company_sku qty $qty");
                        }
                        echo ("$i found proudct id $product_id , nr $product_nr and qty is $qty \n");
                        $i++;
                    } else {
                        echo "$i skip not found \n";
                        $i++;
                    }
                }
                // Close the file
                fclose($file);


                $productImportStatus = new ProductImportStatus();
                $productImportStatus->file_path = $filepath;
                $productImportStatus->status = 'Complete';
                $productImportStatus->start_time = $start_time;
                $productImportStatus->import_type = 'stock';
                $productImportStatus->save();
            }
        } catch (\Throwable $th) {
            return $th;
        } finally {
            // unlink($filepath);
        }

        // if ($timestamp == $currentTimestamp) {

        // } else {
        //     Log::info("Current time $currentTimestamp");
        //     Log::info("$timestamp Stock Started : The Csv file is not updated");
        // }
    }

    private function getProductWithSku($company_sku)
    {
        $products = Product::where('company_sku', $company_sku)->value('id');
        return $products;
    }
}
