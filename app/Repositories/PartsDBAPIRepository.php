<?php

namespace App\Repositories;

use Ixudra\Curl\Facades\Curl;
use App\Traits\API;
use Illuminate\Support\Facades\Session;

class PartsDBAPIRepository extends BaseRepository
{
    use API;

    public function login()
    {
        $response = Curl::to(config('partsdb.endpoint') . config('partsdb.auth.login'))
            ->withData(array('UserName' => config('partsdb.auth.username'), 'Password' => config('partsdb.auth.password')))
            ->withResponseHeaders()
            ->returnResponseObject()
            ->get();
        Session::put('Auth-Cookie', $response->headers['set-cookie']);
    }

    public function getAllBrands()
    {
        $brands = $this->callPartsDBAPI(config('partsdb.brand.all'));
        return $brands->ListResult;
    }

    public function getAllCategories()
    {
        $categories = $this->callPartsDBAPI(config('partsdb.ced-categories-all'));
        return $categories->ListResult ?? [];
    }

    public function getAllMakes()
    {
        $makes = $this->callPartsDBAPI(config('partsdb.makes.all'));
        return $makes;
    }

    public function getAllMakesAndModels()
    {
        $makes_and_models = $this->callPartsDBAPI(config('partsdb.makes-models'));
        return $makes_and_models;
    }

    public function getAllVehicleByMakesAndModels($make, $model)
    {
        $vehicles = $this->callPartsDBAPI(config('partsdb.vehicle'), ['MakeID' => $make, 'Model' => $model]);
        return $vehicles ?? [];
    }

    public function getproductsbyvehicleid($vehicle_id)
    {
        $products = $this->callPartsDBAPI(config('partsdb.product-by-vehicle-id'), ['VehicleID' => $vehicle_id]);
        return isset($products->ListProductsExtVO) ? $products->ListProductsExtVO : [];
    }

    public function getProductCategoriesByBrandIDAndProductNr($brand_id, $product_nr)
    {
        $categories = $this->callPartsDBAPI(
            config('partsdb.ced-category-by-brandid-productnr'),
            [
                'BrandID' => $brand_id,
                'ProductNr' => $product_nr
            ]
        );
        return isset($categories->ListResult) ? $categories->ListResult : [];
    }

    public function getProductCorrespondingPartNmuber($brand_id, $product_nr, $sku)
    {
        $corresponding_numbers = $this->callPartsDBAPI(
            config('partsdb.corresponding-part-number'),
            [
                'BrandID' => $brand_id,
                'ProductNr' => $product_nr,
                'SKU' => $sku
            ]
        );
        return isset($corresponding_numbers->ListResult) ? $corresponding_numbers->ListResult : [];
    }

    public function getProductsImages($brand_id, $art_nr)
    {
        $corresponding_numbers = $this->callPartsDBAPI(
            config('partsdb.product-reach-content-images'),
            [
                'BrandID' => $brand_id,
                'ArtNr' => $art_nr
            ]
        );
        return isset($corresponding_numbers) ? $corresponding_numbers : [];
    }

    public function getProductFittingPositions($brand_id, $product_nr, $Stand_desc_id)
    {
        $product_fittings = $this->callPartsDBAPI(
            config('partsdb.product-fitting-position'),
            [
                'BrandID' => $brand_id,
                'ProductNr' => $product_nr,
                'StandDescID' => $Stand_desc_id
            ]
        );
        return isset($product_fittings) ? $product_fittings : [];
    }

    public function getProductAttributes($brand_id, $product_nr, $Stand_desc_id)
    {
        $product_attributes = $this->callPartsDBAPI(
            config('partsdb.product-attributes'),
            [
                'BrandID' => $brand_id,
                'ProductNr' => $product_nr,
                'StandardDescriptionID' => $Stand_desc_id
            ]
        );
        return isset($product_attributes) ? $product_attributes : [];
    }

    public function getProductsSubscribed($brand_id, $PageNum, $PageSize)
    {
        $products = $this->callPartsDBAPI(config('partsdb.products-subscribed'), ['BrandID' => $brand_id, 'PageNum' => $PageNum, 'PageSize' => $PageSize]);
        return $products ?? [];
    }

    public function getAllProducts($brand_id)
    {
        $products = $this->callPartsDBAPI(config('partsdb.products-subscribed'), ['BrandID' => $brand_id]);
        return $products ?? [];
    }

