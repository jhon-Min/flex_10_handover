<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\SageAPIRepository;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Product;
use Carbon\Carbon;
use App\Models\ProductPrice;

class SyncProductPrices extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:product:prices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will import and update the product prices per user with account code assigned with the use of sage web services';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    public $sageapirepository;

    public function __construct(SageAPIRepository $sageapirepository)
    {
        $this->sageapirepository = $sageapirepository;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        ini_set('memory_limit', '-1');
        $current_time = Carbon::now();


        //Get pricelist information
        echo "Start : Import Customer pricelist information \n";
        Log::info('Start : Import Customer pricelist information');
        $this->customerPriceList();
        echo "End : Import Customer pricelist information \n\n";
        Log::info('End : Import Customer pricelist information');
        $end_time = Carbon::now();
        Log::info('completed : ' . $end_time->diffForHumans($current_time));
    }


    protected function customerPriceList()
    {
        $users = User::whereHas('roles', function ($query) {
            $query->whereNotIn('name', ['Super Admin', 'Admin']);
        })->where('account_code', '!=', '')->get()->pluck('account_code', 'id')->toArray();

        $type = "YPRILST";
        if (count($users) > 0) {

            $db_products = Product::whereNotNull('company_sku')->pluck('company_sku', 'id')->toArray();
            $db_product_prices = ProductPrice::selectRaw(DB::raw('CONCAT(product_id, "_",user_id, "_", company_sku) as product_price'))->pluck('product_price')->toArray();
            $this->createPriceTempTable();

            foreach ($users as $user_id => $account_code) {

                $price_data = $this->sageapirepository->getCustomerInformation($account_code, $type);
                if ($price_data) {

                    $product_prices = [];
                    foreach ($price_data as $price) {

                        $product_price = $price['FLD'];
                        $product_id = $this->array_find($product_price[0], $db_products);
                        if ($product_id) {
                            $product_prices[] = [
                                'product_id' => $product_id,
                                'user_id' => $user_id,
                                'price_retail' => $product_price[1],
                                'price_nett' => $product_price[3],
                                'company_sku' => $product_price[0],
                            ];
                        }

                        if (count($product_prices) >= 1000) {
                            $this->process($product_prices);
                            $product_prices = [];
                        }
                    }

                    if (count($product_prices) > 0) {
                        $this->process($product_prices);
                        $product_prices = [];
                    }
                }
            }

            $this->dropPriceTempTable();
        }
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

        $sql = "insert into product_price_tmp({$columns}) values {$values}";
        DB::statement($sql);
        $this->insertUpdateMainTable();
        echo "\nProcessed " . count($records) . "\n";
        return true;
    }

    private function insertUpdateMainTable()
    {
        $sql = "INSERT INTO product_prices ( `product_id`, `user_id`, `company_sku`, `price_nett`, `price_retail` ) SELECT temp.product_id, temp.user_id, temp.company_sku, temp.price_nett, temp.price_retail FROM product_price_tmp temp ON DUPLICATE KEY UPDATE price_nett = temp.price_nett, price_retail = temp.price_retail";
        DB::statement($sql);

        $sql = "TRUNCATE table product_price_tmp";
        DB::statement($sql);
    }

    private function createPriceTempTable()
    {
        $sql1 = "DROP TABLE IF EXISTS `product_price_tmp`;";
        $sql2 = "CREATE TABLE `product_price_tmp` (
                  `product_id` bigint(20) UNSIGNED NOT NULL,
                  `user_id` bigint(20) UNSIGNED NOT NULL,
                  `price_retail` double(9,6) NOT NULL DEFAULT 0.00,
                  `price_nett` double(9,6) NOT NULL DEFAULT 0.00,
                  `company_sku` text COLLATE utf8mb4_unicode_ci DEFAULT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

        DB::statement($sql1);
        DB::statement($sql2);

        return true;
    }

    private function dropPriceTempTable()
    {
        $sql = "DROP TABLE IF EXISTS `product_price_tmp`";
        DB::statement($sql);

        return true;
    }

    private function array_find($needle, array $haystack)
    {
        foreach ($haystack as $key => $value) {
            if (false !== stripos($value, $needle)) {
                return $key;
            }
        }
        return false;
    }
}
