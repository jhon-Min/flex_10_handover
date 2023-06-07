<?php

namespace App\Models;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class BannerManagement extends Model
{
    use SoftDeletes;
    protected $hidden = [
        'updated_at', 'deleted_at'
    ];
    protected $fillable = ['id', 'image', 'created_at', 'updated_at', 'deleted_at'];
    protected $appends = ['image_url'];
    public function getImageUrlAttribute()
    {
        if ($this->image) {

            return  Storage::disk('public')->url(Config::get('constant.BANNER_IMAGE_PATH')) . $this->image;
        }
    }
}
