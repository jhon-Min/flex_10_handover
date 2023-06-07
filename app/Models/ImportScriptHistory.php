<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImportScriptHistory extends Model
{
    //
    protected $table = "import_script_histories";

    protected $fillable = ['login', 'brand', 'categories', 'make_model', 'products', 'product_categories', 'vehicle', 'product_vehicles', 'product_images', 'vehicle_engine_code', 'start', 'end', 'duration'];
}
