<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVehicle extends Model
{
    protected $table = "product_vehicles";
    protected $fillable = ["product_id", "vehicle_id"];
    protected $hidden = [
        'created_at', 'updated_at',
    ];
}
