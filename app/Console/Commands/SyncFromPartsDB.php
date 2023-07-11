<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\PartsDBAPIRepository;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use App\Models\Make;
use App\Models\Brand;
use App\Models\Models;
use App\Models\Vehicle;
use App\Models\Product;
use Carbon\Carbon;
use App\Models\VehicleTemp;
use App\Models\ProductImage;
use App\Models\ProductVehicle;
use App\Models\ProductCategory;
use App\Models\ImportScriptHistory;
use App\Models\CEDProductCriteria;
use Illuminate\Support\Facades\Log;

class SyncFromPartsDB extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:partsdb';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will update local databse for parts/prducts related data from partsdb with the use of API';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    public $partsdbapirepository, $PRODUCTS_PATH, $last_updated = null;

    public function __construct(PartsDBAPIRepository $partsdbapirepository)
    {
        $this->partsdbapirepository = $partsdbapirepository;
        parent::__construct();

        $this->PRODUCTS_PATH = Config::get('constant.PRODUCTS_PATH');
        $this->last_updated = date('Y-m-d');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */


    public function handle()
    {
        ini_set('memory_limit', '-1');

        $import_script = ImportScriptHistory::create(['start' => Carbon::now()]);

        //Authenticate user with system and obtain the session id and the response for next request.The session will be expired after 30 minutes.
        $this->partsdbapirepository->login();
        $import_script->login = 1;
        $import_script->save();

        // Get all brands that available to the customer from parts db and import in local database (Complete)
        echo "Start : Import Brands \n";
        // $this->importBrands();
        echo "End : Import Brands \n\n";
        $import_script->brand = 1;
        $import_script->save();

        //Get the list of all Makes and Models from PARts system and import in local database
        echo "Start : Import Makes and Models \n";
        // $this->importMakeAndModel();
        echo "End : Import Makes and Models \n\n";
        $import_script->make_model = 1;
        $import_script->save();

        //Import Products from the partsdb to local database with make, model, vehicle mapping (Complete)
        echo "Start : Import Products \n";
        Log::info("Start : Import Products");
        // $this->importProducts();
        Log::info("End : Import Products");
        echo "End : Import Products \n\n";

        //delete Products from local db which are removed from parts db and not coming in sync
        echo "Start : Delete Products \n";
        // $this->deleteProducts();
        echo "End : Delete Products \n\n";

        // Get CED Prodct Criteria (Complete)
        echo "Start : Import CED Product Criteria \n";
        // $this->importCEDProductCriteria();
        echo "End : Import CED Product Criteria \n\n";
        $import_script->product_criteria = 1;
        $import_script->save();

        echo "Start : Import CED Product Company Web Status \n";
        // $this->importPorductCompanyWebStatus();
        echo "End : Import CED Product Company Web Status \n\n";
        $import_script->products = 1;
        $import_script->save();


        //Import Vehicles from the partsdb to local database
        echo "Start : Import Vehicles \n";
        // $this->importVehicles();
        echo "End : Import Vehicles \n";
        $import_script->vehicle = 1;
        $import_script->save();

        //Import Product-Vehicle mapping
        echo "Start : Import Product-Vehicle mapping \n";
        $this->importProductVehicleMapping();
        echo "End : Import Product-Vehicle mapping \n";
        $import_script->product_vehicles = 1;
        $import_script->save();


        //Import Vehicle Engine Code
        echo "Start : Import Vehicle Engine Code \n";
        $this->importVehicleEngineCode();
        echo "End : Import Vehicle Engine Code \n";
        $import_script->vehicle_engine_code = 1;
        $import_script->save();


        //Import Product-image mapping
        echo "Start : Import Product-image mapping \n" . Carbon::now() . "\n";
        $this->importProductImageMapping();
        echo "End : Import Product-image mapping \n" . Carbon::now() . "\n";
        $import_script->product_images = 1;
        $import_script->save();

        $import_script->end = Carbon::now();
        $import_script->duration = $import_script->end->diffForHumans($import_script->start);
        $import_script->save();
        echo $import_script->end->diffForHumans($import_script->start);
    }

    // Done min
    private function importBrands()
    {

        $db_brands = Brand::all()->pluck('id')->toArray();
        $brands = $this->partsdbapirepository->getAllBrands();
        $brands_array = [];
        foreach ($brands as $brand) {
            if ($brand->BrandName != "TRW" && $brand->BrandName != "DOGA" && $brand->BrandName !=  "REMSA" && $brand->BrandName !=  "BOSCH") {
                $brand_data = Brand::firstOrCreate(['id' => $brand->ID, 'name' => $brand->BrandName]);
                $brand_data->logo = $brand->ImagesLocation . $brand->LogoFileName;
                $brand_data->save();
            }
        }
    }

    protected function importMakeAndModel()
    {

        $makes_and_models = $this->partsdbapirepository->getAllMakesAndModels();
        $db_makes = Make::all()->pluck('id')->toArray();
        $makes_array = [];
        foreach ($makes_and_models->ListMakes as $make) {
            if (!in_array($make->ID, $db_makes)) {
                $makes_array[] = [
                    'id' => $make->ID,
                    'name' => $make->Make,
                ];
            }
        }

        if (!empty($makes_array)) {
            Make::insert($makes_array);
        }

        $db_models = Models::all()->pluck('name')->toArray();
        $models_array = [];
        foreach ($makes_and_models->ListModels as $model) {

            if (!in_array($model->Model, $db_models)) {
                $models_array[] = [
                    'make_id' => $model->MakeID,
                    'name' => $model->Model,
                ];
            }

            if (count($models_array) >= 1000) {
                Models::insert($models_array);
                $models_array = [];
            }
        }

        if (count($models_array) > 0) {
            Models::insert($models_array);
            $models_array = [];
        }
    }

    public function importTestProducts()
    {

        // $this->createProductsTempTable();

        $db_brands = Brand::all()->pluck('id')->toArray();

        $products_array = [];
        $product_nr_sku_category = [];

        foreach ($db_brands as $brand_id) {

            $PageNum = 1;
            while ($products = $this->partsdbapirepository->getProductsSubscribed($brand_id, $PageNum, 10000)) {
                echo "Brand ID : " . $brand_id . " > PageNum : " . $PageNum . " > Products Fetched : " . count($products) . "\n";
                Log::info("Brand ID : " . $brand_id . " > PageNum : " . $PageNum . " > Products Fetched : " . count($products));


                $product_lists = [];

                foreach ($products as $product) {
                    Log::info("Loop a product");
                    //fetch product categories
                    $ced_product_categories =  $this->partsdbapirepository->getCEDProductCategories($product->ProductNr, $product->BrandID);

                    Log::info("Get CED Product Cate");

                    if (count($ced_product_categories) > 0) {

                        foreach ($ced_product_categories as $ced_product_category) {
                            Log::info("Start Loop CED Product Cate");
                            //fetch product linked parts
                            $corresponding_numbers = $this->partsdbapirepository->getProductCorrespondingPartNmuber($product->BrandID, $product->ProductNr, $ced_product_category->CompanySKU);

                            Log::info("Get Part Number");

                            $cross_reference_numbers = $associated_part_numbers = [];
                            if (count($corresponding_numbers) > 0) {
                                foreach ($corresponding_numbers as $corresponding_number) {
                                    if ($corresponding_number->LinkType == 'Associated Parts') {
                                        $associated_part_numbers[] = $corresponding_number->LinkProductNr;
                                    } else if ($corresponding_number->LinkType == 'Cross Reference') {
                                        $cross_reference_numbers[] = $corresponding_number->LinkProductNr;
                                    }
                                }
                            }

                            //Fetch product attributes
                            $product_critearea =  $this->getProductAttributes($product->BrandID, $product->ProductNr, $product->StandardDescriptionID);

                            $product_details = [
                                'brand_id' => $product->BrandID,
                                'product_nr' => $product->ProductNr,
                                'name' => $product->StandardDescription,
                                'description' => $product->StandardDescription,
                                'cross_reference_numbers' => implode(',', $cross_reference_numbers),
                                'associated_part_numbers' => implode(',', $associated_part_numbers),
                                'company_sku' => $ced_product_category->CompanySKU,
                                'standard_description_id' => $product->StandardDescriptionID,
                                'last_updated' => $this->last_updated
                            ];

                            $product_nr_sku_category[$product->ProductNr . "_" . $ced_product_category->CompanySKU] = $ced_product_category->CategoryID;
                            $products_array[] = array_merge($product_details, $product_critearea);
                        }
                    } else {
                        //if category mapping not found
                        //Fetch product attributes
                        $product_critearea =  $this->getProductAttributes($product->BrandID, $product->ProductNr, $product->StandardDescriptionID);

                        // Log::info("Get Product Cretia");

                        $product_details = [
                            'brand_id' => $product->BrandID,
                            'product_nr' => $product->ProductNr,
                            'name' => $product->StandardDescription,
                            'description' => $product->StandardDescription,
                            'cross_reference_numbers' => '',
                            'associated_part_numbers' => '',
                            'company_sku' => '',
                            'standard_description_id' => $product->StandardDescriptionID,
                            'last_updated' => $this->last_updated
                        ];

                        $products_array[] = array_merge($product_details, $product_critearea);
                    }

                    // if (count($products_array) >= 1000) {
                    //     $this->process($products_array);
                    //     $this->processProductCategoryMapping($product_nr_sku_category);
                    //     $products_array = [];
                    //     $product_nr_sku_category = [];
                    // }
                }
                $PageNum++;
            }
        }

        if (count($products_array) > 0) {
            $this->process($products_array);
            $this->processProductCategoryMapping($product_nr_sku_category);
            $products_array = [];
            $product_nr_sku_category = [];
            echo "Add products array to product table";
            Log::info("Add record to product table");
        }
    }

    public function importProducts()
    {
        $db_brands = Brand::all()->pluck('id')->toArray();

        $products_array = [];
        $product_nr_sku_category = [];

        foreach ($db_brands as $brand_id) {

            $PageNum = 1;
            $products = $this->partsdbapirepository->getAllProducts($brand_id);
            echo "Brand ID : " . $brand_id . " > PageNum : " . $PageNum . " > Products Fetched : " . count($products) . "\n";
            Log::info("Brand ID : " . $brand_id . " > PageNum : " . $PageNum . " > Products Fetched : " . count($products));

            foreach ($products as $product) {
                // Log::info("Loop a product");

                //fetch product categories
                $ced_product_categories =  $this->partsdbapirepository->getCEDProductCategories($product->ProductNr, $product->BrandID);

                // Log::info("Get CED Product Cate");

                if (count($ced_product_categories) > 0) {

                    foreach ($ced_product_categories as $ced_product_category) {
                        // Log::info("Start Loop CED Product Cate");
                        //fetch product linked parts
                        try {
                            $corresponding_numbers = $this->partsdbapirepository->getProductCorrespondingPartNmuber($product->BrandID, $product->ProductNr, $ced_product_category->CompanySKU);
                        } catch (\Throwable $th) {
                            echo "Product Corresponding Not Found: \n";
                            Log::info($th->getMessage());
                        }

                        // Log::info("Get Part Number");

                        $cross_reference_numbers = $associated_part_numbers = [];
                        if (count($corresponding_numbers) > 0) {
                            foreach ($corresponding_numbers as $corresponding_number) {
                                if ($corresponding_number->LinkType == 'Associated Parts') {
                                    $associated_part_numbers[] = $corresponding_number->LinkProductNr;
                                } else if ($corresponding_number->LinkType == 'Cross Reference') {
                                    $cross_reference_numbers[] = $corresponding_number->LinkProductNr;
                                }
                            }
                        }

                        //Fetch product attributes
                        $product_critearea =  $this->getProductAttributes($product->BrandID, $product->ProductNr, $product->StandardDescriptionID);

                        $product_details = [
                            'brand_id' => $product->BrandID,
                            'product_nr' => $product->ProductNr,
                            'name' => $product->StandardDescription,
                            'description' => $product->StandardDescription,
                            'cross_reference_numbers' => implode(',', $cross_reference_numbers),
                            'associated_part_numbers' => implode(',', $associated_part_numbers),
                            'company_sku' => $ced_product_category->CompanySKU,
                            'standard_description_id' => $product->StandardDescriptionID,
                            'last_updated' => $this->last_updated
                        ];

                        $product_nr_sku_category[$product->ProductNr . "_" . $ced_product_category->CompanySKU] = $ced_product_category->CategoryID;
                        $products_array[] = array_merge($product_details, $product_critearea);
                    }
                } else {
                    //if category mapping not found
                    //Fetch product attributes
                    try {
                        $product_critearea =  $this->getProductAttributes($product->BrandID, $product->ProductNr, $product->StandardDescriptionID);
                    } catch (\Throwable $th) {
                        echo "Product Attribute Not Found: \n";
                        Log::info($th->getMessage());
                    }
                    // Log::info("Get Product Cretia");

                    $product_details = [
                        'brand_id' => $product->BrandID,
                        'product_nr' => $product->ProductNr,
                        'name' => $product->StandardDescription,
                        'description' => $product->StandardDescription,
                        'cross_reference_numbers' => '',
                        'associated_part_numbers' => '',
                        'company_sku' => '',
                        'standard_description_id' => $product->StandardDescriptionID,
                        'last_updated' => $this->last_updated
                    ];

                    $products_array[] = array_merge($product_details, $product_critearea);
                }

                if (count($products_array) >= 1000) {
                    echo "Add products array to product table \n \n";
                    $this->process($products_array, 'product_tmp');
                    try {
                        $this->processProductCategoryMapping($product_nr_sku_category);
                    } catch (\Throwable $th) {
                        echo "Product Not Found: \n";
                        Log::info($th->getMessage());
                    }
                    $products_array = [];
                    $product_nr_sku_category = [];
                }
            }
        }

        if (count($products_array) > 0) {
            $this->process($products_array, 'product_tmp');
            try {
                $this->processProductCategoryMapping($product_nr_sku_category);
            } catch (\Throwable $th) {
                echo "Product Not Found: \n";
                Log::info($th->getMessage());
            }
            $products_array = [];
            $product_nr_sku_category = [];
            echo "Add products array to product table";
            Log::info("Add record to product table");
        }
    }

    // Done By Min
    protected function deleteProducts()
    {
        Product::where('last_updated', '<>', $this->last_updated)->delete();
    }

    // Done by Min
    protected function processProductCategoryMapping($product_nr_sku_category)
    {

        $db_products = Product::selectRaw(DB::raw('CONCAT(product_nr, "_", company_sku) as product_nr_sku, id as product_id'))->pluck('product_id', 'product_nr_sku')->toArray();

        $db_product_category = ProductCategory::selectRaw(DB::raw('CONCAT(product_id, "_", category_id) as product_category'))->pluck('product_category')->toArray();

        $db_product_category_new = [];

        foreach ($product_nr_sku_category as $product_nr_sku => $category_id) {

            $product_id = $db_products[$product_nr_sku];

            if (!in_array($product_id, $db_product_category)) {
                $db_product_category_new[] = [
                    'product_id' => $product_id,
                    'category_id' => $category_id
                ];
            }
        }

        if (count($db_product_category_new) > 0) {
            ProductCategory::insert($db_product_category_new);
        }
    }

    // Done by Min
    protected function getProductAttributes($brand_id, $product_nr, $standard_description_id)
    {

        $critearea = [
            'brake_system' => '',
            'length' => '',
            'fitting_position' => '',
            'height' => '',
            'thickness' => '',
            'weight' => '',
        ];

        $product_critearea =  $this->partsdbapirepository->getProductCriteria($brand_id, $product_nr, $standard_description_id);

        try {
            if (count($product_critearea) > 0) {

                foreach ($product_critearea as $criteara) {

                    $critearea['product_nr'] = $product_nr;
                    $critearea['brand_id'] = $brand_id;

                    if (isset($criteara->Criteria) && !empty($criteara->Criteria)) {
                        if ($criteara->Criteria == 'Brake System' || $criteara->Criteria == 'Brake Type') {
                            $critearea['brake_system'] = $criteara->Value;
                        } elseif ($criteara->Criteria == 'Length [mm]') {
                            $critearea['length'] = $criteara->Value;
                        } elseif ($criteara->Criteria == 'Fitting Position') {
                            $critearea['fitting_position'] = $criteara->Value;
                        } elseif ($criteara->Criteria == 'Height [mm]') {
                            $critearea['height'] = $criteara->Value;
                        } elseif ($criteara->Criteria == 'Weight [kg]') {
                            $critearea['weight'] = $criteara->Value;
                        } elseif ($criteara->Criteria == 'Thickness' || $criteara->Criteria == 'Thickness [mm]') {
                            $critearea['thickness'] = $criteara->Value;
                        } else {
                        }
                    }
                }
            }
        } catch (\Throwable $th) {
            echo "Product Attribute Error: " . $th->getMessage() . "\n";
        }

        return $critearea;
    }

    protected function process(array $records, $table)
    {

        if (count($records) == 0) {
            return true;
        }

        if ($table == 'product_tmp') {
            foreach ($records as $record) {
                Log::info($record);
                try {
                    if ($product = Product::where('product_nr', $record['product_nr'])->where('company_sku', $record['company_sku'])->first()) {
                        Log::info($product);
                        $product->update([
                            'brand_id' => $record['brand_id'],
                            // 'product_nr' => $record['product_nr'],
                            'name' => $record['name'],
                            'description' => $record['description'],
                            'cross_reference_numbers' => $record['cross_reference_numbers'],
                            'associated_part_numbers' => $record['associated_part_numbers'],
                            // 'company_sku' => $record['company_sku'],
                            'standard_description_id' => $record['standard_description_id'],
                            'last_updated' => $record['last_updated']
                        ]);
                    } else {
                        Product::create([
                            'brand_id' => $record['brand_id'],
                            'product_nr' => $record['product_nr'],
                            'name' => $record['name'],
                            'description' => $record['description'],
                            'cross_reference_numbers' => $record['cross_reference_numbers'],
                            'associated_part_numbers' => $record['associated_part_numbers'],
                            'company_sku' => $record['company_sku'],
                            'standard_description_id' => $record['standard_description_id'],
                            'last_updated' => $record['last_updated']
                        ]);
                    }
                } catch (\Throwable $th) {
                    //throw $th;
                }
            }
        }

        if ($table == 'porduct_company_web_statuses_tmp') {
            $this->insertOrUpdateProductCompanyWebStatus();
        }

        echo "\nProcessed " . count($records) . "\n";
        return true;
    }

    protected function createProductsTempTable()
    {
        $sql1 = "DROP TABLE IF EXISTS `products_tmp`;";
        $sql2 = "CREATE TABLE `products_tmp` (
                  `brand_id` bigint(20) UNSIGNED NOT NULL,
                  `product_nr` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                  `cross_reference_numbers` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                  `associated_part_numbers` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                  `fitting_position` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                  `company_sku` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                  `brake_system` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                  `length` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                  `height` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                  `thickness` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                  `weight` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                  `standard_description_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                  `last_updated` date COLLATE utf8mb4_unicode_ci DEFAULT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

        DB::statement($sql1);
        DB::statement($sql2);
    }

    protected function insertOrUpdateProducts()
    {

        $sql = "INSERT INTO products (brand_id, product_nr, name, description, cross_reference_numbers, associated_part_numbers, fitting_position, company_sku, brake_system, length, height, thickness, weight, standard_description_id, last_updated) SELECT t.brand_id, t.product_nr, t.name, t.description, t.cross_reference_numbers, t.associated_part_numbers, t.fitting_position, t.company_sku, t.brake_system, t.length, t.height, t.thickness, t.weight, t.standard_description_id, t.last_updated FROM products_tmp t ON DUPLICATE KEY UPDATE brand_id = t.brand_id, product_nr = t.product_nr, name = t.name, description = t.description, cross_reference_numbers = t.cross_reference_numbers, associated_part_numbers = t.associated_part_numbers, fitting_position = t.fitting_position, company_sku = t.company_sku, brake_system = t.brake_system, length = t.length, height = t.height, thickness = t.thickness, weight = t.weight, standard_description_id = t.standard_description_id, last_updated = t.last_updated";

        DB::statement($sql);
        Log::info("Complete Insert or Update product");
        // $this->truncateTable('products_tmp');
    }

    protected function dropTable($table)
    {

        $sql = "DROP TABLE IF EXISTS `$table`";
        DB::statement($sql);
    }

    protected function truncateTable($table)
    {
        $sql = "TRUNCATE TABLE " . $table;
        DB::statement($sql);
    }

    protected function importVehicles()
    {

        $this->truncateTable('vehicle_tmp');

        $all_models = Models::all();
        //$db_vehicles = Vehicle::all()->pluck('id')->toArray();

        $vehicles_array = [];
        foreach ($all_models as $key => $model) {
            $vehicles = $this->partsdbapirepository->getAllVehicleByMakesAndModels($model['make_id'], $model['name']);
            if (count($vehicles) > 0) {
                echo "id is $model[id] / make is $model[make_id] / name is $model[name] / count is ";
            };
            echo count($vehicles) .  "\n";
            foreach ($vehicles as $vehicle) {
                // if(is_array($db_vehicles) && !in_array($vehicle->VehicleID, $db_vehicles)) {
                try {
                    $vehicles_array[] = [
                        'id' => $vehicle->VehicleID,
                        'make_id' =>  $vehicle->MakeID,
                        'model_id' =>  $model->id,
                        'year_from' => $vehicle->YearFrom,
                        'year_to' => $vehicle->YearTo,
                        'year_range' => $vehicle->YearRange,
                        'sub_model' => $vehicle->Series,
                        'chassis_code' => $vehicle->Chassis,
                        'cc' => $vehicle->cc,
                        'power' => $vehicle->KW,
                        'body_type' => $vehicle->BodyType,
                        'brake_system' => $vehicle->BrakeSystem,
                        // 'created_at' => Carbon::now(),
                        // 'updated_at' => Carbon::now()
                    ];
                } catch (\Exception $e) {
                    Log::info($vehicle->toArray());
                }

                //echo "Vehicles Fetched : " . count($vehicles_array) . "\n";
                if (count($vehicles_array) >= 100) {
                    VehicleTemp::insert($vehicles_array);
                    echo "Vehicles Inserted : " . count($vehicles_array) . "\n\n";
                    $vehicles_array = [];
                }
            }
        }

        if (count($vehicles_array) > 0) {
            VehicleTemp::insert($vehicles_array);
            echo "Vehicles Inserted : " . count($vehicles_array) . "\n";
            $vehicles_array = [];
        }

        echo "Start : insertOrUpdateVehicles \n";
        $this->insertOrUpdateVehicles();
        echo "End : insertOrUpdateVehicles \n";
        $this->truncateTable('vehicle_tmp');
    }

    protected function insertOrUpdateVehicles()
    {

        $sql = "INSERT INTO vehicles (id, make_id, model_id, year_from, year_to, year_range, sub_model, chassis_code, engine_code, cc, power, body_type, brake_system) SELECT t.id, t.make_id, t.model_id, t.year_from, t.year_to, t.year_range, t.sub_model, t.chassis_code, t.engine_code, t.cc, t.power, t.body_type, t.brake_system FROM vehicle_tmp t ON DUPLICATE KEY UPDATE make_id = t.make_id, model_id = t.model_id, year_from = t.year_from, year_to = t.year_to, year_range = t.year_range, sub_model = t.sub_model, chassis_code = t.chassis_code, engine_code = t.engine_code, cc = t.cc, power = t.power, body_type = t.body_type, brake_system = t.brake_system";

        DB::statement($sql);
    }

    protected function importVehicleEngineCode()
    {

        $this->truncateTable('vehicle_tmp');

        $vehicle_ids = Vehicle::all()->pluck('id')->toArray();
        $engine_code_array = [];
        foreach ($vehicle_ids as $vehicle_id) {
            $engine_code =  $this->partsdbapirepository->GetEngineCodesByVehicleID($vehicle_id);

            if (count($engine_code) > 0) {

                $engine_code_array[] = [
                    'id' => $vehicle_id,
                    'engine_code' => implode(",", $engine_code)
                ];
            }

            if (count($engine_code_array) >= 1000) {
                VehicleTemp::insert($engine_code_array);
                echo "Vehicle engine code Inserted : " . count($engine_code_array) . "\n\n";
                $engine_code_array = [];
            }
        }
        if (count($engine_code_array) > 0) {
            VehicleTemp::insert($engine_code_array);
            echo "Vehicle engine code Inserted : " . count($engine_code_array) . "\n\n";
            $engine_code_array = [];
        }

        echo "Start : insertOrUpdateVehiclesEngineCode \n";
        $this->insertOrUpdateVehiclesEngineCode();
        echo "End : insertOrUpdateVehiclesEngineCode \n";

        $this->truncateTable('vehicle_tmp');
    }

    protected function insertOrUpdateVehiclesEngineCode()
    {

        $sql = "INSERT INTO vehicles (id, engine_code) SELECT t.id, t.engine_code FROM vehicle_tmp t ON DUPLICATE KEY UPDATE engine_code = t.engine_code";

        DB::statement($sql);
    }

    protected function importProductVehicleMapping()
    {
        $db_products_nr = Product::selectRaw(DB::raw('CONCAT(product_nr, "_", brand_id) as product_brand, id'))->pluck('product_brand', 'id')->toArray();
        $db_vehicles = Vehicle::all()->pluck('id', 'id')->toArray();
        $product_vehicle = ProductVehicle::selectRaw(DB::raw('CONCAT(product_id, "_", vehicle_id) as product_vehicle'))->pluck('product_vehicle')->toArray();

        $product_vehicle_array = [];
        foreach ($db_products_nr as $product_id => $db_product) {
            try {
                list($product_nr, $brand_id) = explode("_", $db_product);

                try {
                    $vehicles = $this->partsdbapirepository->getVehiclesLinkedToProduct($product_nr, $brand_id);
                } catch (\Throwable $th) {
                    echo $th;
                }
                // echo "Vehicles Product Fetched : " . count($vehicles) . "\n";
                try {
                    $VehicleIDs = array_column($vehicles, "VehicleID");
                } catch (\Throwable $th) {
                    echo $th;
                }

                try {
                    foreach ($VehicleIDs as $VehicleID) {
                        echo "Start loop vehicle: $VehicleID \n";
                        if (!in_array($product_id . "_" . $VehicleID, $product_vehicle) && in_array($VehicleID, $db_vehicles)) {
                            $product_vehicle_array[] = [
                                'product_id' => $product_id,
                                'vehicle_id' => $VehicleID
                            ];
                            $product_vehicle[] = $product_id . "_" . $VehicleID;
                        }
                    }
                } catch (\Throwable $th) {
                    echo $th;
                }

                if (count($product_vehicle_array) >= 100) {
                    echo "insert product vehicle \n";
                    try {
                        ProductVehicle::insert($product_vehicle_array);
                    } catch (\Throwable $th) {
                        echo $th;
                    }
                    echo "Vehicles Product Mapping inserted : " . count($product_vehicle_array) . "\n \n";
                    $product_vehicle_array = [];
                }
            } catch (\Exception $e) {
                echo $e->getMessage() . "\n";
            }
        }

        if (!empty($product_vehicle_array)) {
            try {
                ProductVehicle::insert($product_vehicle_array);
            } catch (\Throwable $th) {
                //throw $th;
            }
            $product_vehicle_array = [];
        }
    }

    // Done by min
    protected function importProductImageMapping()
    {

        $product_image_array = [];

        $location = 0;
        $exist_image = 0;
        $products = Product::selectRaw(DB::raw('CONCAT(product_nr, "_", brand_id) as product_brand, id'))->pluck('product_brand', 'id')->toArray();
        $stored_images = ProductImage::selectRaw(DB::raw('CONCAT(product_id, "_", image) as product_image'))->pluck('product_image')->toArray();

        foreach ($products as $product_id => $product) {
            list($product_nr, $brand_id) = explode("_", $product);
            $product_images =  $this->partsdbapirepository->getProductsImages($brand_id, $product_nr);

            foreach ($product_images as  $product_image) {
                try {
                    if (isset($product_image->ImagesLocation) && !empty($product_image->ImagesLocation)) {
                        $path = $this->PRODUCTS_PATH . $product_image->FileName;
                        $exist_image = Storage::disk('public')->exists($path);

                        $ext = $product_image->Extension;

                        if ($ext != "PDF") {
                            if (!$exist_image) {
                                $contents = file_get_contents($product_image->ImagesLocation);
                                $location = Storage::disk('public')->put($path, $contents);
                            }

                            if ($location || !in_array($product_id . "_" . $product_image->FileName, $stored_images)) {
                                $product_image_array[] = [
                                    'product_id' => $product_id,
                                    'image' =>  $product_image->FileName
                                ];

                                $stored_images[] = $product_id . "_" . $product_image->FileName;
                            }
                        }
                        echo " Uploading to Disk \n";
                    }
                } catch (\Exception $e) {

                    echo $e->getMessage() . "\n";
                }
            }

            if (count($product_image_array) >= 100) {
                echo "\n Images Imported : " . count($product_image_array) . "\n";
                ProductImage::insert($product_image_array);
                $product_image_array = [];
            }
        }

        if (count($product_image_array) > 0) {
            echo "\n Images Imported : " . count($product_image_array) . "\n";
            ProductImage::insert($product_image_array);
            $product_image_array = [];
        }
    }

    protected function importCEDProductCriteria()
    {

        $brands = Brand::all()->pluck('id')->toArray();
        foreach ($brands as $brand_id) {

            $products = Product::where('brand_id', $brand_id)->where('company_sku', '<>', '')->pluck('product_nr', 'company_sku')->toArray();
            $product_id_sku = Product::where('brand_id', $brand_id)->where('company_sku', '<>', '')->pluck('id', 'company_sku')->toArray();
            foreach ($products as $company_sku => $product_nr) {

                try {
                    $criterias = $this->partsdbapirepository->getCEDProductCriteria($brand_id, $product_nr, $company_sku);
                    if (count($criterias) > 0) {

                        CEDProductCriteria::where('product_id', $product_id_sku[$company_sku])->delete();
                        foreach ($criterias as $criteria) {
                            CEDProductCriteria::firstOrCreate([
                                'product_id' => $product_id_sku[$company_sku],
                                'criteria_name' => $criteria->CriteriaName,
                                'criteria_value' => $criteria->CriteriaValue
                            ]);
                        }
                    }
                } catch (\Throwable $th) {
                    echo 'Ced Product Criteria not found in the partsdb api';
                }
                echo 'Get Product Cretia from API \n';
            }
        }
    }

    protected function importPorductCompanyWebStatus()
    {
        $this->createProductCompanyWebStatusTempTable();
        $db_brands = Brand::all()->pluck('id')->toArray();
        $products_array = [];
        foreach ($db_brands as $brand_id) {

            $products = $this->partsdbapirepository->getCEDProductsSubscribed($brand_id);
            foreach ($products as $product) {
                $products_array[] = [
                    'company_sku' => $product->CompanySKU,
                    'product_nr' => $product->ProductNr,
                    'company_web_status' => $product->CompanyWeb
                ];
            }

            if (count($products_array) >= 1000) {
                $this->process($products_array, 'porduct_company_web_statuses_tmp');
                $products_array = [];
            }
        }

        if (count($products_array) > 0) {
            $this->process($products_array, 'porduct_company_web_statuses_tmp');
            $products_array = [];
        }

        $this->dropTable('porduct_company_web_statuses_tmp');
    }

    protected function createProductCompanyWebStatusTempTable()
    {
        $sql1 = "DROP TABLE IF EXISTS `porduct_company_web_statuses_tmp`;";
        $sql2 = "CREATE TABLE `porduct_company_web_statuses_tmp` (
          `id` bigint(20) UNSIGNED NOT NULL,
          `product_nr` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
          `company_sku` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
          `company_web_status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

        DB::statement($sql1);
        DB::statement($sql2);
    }

    protected function insertOrUpdateProductCompanyWebStatus()
    {

        $sql = "INSERT INTO porduct_company_web_statuses (product_nr, company_sku, company_web_status ) SELECT t.product_nr, t.company_sku, t.company_web_status FROM porduct_company_web_statuses_tmp t ON DUPLICATE KEY UPDATE company_web_status = t.company_web_status";

        DB::statement($sql);

        $this->truncateTable('porduct_company_web_statuses_tmp');
    }
}
