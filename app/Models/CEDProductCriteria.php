<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CEDProductCriteria extends Model
{
    protected $table = 'ced_product_criteria';
    protected $fillable = ['product_id', 'criteria_name', 'criteria_value'];
    protected $hidden = [
        'created_at', 'updated_at',
    ];
}
