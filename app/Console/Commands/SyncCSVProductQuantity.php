<?php

namespace App\Console\Commands;

use App\Models\Branch;
use App\Models\Product;
use App\Models\ProductImportStatus;
use App\Repositories\SageAPIRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SyncCSVProductQuantity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:csvproduct:qty';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync CSV products quantity.';

    /**
     * Execute the console command.
     */
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public $sageapirepository;

    public function __construct(SageAPIRepository $sageapirepository)
    {
        parent::__construct();
        $this->sageapirepository = $sageapirepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        ini_set('memory_limit', '-1');
        echo "CSV Start : Import Product QTY\n";
        Log::info("CSV Started : Import Product QTY");

        $filepath = "/home/ec2-user/bulkupload/prod.csv";

        $handle = fopen("/home/ec2-user/fd-backend/public/uploads/products-stock/dummy.txt", "a");
        fwrite($handle, "Background process started at " . date("Y-m-d H:i:s") . PHP_EOL);
        $csv_data = fopen($filepath, "r");
        $importData_arr = array();
        $start_time = date('Y-m-d H:i:s');

        $productImportStatus = new ProductImportStatus();
        $productImportStatus->file_path = $filepath;
        $productImportStatus->status = 'In Progress';
        $productImportStatus->start_time = $start_time;
        $productImportStatus->import_type = 'stock';
        $productImportStatus->save();

        $i = 0;

        while (($filedata = fgetcsv($csv_data, 1000, ",")) !== FALSE) {
            $importDataTemp = array();
            $num = count($filedata);
            if ($i == 0) {
                $i++;
                continue;
            }

            for ($c = 0; $c < $num; $c++) {
                if ($c == 0) {

                    $filterData = explode('"', $filedata[$c]);
                    if (sizeof($filterData) > 1) {
                        $filedata[$c] = ltrim($filterData[1], '=');
                    } else {
                        $filedata[$c] = $filterData[0];
                    }
                    $productNbr = $filedata[$c];
                }
                $importDataTemp[] = $filedata[$c];
            }
            $importData_arr[$productNbr][] = $importDataTemp;


            $i++;
        }
        fclose($csv_data);
        ProductImportStatus::where('file_path', '=', $filepath)
            ->where('import_type', '=', 'stock')
            ->where('start_time', '=', $start_time)
            ->update(['total_records' => $i - 1]);
        $db_branches = $this->getAllBranches();
        $product_quantities = [];
        $failed_enteries = [];
        $failed_enteries_msg = "";
        $this->createTempTable();
        foreach ($importData_arr as $company_sku => $importData) {

            // Log::info("CSV Started : company_sku" . $company_sku);

            $total_quantity = 0;
            $product_id = $this->getProductWithSku($company_sku);
            if (!empty($product_id)) {
                foreach ($importData as $row) {
                    $branch_code = $row[2];
                    if (isset($db_branches[$branch_code])) {
                        $branch_id = $db_branches[$branch_code];
                        if ($row[1] >= 0) {
                            $product_quantities[] = [
                                'product_id' => $product_id,
                                'branch_id' => $branch_id,
                                'company_sku' => $company_sku,
                                'qty' => $row[1]
                            ];
                            $total_quantity += $row[1];
                        } else {
                            $failed_enteries[] = $row;
                            $failed_enteries_msg .= '"' . $row[0] . '",' . $row[1] . ',"' . $row[2] . '" => Quantity cannot be less than zero' . PHP_EOL;
                        }
                    } else {
                        $failed_enteries[] = $row;
                        $failed_enteries_msg .= '"' . $row[0] . '",' . $row[1] . ',"' . $row[2] . '" =>  Invalid branch code' . PHP_EOL;
                    }
                }
            } else {
                foreach ($importData as $fRow) {
                    $failed_enteries[] = $fRow;
                    $failed_enteries_msg .= '"' . $fRow[0] . '",' . $fRow[1] . ',"' . $fRow[2] . '" => No matching product SKU found' . PHP_EOL;
                }
            }

            Product::where('id', $product_id)->update(['qty' => $total_quantity]);
            $this->process($product_quantities);
            $product_quantities = [];
            fwrite($handle, "Background process successfully ran for Product No: " . $company_sku . "Product Id: " . $product_id . " == " . date("Y-m-d H:i:s") . PHP_EOL);
        }
        $failedEnteryCount = sizeof($failed_enteries);
        $end_time = date('Y-m-d H:i:s');
        if ($failedEnteryCount > 0) {
            $path = "/home/ec2-user/fd-backend/public/uploads/products-error-stock";
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            $errorFilePath = '/home/ec2-user/fd-backend/public/uploads/products-error-stock/' . date('dmy') . "_Log" . ".txt";
            file_put_contents($errorFilePath, $failed_enteries_msg);
            ProductImportStatus::where('file_path', '=', $filepath)
                ->where('import_type', '=', 'stock')
                ->where('start_time', '=', $start_time)
                ->update([
                    'end_time' => $end_time,
                    'error_records' => $failedEnteryCount,
                    'error_file_path' => $errorFilePath,
                    'status' => "Error",
                ]);
        } else {
            ProductImportStatus::where('file_path', '=', $filepath)
                ->where('start_time', '=', $start_time)
                ->where('import_type', '=', 'stock')
                ->update([
                    'end_time' => $end_time,
                    'error_records' => $failedEnteryCount,
                    'status' => "Success",
                ]);
        }
        fwrite($handle, "Background process ended at " . date("Y-m-d H:i:s"));
        fclose($handle);

        Log::info("CSV Completed : Import Product QTY");
        echo "CSV End : Import Product QTY\n";
    }

    private function getProductWithSku($company_sku)
    {
        $products = Product::where('company_sku', $company_sku)->value('id');

        return $products;
    }

    private function getAllBranches()
    {
        $branches = Branch::all()->pluck('id', 'code')->toArray();

        return $branches;
    }

    private function getProductQuantity($sku)
    {
        $branches = Branch::all()->pluck('id', 'code')->toArray();

        return $branches;
    }

    private function process(array $records)
    {

        if (count($records) == 0) {
            return true;
        }

        $first = reset($records);
        $columns = implode(
            ',',
            array_map(function ($value) {
                return "`$value`";
            }, array_keys($first))
        );

        $values = implode(
            ',',
            array_map(function ($record) {
                return '(' . implode(
                    ',',
                    array_map(function ($value) {
                        return '"' . str_replace('"', '""', $value) . '"';
                    }, $record)
                ) . ')';
            }, $records)
        );

        $sql = "insert into product_quantities_temp({$columns}) values {$values}";
        DB::statement($sql);
        $this->insertUpdateMainTable();
        return true;
    }

    private function insertUpdateMainTable()
    {
        $sql = "INSERT INTO product_quantities  ( `product_id`, `branch_id`, `company_sku`, `qty` ) SELECT temp.product_id, temp.branch_id, temp.company_sku, temp.qty FROM product_quantities_temp temp ON DUPLICATE KEY UPDATE qty = temp.qty";
        DB::statement($sql);

        $sql = "TRUNCATE table product_quantities_temp";
        DB::statement($sql);
        return true;
    }

    private function createTempTable()
    {
        $sql1 = "DROP TABLE IF EXISTS `product_quantities_temp`;";
        $sql2 = "CREATE TABLE IF NOT EXISTS `product_quantities_temp` (
                  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                  `product_id` bigint(20) NOT NULL,
                  `branch_id` int(11) NOT NULL,
                  `company_sku` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                  `qty` int(11) NOT NULL,
                  PRIMARY KEY (`id`),
                  KEY `product_quantities_product_id_foreign` (`product_id`),
                  KEY `product_quantities_branch_id_foreign` (`branch_id`)
                ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

        DB::statement($sql1);
        DB::statement($sql2);

        return true;
    }

    private function generateRandomString($length = 5)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
