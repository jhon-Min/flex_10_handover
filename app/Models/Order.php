<?php

namespace App\Models;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\DeliveryAddress;
use App\Models\PickupAddress;

class Order extends Model
{
    use SoftDeletes;

    protected $hidden = ['updated_at', 'deleted_at'];
    protected $appends = ['status_label', 'status_badge', 'delivery_type', 'invoice_url', 'is_favourite', 'delivery_info'];
    protected $dates = ['deleted_at'];
    protected $fillable = array('user_id', 'order_number', 'subtotal', 'gst', 'delivery', 'total', 'reference_number', 'repeat_order', 'delivery_method', 'created_at', 'updated_at', 'status', 'is_external_pickup');

    public function items()
    {
        return $this->hasMany(OrderProduct::class, 'order_id');
    }

    public function favourite()
    {
        return $this->hasOne(FavouriteOrders::class, 'order_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getIsFavouriteAttribute()
    {
        return (bool) $this->favourite()->first();
    }

    public function getStatusLabelAttribute()
    {
        return Config::get('constant.order_status')[$this->status];
    }

    public function getStatusBadgeAttribute()
    {
        return Config::get('constant.order_status_badge')[$this->status];
    }

    public function getDeliveryTypeAttribute()
    {
        return Config::get('constant.fullfilmemt_method')[$this->delivery_method];
    }

    public function getInvoiceUrlAttribute()
    {
        if ($this->invoice && Storage::disk('public')->exists(Config::get('constant.INVOICES_PATH') . $this->invoice)) {
            $invoice_url = Storage::disk('public')->url(Config::get('constant.INVOICES_PATH') . $this->invoice);
        } else {
            $invoice_url = '';
        }
        return $invoice_url;
    }

    public function getDeliveryInfoAttribute()
    {
        $pickup = $delivery = null;

        $items = $this->items->toArray();
        $delivery_address = array_values(array_filter(array_unique(array_column($items, 'delivery_address_id'))));
        $pickup_address = array_values(array_filter(array_unique(array_column($items, 'pickup_address_id'))));

        if (!empty($delivery_address)) {
            list($delivery_address_id) = $delivery_address;
            if ($delivery_address_id > 0) {
                $delivery = DeliveryAddress::find($delivery_address_id)->toArray();
                $delivery['products'] = OrderProduct::where('order_id', $this->id)->where('delivery_address_id', $delivery_address_id)->pluck('product_id');
            }
        }

        if (!empty($pickup_address)) {
            $address = PickupAddress::with('location')->where('id', $pickup_address)->first()->toArray();
            if ($address && $address["id"]) {
                if (Branch::find($address["pickup_location_id"]) != null) {
                    $pickup = Branch::where('id', $address["pickup_location_id"])->first()->toArray();
                }
            }
        }


        $is_external = false;
        if (!empty($pickup_address) && !empty($delivery_address)) {
            $is_external = true;
        }
        $info = [
            'delivery_method' => $this->delivery_method,
            'delivery' => $delivery,
            'pickup' => $pickup,
            'is_external_pickup' => $is_external
        ];

        return $info;
    }
}
