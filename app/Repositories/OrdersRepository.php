<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrdersRepository extends BaseRepository
{

    public function getOrders($filters = array(), $paginate = FALSE, $page = 1, $per_page = 20)
    {

        $orders = Order::where('user_id', Auth::user()->id)->with([
            'items.product',
            'items.product.brand',
            'items.product.vehicles',
            'items.product.vehicles.make',
            'items.product.vehicles.model',
            'items.product.images',
            'items.product.categories',
            'items.delivery',
            'items.pickup',
            'items.pickup.location'
        ])->orderBy('id', 'DESC');
        if (isset($filters['order_number']) && !empty($filters['order_number'])) {
            $orders->where(function ($orders) use ($filters) {
                $orders->where('order_number', 'like', '%' . $filters['order_number'] . '%');
                $orders->orWhere('reference_number', 'like', '%' . $filters['order_number'] . '%');
            });
        }

        $start = '';
        $end = '';

        if (isset($filters['from_date']) && !empty($filters['from_date'])) {
            $start = Carbon::parse($filters['from_date'])->startOfDay();
        }
        if (isset($filters['to_date']) && !empty($filters['to_date'])) {
            $end = Carbon::parse($filters['to_date'])->endOfDay();
        }

        $orders->where(function ($q)  use ($start, $end) {
            if (!empty($start) && !empty($end)) {
                $q->whereBetween('created_at', [$start, $end]);
            } else if (!empty($start)) {
                $q->where('created_at', '>=', $start);
            } else if (!empty($end)) {
                $q->where('created_at', '<=', $end);
            }
        });
        if ($paginate) {
            $orders = $orders->paginate($per_page);
        } else {
            $orders = $orders->get();
        }
        return $orders;
    }
}
