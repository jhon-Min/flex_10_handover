<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Make extends Model
{
    protected $fillable = ['id', 'name'];
    protected $hidden = [
        'created_at', 'updated_at',
    ];
    public function makeProduct()
    {
        return $this->hasMany(Product::class, 'make_id');
    }

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class, 'make_id');
    }

    public function products()
    {
        return $this->hasManyThrough('App\ProductVehicle', 'App\Vehicle');
    }
}
