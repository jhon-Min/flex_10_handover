<?php

namespace App\Models;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Model;

class MarketIntel extends Model
{
    use SoftDeletes;
    use Sluggable;
    use SluggableScopeHelpers;
    protected $hidden = [
        'updated_at', 'deleted_at'
    ];

    protected $fillable = ['id', 'title', 'description', 'url', 'short_description', 'image', 'created_at', 'updated_at', 'deleted_at'];
    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'blog_slug' => [
                'source' => 'title'
            ]
        ];
    }

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return  Storage::disk('public')->url(Config::get('constant.MARKET_INTEL_IMAGE_PATH')) . $this->image;
        }
    }
}
