<?php

namespace App\Console\Commands;

use App\Repositories\SageAPIRepository;
use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\Branch;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class SyncProductQuantity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:product:qty';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync products quantity from sage webservice';

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
        $current_time = Carbon::now();

        echo "Start : Import Product QTY\n";
        Log::info("Started : Import Product QTY");

        $db_products = $this->getProductWithSkus();
        $db_branches = $this->getAllBranches();

        $this->createTempTable();

        $product_quantities = [];
        foreach ($db_products as $product_id => $company_sku) {

            $total_quantity = 0;
            $qty_data = $this->sageapirepository->getProductQuantity($company_sku);
            if ($qty_data) {

                foreach ($qty_data as $branch) {

                    $branch_data = $branch['FLD'];
                    if (isset($db_branches[$branch_data[0]])) {
                        $branch_id = $db_branches[$branch_data[0]];
                    } else {
                        $branch_object = Branch::create([
                            'code' => $branch_data[0],
                            'name' => $branch_data[1]
                        ]);
                        $db_branches[$branch_data[0]] = $branch_id = $branch_object->id;
                    }

                    $product_quantities[] = [
                        'product_id' => $product_id,
                        'branch_id' => $branch_id,
                        'company_sku' => $company_sku,
                        'qty' => $branch_data[2]
                    ];

                    $total_quantity += $branch_data[2];
                }
            }
            Product::where('id', $product_id)->update(['qty' => $total_quantity]);

            if (count($product_quantities) >= 1000) {
                $this->process($product_quantities);
                $product_quantities = [];
            }
        }

        if (count($product_quantities) > 0) {
            $this->process($product_quantities);
            $product_quantities = null;
        }

        $this->dropTempTable();

        $end_time = Carbon::now();
        Log::info("Completed : Import Product QTY" . $end_time->diffForHumans($current_time));
        echo "End : Import Product QTY\n";
    }

    private function getProductWithSkus()
    {
        $products = Product::whereNotNull('company_sku')->where('company_sku', '!=', '')->pluck('company_sku', 'id')->toArray();

        return $products;
    }

    private function getAllBranches()
    {
        $branches = Branch::all()->pluck('id', 'code')->toArray();

        return $branches;
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

    private function dropTempTable()
    {
        $sql = "DROP TABLE IF EXISTS `product_quantities_temp`";
        DB::statement($sql);

        return true;
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
        echo "\nProcessed " . count($records) . "\n\n";
        return true;
    }

    private function insertUpdateMainTable()
    {
        $sql = "INSERT INTO product_quantities  ( `product_id`, `branch_id`, `company_sku`, `qty` ) SELECT temp.product_id, temp.branch_id, temp.company_sku, temp.qty FROM product_quantities_temp temp ON DUPLICATE KEY UPDATE qty = temp.qty";
        DB::statement($sql);

        $sql = "TRUNCATE table product_quantities_temp";
        DB::statement($sql);
    }
}
