<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    //
    protected $hidden = [
        'created_at', 'updated_at',
    ];

    protected $fillable = array('product_id', 'order_id', 'qty', 'price', 'total', 'delivery_address_id', 'pickup_address_id', 'order_status', 'created_at', 'updated_at');

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function delivery()
    {
        return $this->belongsTo(DeliveryAddress::class, 'delivery_address_id');
    }
    public function pickup()
    {
        return $this->belongsTo(PickupAddress::class, 'pickup_address_id');
    }
}
