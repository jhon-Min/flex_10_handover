<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\Order;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['orders'] = Order::with(['user'])->where('status', '!=', '6')->orderBy('id', 'DESC')->get();
        $data['status'] = Config::get('constant.order_status');
        return view('order.orders', $data);
    }

    public function getOrderDatatable(Request $request)
    {
        $orders =  Order::with(['user'])->where('status', '!=', '6')->orderBy('id', 'DESC')->get();
        return Datatables::of($orders)
            ->editColumn('name', function ($data) {
                return $data->user->name;
            })
            ->editColumn('email', function ($data) {
                return $data->user->email;
            })
            ->editColumn('order_number', function ($data) {
                return $data->order_number;
            })
            ->editColumn('order_status', function ($data) {
                return $data->status_badge;
            })
            ->editColumn('order_total', function ($data) {
                return number_format((float) $data->total, 2, '.', '');
            })
            ->editColumn('delivery_method', function ($data) {
                return $data->delivery_type;
            })
            ->editColumn('order_date', function ($data) {
                return date('d/m/Y', strtotime($data->created_at));
            })
            ->editColumn('action', function ($data) {
                $url_delete = route('order.delete', ['id' => $data->id]);

                $store_path = Config::get('constant.INVOICES_PATH') . $data->invoice;
                $download_url = ($data->invoice) ? Storage::disk('public')->url($store_path) : 'javascript:void(0);';

                return "<a href=\"javascript:void(0);\" onclick=\"orderSatusModal('" . $data->id . "','" . $data->order_number . "','" . $data->status . "')\" class=\"badge badge-info color-white\"><i class=\"la la-edit\"></i></a><a href=\"javascript:void(0);\" title=\"Delete\" onclick=\"confirmation_alert('Order','Delete','" . $url_delete . "')\" class=\"badge badge-danger color-white\"><i class=\"la la-trash\"></i></a><a href=\"javascript:void(0);\" title=\"View Invoice\" class=\"badge badge-warning color-white\" onclick=\"window.open('" . $download_url . "','_blank')\" ><i class=\"la la-eye\"></i></a>";
            })
            ->rawColumns(['name', 'email', 'order_number', 'order_status', 'order_total', 'delivery_method', 'order_date', 'action'])
            ->make(true);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "order" => "required|integer|exists:orders,id",
                "order_status" => "required|in:0,1,2,3,4",
            ]);
            if ($validator->fails()) {
                return response()->json(['message' => implode('-', $validator->errors()->all())], 401);
            }
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

            Helper::sendEmail($mail_attributes);
            return response()->json(['message' => 'Order is ' . $label], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
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
            $is_delete = $order->delete();
            $order->favourite->delete();
            if ($is_delete > 0) {
                Helper::sendEmail($mail_attributes);
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
