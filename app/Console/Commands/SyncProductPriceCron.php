<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\CustomerUser;
use App\Models\ProductPrice;
use Illuminate\Console\Command;
use App\Models\ProductImportStatus;
use Illuminate\Support\Facades\Log;

class SyncProductPriceCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'productprice:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Product Price & Customer Cron is working.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        ini_set('memory_limit', '-1');
        echo "Product and Customer working start \n";

        // $customer_path = "/home/kimmich/Documents/csv/cust.csv";
        // $price_path = "/home/kimmich/Documents/csv/pric.csv";
        $customer_path = env('CUSTOMER_CSV');
        $price_path = env('PRICE_CSV');

        $timestamp = filectime($customer_path);
        $currentTimestamp = time();

        Log::info("ProductPrice Started : start working now");
        $customer_file = fopen($customer_path, "r");
        $price_file = fopen($price_path, "r");
        $start_time = date('Y-m-d H:i:s');

        $userQuery =  CustomerUser::with(['user']);
        $users = $userQuery->get();

        Log::info("Customer: start working now");

        $user_list = [];
        foreach ($users as $user) {
            if (!empty($user->account_code)) {
                $user_list[$user->id] = $user->account_code;
            }
        }

        $user_list_code = [];
        foreach ($user_list as $user_id => $account_code) {
            $user_list_code[$account_code][] = $user_id;
        }

        $productImportStatus = new ProductImportStatus();
        $productImportStatus->file_path = $customer_path;
        $productImportStatus->file_path1 = $price_path;
        $productImportStatus->status = 'Progess';
        $productImportStatus->start_time = $start_time;
        $productImportStatus->import_type = 'price';
        $productImportStatus->save();

        $array_customer_category_mapping = [];
        $failed_enteries = [];
        $failed_enteries_msg = '';

        while (($row = fgetcsv($customer_file)) !== false) {
            if (in_array($row[0], $user_list)) {
                $array_customer_category_mapping[$row[0]] = $row[2];
            } else {
                $failed_enteries[] = $row;
                $failed_enteries_msg .= 'Customer "' . $row[0] . '" with Company Name "' . $row[1] . '" => Entry not found in System' . PHP_EOL;
            }
        }
        // Close the customer file
        fclose($customer_file);

        $i = $j = 0;
        $array_category_price_mapping = [];
        while (($row = fgetcsv($price_file, 1000, ",")) !== FALSE) {
            $num1 = count($row);
            if ($j == 0) {
                $j++;
                continue;
            }

            for ($k = 0; $k < $num1; $k++) {
                if ($k == 0) {
                    if (isset($row[4])) {
                        $array_category_price_mapping[$row[4]][$row[0]]['net_price'] = $row[2];
                        $array_category_price_mapping[$row[4]][$row[0]]['retail_price'] = $row[3];
                    }
                }
            }

            $j++;
        }
        // Close the price file
        fclose($price_file);

        $intCntTotal = 1;
        $int = 0;

        foreach ($user_list_code as $acnt_code => $code_user) {
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



        $failedEnteryCount = sizeof($failed_enteries);
        $end_time = date('Y-m-d H:i:s');
        if ($failedEnteryCount > 0) {
            $path = env('PRODUCT_ERROR_PATH_1');
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            $errorFilePath = env('PRODUCT_ERROR_PATH_2') . date('dmy') . "_Log" . ".txt";
            file_put_contents($errorFilePath, $failed_enteries_msg);
            ProductImportStatus::where('file_path', '=', $customer_path)
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
            ProductImportStatus::where('file_path', '=', $customer_path)
                ->where('start_time', '=', $start_time)
                ->where('import_type', '=', 'price')
                ->update([
                    'end_time' => $end_time,
                    'error_records' => $failedEnteryCount,
                    'total_records' => $intCntTotal,
                    'status' => "Success",
                ]);
        }
        // if ($timestamp == $currentTimestamp) {

        // } else {
        //     Log::info("Current time $currentTimestamp");
        //     Log::info("$timestamp Product Started : The Csv file is not updated");
        // }
    }
}
