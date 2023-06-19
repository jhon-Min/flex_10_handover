<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use App\Models\Cart;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use \DB;

class AbandonedCartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $ordersQuery =  Cart::with(['user']);
        // // $ordersQuery->where('status', '=', '6');
        // $ordersQuery->groupBy('user_id');
        // $orders = $ordersQuery->get();
        // $data['orders'] = $orders;
        $data['status'] = Config::get('constant.order_status');
        return view('abandoned-cart.abandoned-cart', $data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $user_id = $request->user_id;

        $ordersQuery =  Cart::with(['product', 'user']);
        $ordersQuery->where('user_id', '=', $user_id);

        // $sql = $ordersQuery->toSql();
        // $bindings = $ordersQuery->getBindings();

        $orders_data = $ordersQuery->get();
        $array_products = [];


        $intCnt = 0;
        $download_url = '';

        $data_return = [];

        foreach ($orders_data as $orders) {
            $product = $orders->product;
            $cart_user = $orders->user;
            $product_images = $product->images;

            $data_return['user']['name'] = $orders->user->name;
            $data_return['user']['email'] = $orders->user->email;
            $data_return['user']['date'] = date('d/m/Y', strtotime($orders->created_at));


            $product_image = '';
            if (!empty($product_images)) {
                foreach ($product_images as $key => $image) {
                    $product_image = $image->image;
                    $store_path = Config::get('constant.PRODUCTS_PATH') . $product_image;
                    if (Storage::disk('public')->exists($store_path)) {
                        $download_url = Storage::disk('public')->url($store_path);
                    } else {
                        $store_path = Config::get('constant.PRODUCTS_PATH') . 'default.png';
                        $download_url = Storage::disk('public')->url($store_path);
                    }
                }
            } else {
                $store_path = Config::get('constant.PRODUCTS_PATH') . 'default.png';
                $download_url = Storage::disk('public')->url($store_path);
            }

            $retail_price = $product->getPriceNettAttribute($user_id);
            $sub_total = $retail_price * $orders->qty;



            $array_products[$intCnt]['product_id'] = $product->id;
            $array_products[$intCnt]['part_numer'] = $product->product_nr;
            $array_products[$intCnt]['image_url'] = $download_url;
            $array_products[$intCnt]['price'] = number_format((float) $retail_price, 2, '.', '');
            $array_products[$intCnt]['qty'] = $orders->qty;
            $array_products[$intCnt]['total'] = number_format((float) $sub_total, 2, '.', '');;

            $intCnt++;
        }



        $data_return['products'] = $array_products;

        return response()->json($data_return);
    }

    public function getAbandonedCartDatatable(Request $request)
    {
        $ordersQuery =  Cart::query()->with(['user']);
        // $ordersQuery =  DB::table('cart')->leftJoin('users as user',function($join){
        //     $join->on('cart.user_id','user.id');
        // })->select([
        //     'user.email',
        //     'cart.created_at',
        //     'cart.user_id',
        //     'cart.invoice',
        // ])->addSelect(DB::raw("concat(first_name,' ',last_name) as name"));

        // $ordersQuery->where('status', '=', '6');

        $ordersQuery->groupBy('cart.user_id');
        return Datatables::of($ordersQuery)
            ->editColumn('name', function ($data) {
                return $data->user->name;
            })
            ->editColumn('email', function ($data) {
                return $data->user->email;
            })
            ->filterColumn('name', function($query, $keyword) {
                $query->whereHas('user', fn($q) => $q->where(DB::raw('concat(first_name," ",last_name)'), 'like','%'. $keyword . '%'));
            })
            ->filterColumn('email', function($query, $keyword) {
                $query->whereHas('user', fn($q) => $q->where('email','like', '%'. $keyword . '%'));
            })
            // ->editColumn('order_number', function ($data) {
            //     return $data->order_number;
            // })
            // ->addColumn('order_status', function ($data) {
            //     return $data->status_badge;
            // })
            // ->addColumn('order_total', function ($data) {
            //     return number_format((float) $data->total, 2, '.', '');
            // })
            // ->addColumn('delivery_method', function ($data) {
            //     return $data->delivery_type;
            // })
            ->addColumn('order_date', function ($data) {
                return date('d/m/Y', strtotime($data->created_at));
            })
            ->addColumn('action', function ($data) {
                $url_delete = route('cart.delete', ['id' => $data->user_id]);

                $store_path = Config::get('constant.INVOICES_PATH') . $data->invoice;
                $download_url = ($data->invoice) ? Storage::disk('public')->url($store_path) : 'javascript:void(0);';

                return "<a href=\"javascript:void(0);\" onclick=\"cartItemsModal('" . $data->user_id . "')\" class=\"badge badge-info color-white\"><i class=\"la la-eye\"></i></a><a href=\"javascript:void(0);\" title=\"Delete\" onclick=\"confirmation_alert('Order','Delete','" . $url_delete . "')\" class=\"badge badge-danger color-white\"><i class=\"la la-trash\"></i></a>";
            })
            ->rawColumns(['name', 'email', 'order_date', 'action'])
            ->only(['name', 'email', 'order_date', 'action'])
            ->make(true);
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
                "id" => "required|integer|exists:cart,user_id",
            ]);
            if ($validator->fails()) {
                return response()->json(['message' => implode('-', $validator->errors()->all())], 401);
            }

            $is_delete = Cart::where('user_id', $id)->delete();


            // $order = Cart::find($id);
            // $mail_attributes = [
            //     'mail_template' => "emails.admin_order_action",
            //     'mail_to_email' => $order->user->email,
            //     'mail_to_name' => $order->user->name,
            //     'mail_subject' => "FlexibleDrive : Your Order Deleted",
            //     'mail_body' => [
            //         'order' => $order,
            //         'action' => 'Deleted',
            //         ]
            // ];
            // $is_delete = $order->delete();
            // $order->favourite->delete();
            if ($is_delete > 0) {
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
