<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $hidden = [
        'created_at', 'updated_at',
    ];

    protected $fillable = ['id', 'parent_id', 'name', 'description', 'icon', 'image', 'created_at', 'updated_at'];

    public function products()
    {
        return $this->hasMany(ProductCategory::class, 'category_id');
    }
}
