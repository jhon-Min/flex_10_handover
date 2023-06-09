<?php

namespace App\Http\Controllers\Api;

use App\Make;
use App\Models;
use App\Vehicle;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BaseController;
use App\Repositories\ProductsRepository;

class SearchController extends BaseController
{

    /**
     * Create a new  instance.
     *
     * @return void
     */
    public function __construct(ProductsRepository $productsRepository)
    {
        $this->productsRepository = $productsRepository;        
    }

    /**
     * @group Search Filters
     * Product Count
     * This API will return count of product.
     * @queryParam make_id (Integer) Make ID ( Table : "makes" ) Example:99
     * @queryParam model_id (Integer) Model ID ( Table : "models" ) Example:353
     * @queryParam sub_model (String) car sub model Example:2.0 i
     * @queryParam year (Integer) Year Example:1985
     * @queryParam chassis_code (String) car chassis code Example:69,AF3,78
     * @queryParam engine_code  (String) car engine code   Example:B201
     * @queryParam cc (String) car engine cc  Example:1985
     * @queryParam power (String) car engine power  Example:81
     * @queryParam body_type (String) car body type Example:Hatchback
     * @queryParam rego_number (String) vehicle Rego Number Example:99
     * @queryParam state String (ACT, NSW, NT, QLD, SA, TAS, VIC, WA) Example:SA
     * @queryParam vin_number (String) VIN Number Example:JF1BL5KS57G03135
     * 
     *  
     */
    public function index(Request $request)
    {
        try {
            
            $filters = $request->all();
            //$filters['count'] = true;
            $products = $this->productsRepository->getProducts($filters, false);
            $vehicles = $this->productsRepository->getVehicles($filters, false);
            return $this->sendResponse(['count' => count($products), 'vehicles' => $vehicles], "Products Count");

        }  catch(\Exception $e) {

            return $this->sendError($e->getMessage(), [], 401);
        }
    }
    /**
     * @group Search Filters
     * Search Form Dropdowns
     * This API will return Dropdown values.
     * @queryParam make_id required (Integer) Make ID ( Table : "makes" ) Example:99
     * @queryParam model_id required (Integer) Model ID ( Table : "models" ) Example:353
     * @queryParam sub_model (String) car sub model Example:2.0 i
     * @queryParam year (Integer) Year Example:1985
     * @queryParam chassis_code (String) car chassis code Example:69,AF3,78
     * @queryParam engine_code  (String) car engine code   Example:B201
     * @queryParam cc (String) car engine cc  Example:1985
     * @queryParam power (String) car engine power  Example:81
     * @queryParam body_type (String) car body type Example:Hatchback
     *  
     */
    public function searchProductsDropdowns(Request $request)
    {
        try {
             
            $filters = $request->all();
            for ($i = 1934; $i <= date('Y'); $i++) {

                $data['years'][] = $i;
            }
            $vehicles = Vehicle::orderBy('id', 'ASC');
            
            if(array_key_exists('make_id', $filters) && $filters['make_id'] > 0){
                $vehicles->where(function($query) use($filters) {
                    $query->where('make_id', $filters['make_id']);
                });
            }

            if(array_key_exists('model_id', $filters) && $filters['model_id'] > 0){
                $vehicles->where(function($query) use($filters) {
                    $query->where('model_id', $filters['model_id']);
                });
            }

            if(array_key_exists('year', $filters) && $filters['year'] > 0){
                $vehicles->where(function($query) use($filters) {
                    $query->where('year_from','<=',$filters['year']);
                    $query->where('year_to','>=',$filters['year']);
                });
            }            

            $data['sub_models'] = array_values(array_unique(array_filter($vehicles->pluck('sub_model')->toArray())));
            if(array_key_exists('sub_model', $filters) && !empty( $filters['sub_model'])){
                $vehicles->where(function($query) use($filters) {
                    $query->where('sub_model', $filters['sub_model']);
                });
            }

            $data['chassis_code'] = array_values(array_unique(array_filter($vehicles->pluck('chassis_code')->toArray())));
            if(array_key_exists('chassis_code', $filters) && !empty( $filters['chassis_code'])){
                $vehicles->where(function($query) use($filters) {
                    $query->where('chassis_code', $filters['chassis_code']);
                });
            }

            $data['engine_code'] = array_values(array_unique(array_filter($vehicles->pluck('engine_code')->toArray())));
            if(array_key_exists('engine_code', $filters) && !empty( $filters['engine_code'])){
                $vehicles->where(function($query) use($filters) {
                    $query->where('engine_code', $filters['engine_code']);
                });
            }
            
            $data['body_type'] = array_values(array_unique(array_filter($vehicles->pluck('body_type')->toArray())));
            return $this->sendResponse($data, "Search Results");
        }  catch(\Exception $e) {

            return $this->sendError($e->getMessage(), [], 401);
        }
    }

    /**
     * @group Search Filters
     * Search Form Makes
     * This API will return All Makes (Car Company).
     *  
     */
    public function makes()
    { 
        try {
            $common_makes = Make::where('is_common','1')->orderBy('name', 'ASC')->get();
            $makes = Make::orderBy('name', 'ASC')->get();
            $all_makes = [
                'common' => $common_makes,
                'all' => $makes,
            ];
            return $this->sendResponse($all_makes, "Makes");
        } catch(\Exception $e) {

            return $this->sendError($e->getMessage(), [], 401);
        }
    }

    /**
     * @group Search Filters
     * Search Form Models
     * This API will return All Models of particuler make(company) makes/{2}/models.
     *  
     */
    public function models($id)
    {

        try {
            
            $models = Models::where('make_id', $id)->select('id','name')->get();
            return $this->sendResponse($models, "Models");
        } catch(\Exception $e) {

            return $this->sendError($e->getMessage(), [], 401);
        }
    }
}
