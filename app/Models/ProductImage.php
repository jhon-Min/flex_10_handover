<?php

namespace App\Models;

use Illuminate\Support\Facades\Storage;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $appends = ['image_url'];
    protected $hidden = [
        'created_at', 'updated_at',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     * 
     */
    protected $fillable = ['product_id', 'image', 'created_at', 'updated_at'];

    public function getImageUrlAttribute()
    {

        return  Storage::disk('public')->url('products/') . $this->image;
    }
}
