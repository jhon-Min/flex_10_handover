<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use App\Models\ProductImportStatus;
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
        $timestamp = filectime($filepath);
        $currentTimestamp = time();

        Log::info("Stock Started : start working now");

        $file = fopen($filepath, "r");
        $start_time = date('Y-m-d H:i:s');

        // Read the CSV file line by line
        while (($row = fgetcsv($file)) !== false) {
            // Push the row data into the array
            $product_nr = $row[0];
            $qty = $row[1];
            $company_sku = $row[2];
            $id = $this->getProductWithSku($company_sku);

            if (!empty($id)) {
                $update = Product::where('id', $id)->update([
                    'product_nr' => $product_nr,
                    'qty' => $qty,
                    'product_nr' => $product_nr,
                ]);
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
