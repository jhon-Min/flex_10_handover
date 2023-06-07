<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryAddress extends Model
{
    // 
    protected $hidden = [
        'created_at', 'updated_at',
    ];
    protected $fillable = array('user_id', 'first_name', 'last_name', 'company_name', 'address_line1', 'address_line2', 'state', 'zip', 'mobile', 'email', 'created_at', 'updated_at');
}
