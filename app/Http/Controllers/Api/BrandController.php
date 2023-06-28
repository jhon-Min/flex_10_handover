<?php

namespace App\Http\Controllers\Api;

use App\Models\Brand;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Repositories\ProductsRepository;
use App\Repositories\PartsDBAPIRepository;

class BrandController extends BaseController
{
    public $productsRepository, $partsdbapirepository;

    public function __construct(ProductsRepository $productsRepository, PartsDBAPIRepository $partsdbapirepository)
    {
        $this->productsRepository = $productsRepository;
        $this->partsdbapirepository = $partsdbapirepository;
    }

    /**
     * @group Brands
     * Get all brands
     * This API will be use for get all brands and products count.
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

        try {
            $this->partsdbapirepository->login();
            $brand_api =  $this->partsdbapirepository->getAllBrands();

            foreach ($brand_api as $brand) {
                $brands_data = Brand::firstOrCreate(
                    [
                        'id' => $brand->ID,
                        'name' => $brand->BrandName,
                    ],
                );
                $brands_data->logo = $brand->ImagesLocation . $brand->LogoFileName;
                $brands_data->save();
            }

            $brands = Brand::all();
            $message = "All brands";

            if (!empty($request->all())) {

                foreach ($brands as $brand) {
                    $filters = $request->all();
                    $filters['count'] = true;
                    $filters['brand_id'] = $brand->id;
                    $products = $this->productsRepository->getProducts($filters);
                    $brand->product_count = $products;
                }
            } else {
                foreach ($brands as $brand) {
                    $brand->product_count = $brand->products()->count();
                }
            }

            return $this->sendResponse($brands, $message);
        } catch (\Exception $e) {

            return $this->sendError($e->getMessage(), [], 401);
        }
    }
}
