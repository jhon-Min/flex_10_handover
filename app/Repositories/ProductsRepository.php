<?php

namespace App\Repositories;

use App\Console\Commands\SyncFromPartsDB;
use App\Traits\API;
use App\Models\Brand;
use App\Models\Branch;
use App\Models\Product;
use App\Models\SearchHistory;
use App\Models\ProductCategory;
use App\Models\ProductQuantity;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Repositories\PartsDBAPIRepository;

class ProductsRepository extends BaseRepository
{
    use API;

    public $partsdbapirepository, $last_updated = null, $syncpartdb;

    public function __construct(PartsDBAPIRepository $partsdbapirepository, SyncFromPartsDB $syncpartdb)
    {
        $this->partsdbapirepository = $partsdbapirepository;
        $this->syncpartdb = $syncpartdb;
        $this->last_updated = date('Y-m-d');
    }


    public function getVehicles($filters = array(), $paginate = FALSE, $page = 1, $per_page = 50)
    {
        $vehicles = null;

        if (isset($filters['rego_number']) && isset($filters['state'])) {
            $repository = new PartsDBAPIRepository();
            $repository->login();
            $vehicles = $repository->getVehiclesByRegoNumber($filters['rego_number'], $filters['state'], 'AUS');
        }

        //Filter by vin number
        if (isset($filters['vin_number'])) {
            $repository = new PartsDBAPIRepository();
            $repository->login();
            $vehicles = $repository->getVehiclesByVIN($filters['vin_number']);
        }
        return $vehicles;
    }

    public function fetchProducts()
    {
        $db_brands = Brand::all()->pluck('id')->toArray();

        $products_array = [];
        $ced_array = [];
        $product_nr_sku_category = [];

        foreach ($db_brands as $brand_id) {
            // $PageNum = 1;
            // while ($products = $this->partsdbapirepository->getProductsSubscribed($brand_id, $PageNum, 1000)) {

            // }
            $products_lists = $this->partsdbapirepository->getAllProducts($brand_id);

            foreach ($products_lists as $list) {
                $ced_product_categories =  $this->partsdbapirepository->getCEDProductCategories($list->ProductNr, $list->BrandID);
                $ced_array[] = $ced_array;
            }
        }

        return $ced_array;
    }