    public function getCEDCategoryProducts($category_id)
    {
        $products_all = [];
        $products_per_page = [];
        $products = $this->callPartsDBAPI(config('partsdb.ced-category-products'), ['CategoryID' => $category_id]);
        $per_page = 100;
        $total_record = $products->TotalRecord;
        if ($total_record > 0) {

            $pages = round($total_record / $per_page);
            if ($pages > 0) {
                for ($i = 1; $i <= $pages; $i++) {
                    $products_data = $this->callPartsDBAPI(config('partsdb.ced-category-products'), ['CategoryID' => $category_id, 'PageNum' => $i]);

                    $products_all = array_merge($products_all, $products_data->ListResult);
                }
            } else {
                $products_all = $products->ListResult;
            }
        } else {
            $products_all = $products->ListResult;
        }
        return $products_all;


        // return $products->ListResult ?? [];
    }

    public function getVehiclesLinkedToProduct($product_nr, $brand_id)
    {
        $vehicles = $this->callPartsDBAPI(config('partsdb.vehicle-linkedwith-products'), [
            'ProductNr' => $product_nr,
            'BrandID' => $brand_id
        ]);
        return $vehicles ?? [];
    }

    public function GetEngineCodesByVehicleID($vehicle_id)
    {
        $engine_codes = $this->callPartsDBAPI(
            config('partsdb.get-engine-codes'),
            [
                'VehicleID' => $vehicle_id
            ]
        );
        return isset($engine_codes) ? $engine_codes : [];
    }

    public function getVehiclesByRegoNumber($rego_number, $state, $country_code)
    {
        $vehicles = $this->callPartsDBAPI(
            config('partsdb.vehicles-by-rego-number'),
            [
                'rego' => $rego_number,
                'state' => $state,
                'CountryCode' => $country_code
            ]
        );

        return $vehicles->Data->ListResult ?? [];
    }

    public function getVehiclesByVIN($vin_number)
    {
        $vehicles = $this->callPartsDBAPI(
            config('partsdb.vehicles-by-vin-number'),
            [
                'VIN' => $vin_number
            ]
        );

        return $vehicles->Data->ListResult ?? [];
    }

    public function getProductCriteria($brand_id, $product_nr, $standD_desc_id)
    {
        $product_criteria = $this->callPartsDBAPI(
            config('partsdb.product-criteria-details'),
            [
                'BrandID' => $brand_id,
                'ProductNr' => $product_nr,
                'StandDescID' => $standD_desc_id
            ]
        );

        return $product_criteria ?? [];
    }

    public function getCEDProductCategories($product_nr, $brand_id)
    {
        $categories_all = [];
        $categories_per_page = [];
        $categories = $this->callPartsDBAPI(config('partsdb.ced-category-by-brandid-productnr'), [
            'ProductNr' => $product_nr,
            'BrandID' => $brand_id
        ]);

        if (isset($categories->TotalRecord) && $categories->TotalRecord > 0) {
            return $categories->ListResult;
        } else {
            return [];
        }
    }

    public function getCEDProductCriteria($brand_id, $product_nr, $company_sku)
    {
        $criterias = [];

        $criteria = $this->callPartsDBAPI(config('partsdb.ced-product-criteria'), [
            'BrandID' => $brand_id,
            'ProductNr' => $product_nr,
            'SKU' => $company_sku
        ]);

        if ($criteria->TotalRecord > 0) {

            $criterias = array_merge($criterias, $criteria->ListResult);
            if ($criteria->TotalRecord > count($criteria->ListResult)) {

                $per_page = count($criteria->ListResult);
                $total_pages = ceil($criteria->TotalRecord / count($criteria->ListResult));
                for ($page = 2; $page <= $total_pages; $page++) {

                    $criteria = $this->callPartsDBAPI(config('partsdb.ced-product-criteria'), [
                        'BrandID' => $brand_id,
                        'ProductNr' => $product_nr,
                        'SKU' => $company_sku,
                        'PageNum' => $page,
                        'PageSize' => $per_page
                    ]);

                    if ($criteria->TotalRecord > 0) {
                        $criterias = array_merge($criterias, $criteria->ListResult);
                    }
                }
            }
        }

        return $criterias;
    }

    public function getCEDProductsSubscribed($brand_id)
    {
        $products = $this->callPartsDBAPI(config('partsdb.ced-products-subscribed'), ['BrandID' => $brand_id]);
        return $products ?? [];
    }
}
