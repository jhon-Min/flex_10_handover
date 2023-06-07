<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductPrice extends Model
{
    protected $table = 'product_prices';
    protected $fillable = array('user_id', 'product_id', 'price_nett', 'price_retail',  'company_sku', 'created_at', 'updated_at', 'deleted_at');


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
