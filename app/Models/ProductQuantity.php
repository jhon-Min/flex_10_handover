<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductQuantity extends Model
{
    protected $table = 'product_quantities';
    protected $fillable = ['id', 'product_id', 'branch_id', 'company_sku', 'qty'];
}
