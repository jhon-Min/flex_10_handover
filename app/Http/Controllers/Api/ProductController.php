<?php

namespace App\Http\Controllers\Api;

use Auth;
use JWTAuth;
use Config;
use Storage;
use Validator;
use App\Product;
use App\ProductQuantity;
use App\Branch;
use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\BaseController;
use App\Repositories\ProductsRepository;
use Symfony\Component\Console\Input\Input;

class ProductController extends BaseController
{

    /**
     * Create a new  instance.
     *
     * @return void
     */
    public function __construct(ProductsRepository $productsrepository)
    {
        $this->productsrepository = $productsrepository;

        $this->PRODUCTS_PATH = Config::get('constant.PRODUCTS_PATH');
    }

    /**
     * @group Products
     * All Products
     * This API will be use for product catalogue, quick search , advance search.
     * @queryParam page (Integer) page number
     * @queryParam per_page (Integer) items per page
     * @queryParam brand_id (Integer) Brand ID ( Table : "brands" )
     * @queryParam category_id (Integer) Category ID ( Table : "categories" )
     * @queryParam products  (Array) Product ID , items (Integer)  ( Table : "products" )
     * @queryParam product_nr (String) ProductNr (number)
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
     * @queryParam sort_column (String) sort product list liek Price , name etc. Example:price_nett
     * @queryParam sort_order (String) sort by assanding or Desancding. Example:ASC
     * 
     * 
     * 
     * 
     */
    public function index(Request $request)
    {       

        try{ 
            $paginate = $request->input('paginate', true);

            $validator = \Validator::make($request->all(), [
                "brand_id" => "integer|exists:brands,id",
                "category_id" => "integer|exists:categories,id",                
                "products" => "array",
                "products.*" => "integer|exists:products,id",
                "paginate" => "boolean",
                "sort_column" => "string",
                "sort_order" => "string|in:ASC,DESC,asc,desc"
            ]);

            if($validator->fails()) {
                return $this->sendError('Invalid input', $validator->errors()->all(),401);
            }

            $products = $this->productsrepository->getProducts($request->all(), $paginate, $request->page, $request->per_page);

            return $this->sendResponse($products, "Products");

        } catch(\Exception $e) {

            return $this->sendError($e->getMessage(), [], 401);
        }

    }

    /**
     * @group Products
     * Product Detail
     * For get particular product detail. here need to pass product id ie (api/product/10/detail).
     * 
     * @param id required product id 
     * 
     * 
     */
    public function show($id)
    {    
        
        $validator = Validator::make(['id' => $id],[
            "id" => "required|exists:products"
        ]);
 
        try{     
            if($validator->fails()) {
                return $this->sendError("validation errors", $validator->errors()->all(), 400);    
            }
            $product = Product::with(['brand','vehicles','vehicles.make','vehicles.model','images','categories','notes','criteria'])->find($id)->toArray();
            
            if($product['associated_part_numbers']) {
                $product['associated_parts'] = $this->productsrepository->getAssociatedProducts(explode(",", $product['associated_part_numbers']));
            } else {
                $product['associated_parts'] = [];
            }

            $qty_with_location = array();
            $branchWiseQty = ProductQuantity::where('product_id', $product['id'])->get();
            $branchWiseQtyForPush = array();
            foreach($branchWiseQty as &$branchQty){
                $branch = Branch::where('id', $branchQty->branch_id)->first();
                $branchQty['branch'] = $branch;
                array_push($branchWiseQtyForPush, $branchQty);
            }
            $product['qty_with_location'] = $branchWiseQtyForPush;
            
            return $this->sendResponse($product, "Product details");

        } catch(\Exception $e) {

            return $this->sendError($e->getMessage(), [], 401);
        }
    }
	
	public function searchbysku($sku)
    {    
        
        $validator = Validator::make(['sku' => $sku],[
            "sku" => "required"
        ]);
 
        try{     
            if($validator->fails()) {
                return $this->sendError("validation errors", $validator->errors()->all(), 400);    
            }
            $product = Product::with(['brand','vehicles','vehicles.make','vehicles.model','images','categories','notes','criteria'])->where('company_sku', $sku)->first();
            
			if(empty($product)){
				return $this->sendResponse('',"Product not found!");    
			}
			
            if($product['associated_part_numbers']) {
                $product['associated_parts'] = $this->productsrepository->getAssociatedProducts(explode(",", $product['associated_part_numbers']));
            } else {
                $product['associated_parts'] = [];
            }

            $qty_with_location = array();
            $branchWiseQty = ProductQuantity::where('product_id', $product['id'])->get();
            $branchWiseQtyForPush = array();
            foreach($branchWiseQty as &$branchQty){
                $branch = Branch::where('id', $branchQty->branch_id)->first();
                $branchQty['branch'] = $branch;
                array_push($branchWiseQtyForPush, $branchQty);
            }
            $product['qty_with_location'] = $branchWiseQtyForPush;
            
            return $this->sendResponse($product, "Product details");

        } catch(\Exception $e) {

            return $this->sendError($e->getMessage(), [], 401);
        }
    }
}
