<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SearchHistory extends Model
{
    protected $fillable = array(
        'user_id', 'search_type',
        'part_number',
        'state',
        'reg_number',
        'vin',
        'make_id',
        'model_id',
        'sub_model',
        'year',
        'chassis_code',
        'engine_code',
        'cc',
        'power',
        'body_type',
        'in_stock', 'created_at', 'updated_at'
    );
}
