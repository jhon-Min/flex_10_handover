<?php

namespace App\Services\Admin;

use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use App\Helpers\Helper;

class OrderService
{
    public function lists()
    {
        $data['orders'] = Order::with(['user'])->where('status', '!=', '6')->orderBy('id', 'DESC')->get();
        $data['status'] = Config::get('constant.order_status');
        return view('order.orders', $data);
    }

    public function tableLists()
    {
        $orders =  Order::with(['user'])->where('status', '!=', '6')->orderBy('id', 'DESC');
        return DataTables::of($orders)
            ->editColumn('name', function ($data) {
                return $data->user->name;
            })
            ->filterColumn('name', function ($query, $keyword) {
                $query->whereHas('user', fn ($q) => $q->where(DB::raw('concat(first_name," ",last_name)'), 'like', '%' . $keyword . '%'));
            })
            // ->order('name', function($query, $keyword) {
            //     $query->whereHas('user', fn($q) => $q->where(DB::raw('concat(first_name," ",last_name)'), 'like','%'. $keyword . '%'));
            // })
            ->editColumn('email', function ($data) {
                return $data->user->email;
            })
            ->filterColumn('email', function ($query, $keyword) {
                $query->whereHas('user', fn ($q) => $q->where('email', 'like', '%' . $keyword . '%'));
            })
            ->editColumn('order_number', function ($data) {
                return $data->order_number;
            })
            ->editColumn('order_status', function ($data) {
                return $data->status_badge;
            })
            ->filterColumn('order_status', function ($query, $keyword) {
                // Config::get('constant.order_status_badge')[$this->status]
                $query->where('status', 'like', '%' . $keyword . '%');
            })
            ->editColumn('order_total', function ($data) {
                return number_format((float) $data->total, 2, '.', '');
            })
            ->filterColumn('order_total', function ($query, $keyword) {
                $query->where('total', 'like', '%' . $keyword . '%');
            })
            ->editColumn('delivery_method', function ($data) {
                return $data->delivery_type;
            })
            ->filterColumn('delivery_method', function ($query, $keyword) {
                $query->where('delivery_method', 'like', '%' . $keyword . '%');
            })
            ->editColumn('order_date', function ($data) {
                return date('d/m/Y', strtotime($data->created_at));
            })
            ->filterColumn('order_date', function ($query, $keyword) {
                $query->where('created_at', 'like', '%' . $keyword . '%');
            })
            ->addColumn('action', function ($data) {
                $url_delete = route('order.delete', ['id' => $data->id]);

                $store_path = Config::get('constant.INVOICES_PATH') . $data->invoice;
                $download_url = ($data->invoice) ? Storage::disk('public')->url($store_path) : 'javascript:void(0);';

                return "<a href=\"javascript:void(0);\" onclick=\"orderSatusModal('" . $data->id . "','" . $data->order_number . "','" . $data->status . "')\" class=\"badge badge-info color-white\"><i class=\"la la-edit\"></i></a><a href=\"javascript:void(0);\" title=\"Delete\" onclick=\"confirmation_alert('Order','Delete','" . $url_delete . "')\" class=\"badge badge-danger color-white\"><i class=\"la la-trash\"></i></a><a href=\"javascript:void(0);\" title=\"View Invoice\" class=\"badge badge-warning color-white\" onclick=\"window.open('" . $download_url . "','_blank')\" ><i class=\"la la-eye\"></i></a>";
            })
            ->rawColumns(['name', 'email', 'order_number', 'order_status', 'order_total', 'delivery_method', 'order_date', 'action'])
            ->only(['name', 'email', 'order_number', 'order_status', 'order_total', 'delivery_method', 'order_date', 'action'])
            ->make(true);
    }

    public function updateOrder($request)
    {
        try {
            $order = Order::find($request->order);
            DB::transaction(function () use ($order, $request) {
                $old_status = $order->status;
                $order->status = $request->order_status;
                $order->save();

                if ($old_status == Config::get('constant.BACK_ORDER_STATUS_ID') && $request->order_status == Config::get('constant.SUBMITTED_ORDER_STATUS_ID')) {
                    foreach ($order->items as $item) {
                        $product = $item->product;
                        $product->qty = $product->qty - $item->qty;
                        $product->save();
                    }
                }
            });

            $badge = Config::get('constant.order_status_badge')[$request->order_status];
            $label = Config::get('constant.order_status')[$request->order_status];
            $mail_attributes = [
                'mail_template' => "emails.admin_order_action",
                'mail_to_email' => $order->user->email,
                'mail_to_name' => $order->user->name,
                'mail_subject' => "FlexibleDrive : Your Order is " . $label,
                'mail_body' => [
                    'order' => $order,
                    'action' => $label,
                ]
            ];

            // Helper::sendEmail($mail_attributes);
            return response()->json(['message' => 'Order is ' . $label], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }

    public function delete($id)
    {
        try {
            $validator = Validator::make(['id' => $id], [
                "id" => "required|integer|exists:orders,id",
            ]);
            if ($validator->fails()) {
                return response()->json(['message' => implode('-', $validator->errors()->all())], 401);
            }
            $order = Order::find($id);
            $mail_attributes = [
                'mail_template' => "emails.admin_order_action",
                'mail_to_email' => $order->user->email,
                'mail_to_name' => $order->user->name,
                'mail_subject' => "FlexibleDrive : Your Order Deleted",
                'mail_body' => [
                    'order' => $order,
                    'action' => 'Deleted',
                ]
            ];
            if ($order->favourite) {
                $order->favourite->delete();
            }
            $is_delete = $order->delete();

            if ($is_delete) {
                // Helper::sendEmail($mail_attributes);
                $response = [
                    'success' => '1',
                    'message' => 'Order has been Deleted',
                    'delete' => 'order_' . $id,
                ];
                return response()->json($response, 200);
            } else {
                return response()->json(['message' => 'some thing went wrong. Try again!'], 401);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }
}
