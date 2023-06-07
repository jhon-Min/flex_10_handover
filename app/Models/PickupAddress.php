<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PickupAddress extends Model
{
    //
    protected $hidden = [
        'created_at', 'updated_at',
    ];

    protected $fillable = array('user_id', 'pickup_location_id', 'first_name', 'last_name', 'pickup_date_time', 'mobile', 'created_at', 'updated_at');

    public function location()
    {
        return $this->belongsTo(Branch::class, 'pickup_location_id');
    }
}
