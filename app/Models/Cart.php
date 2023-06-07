<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\ProductVehicles;

class Cart extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cart';
    protected $hidden = [
        'created_at', 'updated_at',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['product_id', 'user_id', 'qty'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function ProductVehicles()
    {
        return $this->belongsTo(ProductVehicles::class, 'product_id');
    }
}
