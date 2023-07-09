<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $fillable = [
        'id',
        'make_id',
        'model_id',
        'year_from',
        'year_to',
        'year_range',
        'sub_model',
        'chassis_code',
        'cc',
        'power',
        'body_type',
        'brake_system',
    ];

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
