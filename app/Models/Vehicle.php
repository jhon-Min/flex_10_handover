<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $hidden = [
        'created_at', 'updated_at',
    ];
    //
    public function products()
    {
        return $this->hasMany(ProductVehicle::class, 'vehicle_id');
    }

    public function make()
    {
        return $this->belongsTo(Make::class, 'make_id');
    }

    public function model()
    {
        return $this->belongsTo(Models::class, 'model_id');
    }
}