    public function getProducts($filters = array(), $paginate = FALSE, $page = 1, $per_page = 50)
    {
        $repository = new PartsDBAPIRepository();
        $repository->login();
        ini_set('max_execution_time', '-1');
        ini_set('memory_limit', '-1');
        return $this->fetchProducts();


        $products = Product::with(['brand', 'vehicles', 'vehicles.make', 'vehicles.model', 'images', 'categories', 'criteria']);
        $products->CompanyWebStatus();

        return $products->get();

        $combined_search = false;
        //Filter by rego number
        if (isset($filters['rego_number']) && isset($filters['state'])) {
            $vehicle_ids_rego = [];
            $repository = new PartsDBAPIRepository();
            $repository->login();
            $vehicles = $repository->getVehiclesByRegoNumber($filters['rego_number'], $filters['state'], 'AUS');
            $vehicle_ids = array_column($vehicles, 'VehicleID');
            if (count($vehicle_ids) > 0) {
                $vehicle_ids_rego = array_unique($vehicle_ids);
            }
            $combined_search = true;
        }

        //Filter by vin number
        if (isset($filters['vin_number'])) {
            $vehicle_ids_vin = [];
            $repository = new PartsDBAPIRepository();
            $repository->login();
            $vehicles = $repository->getVehiclesByVIN($filters['vin_number']);
            $vehicle_ids = array_column($vehicles, 'VehicleID');
            if (count($vehicle_ids) > 0) {
                $vehicle_ids_vin = array_unique($vehicle_ids);
            }
            $combined_search = true;
        }

        $vehicle_ids_array = [];
        if (isset($vehicle_ids_rego) && isset($vehicle_ids_vin)) {
            $vehicle_ids_array = array_intersect($vehicle_ids_rego, $vehicle_ids_vin);
        } elseif (isset($vehicle_ids_rego)) {
            $vehicle_ids_array = $vehicle_ids_rego;
        } elseif (isset($vehicle_ids_vin)) {
            $vehicle_ids_array = $vehicle_ids_vin;
        }

        if ($combined_search) {
            $products->whereHas('vehicles', function ($query) use ($vehicle_ids_array) {
                $query->whereIn('vehicles.id', $vehicle_ids_array);
            });
        }

        // Filter with brand id
        if (isset($filters['brand_id'])) {
            $products->where('brand_id', $filters['brand_id']);
        }

        // Filter with category id
        if (isset($filters['category_id'])) {
            $products->whereHas('categories', function ($query) use ($filters) {
                $query->where('category_id', $filters['category_id']);
            });
        }

        // Filter with product ids
        if (isset($filters['products'])) {
            $products->whereIn('id', $filters['products']);
            $paginate = false;
        }

        // Filter with product nr
        if (isset($filters['product_nr'])) {
            $products->where(function ($query) use ($filters) {
                $query->where('product_nr', $filters['product_nr'])
                    ->orWhere('company_sku', $filters['product_nr'])
                    ->orWhereRaw('FIND_IN_SET("' . $filters['product_nr'] . '",cross_reference_numbers)');
            });
        }

        if (isset($filters['fitting_position'])) {
            $products->where(function ($query) use ($filters) {
                $query->where('fitting_position', 'like', '%' . $filters['fitting_position'] . '%');
            });
        }

        $count_with_join = false;
        if (array_key_exists('year', $filters) || array_key_exists('make_id', $filters) || array_key_exists('sub_model', $filters) || array_key_exists('sub_model', $filters) || array_key_exists('chassis_code', $filters) || array_key_exists('engine_code', $filters) || array_key_exists('cc', $filters) || array_key_exists('power', $filters) || array_key_exists('body_type', $filters)) {

            $products->join('product_vehicles', 'products.id', '=', 'product_vehicles.product_id')
                ->join('vehicles', 'vehicles.id', '=', 'product_vehicles.vehicle_id');

            if (array_key_exists('year', $filters)) {
                $products->raw('(CASE WHEN year_to IS NULL THEN YEAR(CURDATE()) ELSE year_to END) AS select_year_to');
                $products->where(function ($query) use ($filters) {

                    $query->where('year_from', '<=', $filters['year']);
                    $query->where('year_to', '>=', $filters['year']);
                    $query->orWhereNull('year_to');

                    // $query->having('select_year_to', '>=', $filters['year']);
                    // $query->where('year_to','>=',$filters['year']);
                });
            }

            // (CASE WHEN year_to IS NULL THEN YEAR(CURDATE()) ELSE year_to END) AS select_year_to

            if (array_key_exists('make_id', $filters)) {
                $products->where('make_id', '=', $filters['make_id']);
            }

            if (array_key_exists('model_id', $filters)) {
                $products->where('model_id', '=', $filters['model_id']);
            }

            if (array_key_exists('sub_model', $filters)) {
                $products->where('sub_model', 'like', '%' . $filters['sub_model'] . '%');
            }

            if (array_key_exists('chassis_code', $filters)) {
                $products->where('chassis_code', 'like', '%' . $filters['chassis_code'] . '%');
            }

            if (array_key_exists('engine_code', $filters)) {
                $products->where('engine_code', 'like', '%' . $filters['engine_code'] . '%');
            }

            if (array_key_exists('cc', $filters)) {
                $products->where('cc', $filters['cc']);
            }

            if (array_key_exists('power', $filters)) {
                $products->where('power', $filters['power']);
            }

            if (array_key_exists('body_type', $filters)) {
                $products->where('body_type', 'like', '%' . $filters['body_type'] . '%');
            }
            $products->groupBy('products.id');
            $count_with_join = true;
        }

        $sort_column = "products.name";
        $sort_order = "ASC";

        if (isset($filters['sort_column']) && isset($filters['sort_order'])) {
            $sort_column = $filters['sort_column'];
            $sort_order = $filters['sort_order'];
        }

        $products->orderBy($sort_column, $sort_order);


        // return $shirish;

        if (isset($filters['count'])) {
            if ($count_with_join) {
                return $products->get()->count();
            } else {
                return $products->count();
            }
        } elseif ($paginate) {
            $products = $products->select('products.*')->paginate($per_page);

            // return $products = $products->select('products.*')->paginate($per_page);
        } else {
            $products = $products->select('products.*')->get();
            // return $products = $products->select('products.*')->get();
        }


        $qty_with_location = array();

        $flag_no_stock = 0;

        foreach ($products as &$prod) {
            $branchWiseQty = ProductQuantity::where('product_id', $prod->id)->get();
            $branchWiseQtyForPush = array();
            foreach ($branchWiseQty as &$branchQty) {
                $branch = Branch::where('id', $branchQty->branch_id)->first();
                $branchQty['branch'] = $branch;
                array_push($branchWiseQtyForPush, $branchQty);
            }
            $prod['qty_with_location'] = $branchWiseQtyForPush;

            if ($prod->qty > 0) {
                $flag_no_stock = 1;
            }
            array_push($qty_with_location, $prod);
        }
        $products->data = $qty_with_location;

        // Add search history data.
        $no_stock_prducts = ProductQuantity::where('qty', 0)->get();

        $prod_id_arr = [];
        foreach ($no_stock_prducts as $no_stock_prduct) {
            $prod_id_arr[] = $no_stock_prduct->id;
        }
        $prod_id_arr = array_unique($prod_id_arr);


        $part_type = '';
        if (isset($filters['product_nr'])) {
            $part_type = 'part';
        }
        if (isset($filters['make_id']) && isset($filters['model_id']) && isset($filters['year'])) {
            $part_type = 'vehicle';
        }
        if (isset($filters['rego_number']) || isset($filters['state']) || isset($filters['vin_number'])) {
            $part_type = 'registration';
        }

        $user_id = 0;
        $userInfo = Auth('api')->user();
        $user = Auth::guard('api')->user();

        $state = $filters['state'] ?? '';
        if (empty($state)) {
            if (!empty($userInfo)) {
                $user_id = $userInfo->id;
                $state = $userInfo->state;
            }
        }


        $in_stock = $flag_no_stock > 0 ? 1 : 0;
        if (!empty($part_type) && !empty($paginate)) {
            SearchHistory::create([
                'user_id'      => $userInfo->id,
                'search_type'  => $part_type ?? NULL,
                'part_number'  => $filters['product_nr'] ?? NULL,
                'state'        => $state ?? '',
                'reg_number'   => $filters['rego_number'] ?? '',
                'vin'          => $filters['vin_number'] ?? '',
                'make_id'      => $filters['make_id'] ?? NULL,
                'model_id'     => $filters['model_id'] ?? NULL,
                'sub_model'    => $filters['sub_model'] ?? NULL,
                'year'         => $filters['year'] ?? NULL,
                'chassis_code' => $filters['chassis_code'] ?? NULL,
                'engine_code'  => $filters['engine_code'] ?? NULL,
                'cc'           => $filters['cc'] ?? NULL,
                'power'        => $filters['power'] ?? NULL,
                'body_type'    => $filters['body_type'] ?? NULL,
                'in_stock'     => $flag_no_stock ?? NULL,
            ]);
        }

        return $products;
    }

    public function getAssociatedProducts($part_numbers = array())
    {

        if (count($part_numbers) > 0) {
            $products = Product::with(['brand', 'vehicles', 'vehicles.make', 'vehicles.model', 'images', 'categories']);
            $products->whereIn('product_nr', $part_numbers);
            return $products->get()->toArray();
        } else {
            return [];
        }
    }
}
