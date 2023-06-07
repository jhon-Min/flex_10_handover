<?php

use Config as Config;

function orderNumber($order_id) {
    $prefix = Config::get('constant.order.prefix');
    $digit = Config::get('constant.order.digit');
    return $prefix.str_pad($order_id, $digit, '0', STR_PAD_LEFT);
}

?>