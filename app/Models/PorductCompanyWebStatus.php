<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PorductCompanyWebStatus extends Model
{
    protected $table = "porduct_company_web_statuses";
    protected $fillable = ['product_nr', 'company_sku', 'company_web_status'];
}
