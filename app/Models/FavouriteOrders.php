<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FavouriteOrders extends Model
{
    protected $hidden = [
        'created_at', 'updated_at',
    ];
    protected $fillable = array('user_id', 'order_id', 'created_at', 'updated_at');
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
