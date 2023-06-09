<?php

namespace App\Http\Controllers\Api;

use App\Category;
use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BaseController;
use App\Repositories\ProductsRepository;

class CategoryController extends BaseController
{
    public function __construct(ProductsRepository $productsRepository)
    {
        $this->productsRepository = $productsRepository;        
    }

    public function positions (Request $request){
        try{ 
                             
            $message = "All fitting positions";
            $fitting_positions = Product::select('fitting_position')->whereRaw('fitting_position is not null and fitting_position != \'\'')->distinct()->get();

            if (!empty($request->all())) {
                foreach($fitting_positions as $fitting_position) {
                    $filters = $request->all();
                    $filters['count'] = true;
                    $filters['fitting_position'] = $fitting_position->fitting_position;
                    $products = $this->productsRepository->getProducts($filters);
                    $fitting_position->product_count = $products;
                }
            } else {
                foreach($fitting_positions as $fitting_position) {
                    $fitting_position->product_count = Product::where('fitting_position', 'like', '%' . $fitting_position['fitting_position'] . '%')->count();
                }
            }

            return $this->sendResponse($fitting_positions, $message);

        } catch(\Exception $e) {

            return $this->sendError($e->getMessage(), [], 401);
        }
    }

    /** 
     * @group Categories
     * Get all Categories
     * This API will be use for get all Categories and products count.
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
     * @queryParam state (String) ACT, NSW, NT, QLD, SA, TAS, VIC, WA Example:SA
     * @queryParam vin_number (String) VIN Number Example:JF1BL5KS57G03135
     * 
     */
    public function index(Request $request)
    {
        try{ 
                             
            $message = "All Categories";
            $categories = Category::all();
            
            if(!empty($request->all())) {
                foreach ($categories as $category) {
                    $filters = $request->all();
                    $filters['count'] = true;
                    $filters['category_id'] = $category->id;
                    $products = $this->productsRepository->getProducts($filters);
                    $category->product_count = $products;
                }
            } else {
                foreach ($categories as $category) {
                    $category->product_count = $category->products()->count();                    
                }

            }

            $fitting_positions = Product::select('fitting_position')->distinct()->get();
            
            return $this->sendResponse($categories, $message);

        } catch(\Exception $e) {

            return $this->sendError($e->getMessage(), [], 401);
        }
    }

}
