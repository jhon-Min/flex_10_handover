<?php

return [
    'endpoint' => 'https://api.partsdb.com.au/v1.1/',
    'auth' => [
        'username' => 'partsapi@flexibledrive.com.au',
        'password' => 'Flexibledrive123',
        'login' => 'Authentication/Login',
    ],
    'brand' => [
        'all' => 'Brand/All',
        'productcount' => 'Product/ProductsSubscribed'
    ],
    'category' => [
        'all' => 'Fitment/GetTreeCategories'
    ],
    'product' => [
        'all' => 'Product/ProductsSubscribed',
        'productprice' => 'Product/GetProductPricing'
    ],
    'makes' => [
        'all' => 'Vehicle/Makes'
    ],
    'makes-models' => 'Vehicle/MakeModels',    
    'vehicle' => 'Vehicle/All',  
    'product-by-vehicle-id' => 'Fitment/ProductsLinkedToVehicleID',  
    'ced-category-by-brandid-productnr' => 'CED/CEDProductCategories',  
    'corresponding-part-number' => 'CED/CEDLinkedProducts',
    'product-reach-content-images' => 'Product/ProductRichContent',
    'product-fitting-position' => 'Product/ProductFittingPositions',
    'product-attributes' => 'Product/ProductAttributes',
    'products-subscribed' => 'Product/ProductsSubscribed',
    'ced-categories-all' => 'CED/CEDCategories',
    'ced-category-products' => 'CED/CEDCategoryProducts',
    'vehicle-linkedwith-products' => 'Fitment/VehiclesLinkedToProduct',
    'get-engine-codes' => 'Vehicle/EngineCode',
    'vehicles-by-rego-number' => 'RegoDecoder/GetDetails',
    'vehicles-by-vin-number' => 'VINDecoder/GetDetails',
    'product-criteria-details' => 'Product/ProductCriteria',
    'ced-product-criteria' => 'CED/CEDProductCriteria',
    'ced-products-subscribed' => 'CED/ProductsSubscribed',
];
