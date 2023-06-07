<?php

return [

    'PRODUCTS_PATH' => 'products/',             
    'INVOICES_PATH' => 'invoices/',
    'USER_IMAGE_PATH' => 'users/',
    'MARKET_INTEL_IMAGE_PATH' => 'marketintel/',
    'BANNER_IMAGE_PATH' => 'banners/',
    'password_reset_timeout' => env('PASSWORD_RESET_LINK_TIMEOUT',120),
    'front_url' => env('FRONT_URL', 'https://ecat.flexibledrive.com.au'),
    'HELP_LINE_NUMBER' => env('HELP_LINE_NUMBER', '1300 363 735'),
    'WSDL_URL' => env('WSDL_URL', 'https://api.flexibledrive.com.au:28843/adxwsvc/services/CAdxWebServiceXmlCC?wsdl'),
    'invoice'  => [
        'freight' => 0,
        'currency' => "AUD",
        'gst' => 10,
        'delivery_charges' => 0,
    ],    
    'fullfilmemt_method' => [
        '1' => 'Delivery',
        '2' => 'Pick Up',
        '3' => 'Pick Up & Delivery'
    ],    
    'order' => [
        'prefix' => 'FD',
        'digit' => 7,
    ],          
    'user_account_status' => [
        '1' => 'Pending',
        '2' => 'Approved',
        '3' => 'Declined'
    ],
    'user_account_status_lables' => [
        '1' => '<label class="badge badge-warning">Pending</label>',
        '2' => '<label class="badge badge-success">Approved</label>',
        '3' => '<label class="badge badge-danger">Declined</label>',
    ],
    'order_status' => [
        '0' => 'Submitted',
        '1' => 'Processing',
        '2' => 'Delivering',
        '3' => 'Completed',
        '4' => 'Cancelled',
        '5' => 'Back Order',
        '6' => 'Abandon Cart',
    ],
    'order_status_badge' => [
        '0' => '<label class="badge badge-warning">Submitted</label>',
        '1' => '<label class="badge badge-primary" style="background-color: #736cc7">Processing</label>',
        '2' => '<label class="badge badge-info">Delivering</label>',
        '3' => '<label class="badge badge-success">Completed</label>',
        '4' => '<label class="badge badge-danger">Cancelled</label>',
        '5' => '<label class="badge badge-danger">Back Order</label>',
        '6' => '<label class="badge badge-danger">Abandon Cart</label>',
    ],

    'SUBMITTED_ORDER_STATUS_ID' => 0,
    'BACK_ORDER_STATUS_ID' => 5,

    'ADMINISTRATOR_EMAIL_ACT' => env('ADMINISTRATOR_EMAIL_ACT','vicsales@flexibledrive.com.au'),
    'ADMINISTRATOR_EMAIL_NSW' => env('ADMINISTRATOR_EMAIL_NSW','vicsales@flexibledrive.com.au'),
    'ADMINISTRATOR_EMAIL_NT' => env('ADMINISTRATOR_EMAIL_NT','vicsales@flexibledrive.com.au'),
    'ADMINISTRATOR_EMAIL_QLD' => env('ADMINISTRATOR_EMAIL_QLD','vicsales@flexibledrive.com.au'),
    'ADMINISTRATOR_EMAIL_SA' => env('ADMINISTRATOR_EMAIL_SA','vicsales@flexibledrive.com.au'),
    'ADMINISTRATOR_EMAIL_TAS' => env('ADMINISTRATOR_EMAIL_TAS','vicsales@flexibledrive.com.au'),
    'ADMINISTRATOR_EMAIL_VIC' => env('ADMINISTRATOR_EMAIL_VIC','vicsales@flexibledrive.com.au'),
    'ADMINISTRATOR_EMAIL_WA' => env('ADMINISTRATOR_EMAIL_WA','vicsales@flexibledrive.com.au'),    
];
