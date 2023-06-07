<?php

namespace App\Models;

use Auth;
use JWTAuth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $hidden = [
        'created_at', 'updated_at',
    ];
    protected function getAuth()
    {
        try {
            $token = JWTAuth::getToken();
            $user = JWTAuth::toUser($token);
            $user_id = $user->id;
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $th) {
            //throw $th;
            $user_id = 0;
        }
        return $user_id;
    }
    protected $appends = ['price_nett', 'price_retail'];

    public function getPriceNettAttribute($user_id = NULL)
    {
        if (empty($user_id)) {
            $user_id = $this->getAuth();
        }
        $price_data = ProductPrice::where('product_id', $this->id)->where('user_id', $user_id)->first();

        if ($price_data) {
            $price = $price_data->price_nett;
        } else {
            $price = 0;
        }

        return number_format($price, 2);
    }
    public function getPriceRetailAttribute($user_id = NULL)
    {
        if (empty($user_id)) {
            $user_id = $this->getAuth();
        }
        $price_data = ProductPrice::where('product_id', $this->id)->where('user_id', $user_id)->first();

        if ($price_data) {
            $price = $price_data->price_retail;
        } else {
            $price = 0;
        }

        return number_format($price, 2);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }

    public function notes()
    {
        return $this->hasMany(Note::class, 'product_id')->where('user_id', $this->getAuth())->with('user');
    }

    public function categories()
    {

        return $this->belongsToMany(Category::class, 'product_categories', 'product_id', 'category_id');
    }

    public function vehicles()
    {

        return $this->belongsToMany(Vehicle::class, 'product_vehicles', 'product_id', 'vehicle_id');
    }

    public function productPrice()
    {
        return $this->hasMany(ProductPrice::class, 'product_id');
    }

    public function criteria()
    {
        return $this->hasMany(CEDProductCriteria::class, 'product_id');
    }

    public function scopeCompanyWebStatus($query)
    {
        return $query->whereExists(function ($query) {
            $query->select(\DB::raw(1))
                ->from('porduct_company_web_statuses as pcws')
                ->whereRaw('pcws.product_nr = products.product_nr AND pcws.company_sku = products.company_sku AND pcws.company_web_status = "L"');
        });
    }
}
