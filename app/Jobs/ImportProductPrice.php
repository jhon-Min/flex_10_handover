<?php

namespace App\Jobs;

use App\Models\Branch;
use App\Models\Product;
use App\Models\ProductImportStatus;
use App\Models\ProductPrice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ImportProductPrice implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $filepath;
    protected $filename;
    protected $filepath1;
    protected $filename1;
    protected $user_list;
    protected $user_list_code;
    /**
     * Create a new job instance.
     */
    public function __construct($filepath, $filename, $filepath1, $filename1, $user_list)
    {
        $this->filepath = $filepath;
        $this->filename = $filename;
        $this->filepath1 = $filepath1;
        $this->filename1 = $filename1;
        $this->user_list = $user_list;

        foreach ($user_list as $user_id => $account_code) {
            $this->user_list_code[$account_code][] = $user_id;
        }
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        ini_set('memory_limit', '-1');
        $handle = fopen("uploads/products-price/product_customer_price.txt", "a");
        // $handle = fopen("uploads/products-price/product_price.txt", "a");
        fwrite($handle, "Background process started at " . date("Y-m-d H:i:s") . PHP_EOL);
        $csv_data = fopen($this->filepath, "r");
        $csv_data1 = fopen($this->filepath1, "r");
        $importData_arr = array();
        $start_time = date('Y-m-d H:i:s');

        $productImportStatus = new ProductImportStatus;
        $productImportStatus->file_path = $this->filepath;
        $productImportStatus->file_path1 = $this->filepath1;
        $productImportStatus->status = 'In Progress';
        $productImportStatus->start_time = $start_time;
        $productImportStatus->import_type = 'price';
        $productImportStatus->save();


        $i = $j = 0;

        $failed_enteries = [];
        $failed_enteries_msg = '';

        $array_customer_category_mapping = [];
        while (($filedata = fgetcsv($csv_data, 1000, ",")) !== FALSE) {
            $num = count($filedata);
            if ($i == 0) {
                $i++;
                continue;
            }

            if (in_array($filedata[0], $this->user_list)) {
                $array_customer_category_mapping[$filedata[0]] = $filedata[2];
            } else {
                $failed_enteries[] = $filedata;
                $failed_enteries_msg .= 'Customer "' . $filedata[0] . '" with Company Name "' . $filedata[1] . '" => Entry not found in System' . PHP_EOL;
            }

            $i++;
        }
        fclose($csv_data);

        $array_category_price_mapping = [];
        while (($filedata1 = fgetcsv($csv_data1, 1000, ",")) !== FALSE) {
            $num1 = count($filedata1);
            if ($j == 0) {
                $j++;
                continue;
            }

            for ($k = 0; $k < $num1; $k++) {
                if ($k == 0) {
                    if (isset($filedata1[4])) {
                        $array_category_price_mapping[$filedata1[4]][$filedata1[0]]['net_price'] = $filedata1[2];
                        $array_category_price_mapping[$filedata1[4]][$filedata1[0]]['retail_price'] = $filedata1[3];
                    }
                }
            }
            $j++;
        }
        fclose($csv_data1);

        $intCntTotal = 1;

        $int = 0;

        foreach ($this->user_list_code as $acnt_code => $code_user) {
            if (array_key_exists($acnt_code, $array_customer_category_mapping)) {
                $category_id = $array_customer_category_mapping[$acnt_code];
                if (array_key_exists($category_id, $array_category_price_mapping)) {
                    $part_number_array = $array_category_price_mapping[$category_id];


                    foreach ($part_number_array as $part_number => $price_data) {
                        //get product from part number

                        $product_data = Product::where('product_nr', $part_number)->where('company_sku', $part_number)->first();

                        if (!empty($product_data)) {
                            $product_id = $product_data->id;

                            foreach ($code_user as $key => $user_id) {
                                $price_data_select = ProductPrice::where('product_id', $product_id)->where('user_id', $user_id)->first();
                                if (!empty($price_data_select)) {
                                    ProductPrice::where('id', '=', $price_data_select->id)
                                        ->update([
                                            'price_retail' => $price_data['retail_price'],
                                            'price_nett' => $price_data['net_price'],
                                            'updated_at' => date('Y-m-d H:i:s'),
                                        ]);
                                } else {
                                    if (!empty($price_data['retail_price']) && !empty($price_data['net_price'])) {
                                        ProductPrice::insert([
                                            'product_id' => $product_id,
                                            'user_id' => $user_id,
                                            'company_sku' => $user_id,
                                            'price_retail' => $price_data['retail_price'],
                                            'price_nett' => $price_data['net_price'],
                                            'created_at' => date('Y-m-d H:i:s'),
                                            'updated_at' => date('Y-m-d H:i:s'),

                                        ]);
                                    }
                                }
                                $intCntTotal++;
                            }
                        } else {
                            $failed_enteries[] = $part_number;
                            $failed_enteries_msg .= 'Part Number "' . $part_number . '" for Account Code "' . $acnt_code . '" => Part Number not found in System.' . PHP_EOL;
                        }
                    }
                } else {
                    $failed_enteries[] = $category_id;
                    $failed_enteries_msg .= 'Customer CSV Category "' . $category_id . '" => Category not found in Mapping Price CSV.' . PHP_EOL;
                }
            } else {
                $failed_enteries[] = $acnt_code;
                $failed_enteries_msg .= 'Account Code "' . $acnt_code . '" => not found in CSV.' . PHP_EOL;
            }
        }


        // foreach ($array_customer_category_mapping as $account_code => $category_id) {
        //     if (array_key_exists($category_id, $array_category_price_mapping)) {
        //         $part_number_array = $array_category_price_mapping[$category_id];

        //         foreach ($part_number_array as $part_number => $price_data) {
        //             //get product from part number

        //           $product_data = Product::where('product_nr', $part_number)->first();

        //           if (!empty($product_data)) {
        //             $product_id = $product_data->id;

        //             if (isset($this->user_list_code[$account_code])) {
        //               $account_user_list = $this->user_list_code[$account_code];
        //               foreach ($account_user_list as $key => $user_id) {
        //                 $price_data_select = ProductPrice::where('product_id', $product_id)->where('user_id', $user_id)->first();
        //                 if (!empty($price_data_select)) {
        //                   ProductPrice::where('id','=',$price_data_select->id)
        //                   ->update([
        //                     'price_retail'=> $price_data['retail_price'], 
        //                     'price_nett'=> $price_data['net_price'],
        //                     'updated_at'=> date('Y-m-d H:i:s'),
        //                   ]);
        //                 }
        //                 else {
        //                   if (!empty($price_data['retail_price']) && !empty($price_data['net_price'])) {
        //                     ProductPrice::insert([
        //                       'product_id'=> $product_id, 
        //                       'user_id'=> $user_id, 
        //                       'company_sku'=> $user_id, 
        //                       'price_retail'=> $price_data['retail_price'], 
        //                       'price_nett'=> $price_data['net_price']
        //                     ]);
        //                   }
        //                 }
        //                 $intCntTotal++;
        //               }
        //             }
        //             else {
        //               $failed_enteries[] = $account_code;
        //               $failed_enteries_msg .= 'Account Code "' . $account_code . '" => not found in System.'.PHP_EOL;
        //             }

        //           }
        //           else {
        //             $failed_enteries[] = $part_number;
        //             $failed_enteries_msg .= 'Part Number "' . $part_number. '" for Account Code "' . $account_code . '" => Part Number not found in System.'.PHP_EOL;
        //           }
        //         }
        //     }
        //     else {
        //         $failed_enteries[] = $category_id;
        //         $failed_enteries_msg .= 'Customer CSV Category "' . $category_id. '" => Category not found in Mapping Price CSV.'.PHP_EOL;
        //     }
        // }

        $failedEnteryCount = sizeof($failed_enteries);
        $end_time = date('Y-m-d H:i:s');
        if ($failedEnteryCount > 0) {
            $path = "uploads/products-error-price";
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            $errorFilePath = 'uploads/products-error-stock/' . $this->filename . "_Log" . ".txt";
            file_put_contents($errorFilePath, $failed_enteries_msg);
            ProductImportStatus::where('file_path', '=', $this->filepath)
                ->where('import_type', '=', 'price')
                ->where('start_time', '=', $start_time)
                ->update([
                    'end_time' => $end_time,
                    'error_records' => $failedEnteryCount,
                    'error_file_path' => $errorFilePath,
                    'total_records' => $intCntTotal,
                    'status' => "Error",
                ]);
        } else {
            ProductImportStatus::where('file_path', '=', $this->filepath)
                ->where('start_time', '=', $start_time)
                ->where('import_type', '=', 'price')
                ->update([
                    'end_time' => $end_time,
                    'error_records' => $failedEnteryCount,
                    'total_records' => $intCntTotal,
                    'status' => "Success",
                ]);
        }
        fwrite($handle, "Background process ended at " . date("Y-m-d H:i:s"));
        fclose($handle);
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
