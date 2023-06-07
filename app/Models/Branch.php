<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $table = 'branches';
    protected $hidden = ['created_at', 'updated_at'];
    protected $fillable = ['id', 'code', 'name', 'created_at', 'updated_at', 'city', 'state', 'phone', 'email', 'contact', 'mobile'];
}
