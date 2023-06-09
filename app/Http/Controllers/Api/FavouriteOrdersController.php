<?php

namespace App\Http\Controllers\Api;

use Auth;
use Response;
use App\Order;
use Validator;
use Carbon\Carbon;
use App\FavouriteOrders;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BaseController;

class FavouriteOrdersController extends BaseController
{
    /**
     * @group Order
     * Favourite Orders
     * @queryParam page (Integer) page number
     * @queryParam per_page (Integer) items per page
     * @response{
     *       "success": true,
     *       "data": {
     *           "current_page": 1,
     *           "data": [
     *               {
     *                   "id": 4,
     *                   "user_id": 8,
     *                   "order_id": 60,
     *                   "order": {
     *                       "id": 60,
     *                       "user_id": 8,
     *                       "order_number": "FD0000060",
     *                       "subtotal": 21,
     *                       "gst": 2.1,
     *                       "delivery": 10,
     *                       "total": 33.1,
     *                       "status": 2,
     *                       "reference_number": null,
     *                       "repeat_order": 0,
     *                       "delivery_method": "2",
     *                       "invoice": "invoice_FD0000060.pdf",
     *                       "created_at": "2019-10-18 04:42:15",
     *                       "status_label": "Delivering",
     *                       "status_badge": "<label class=\"badge badge-info\">Delivering</label>",
     *                       "delivery_type": "Pick Up",
     *                       "invoice_url": "http://virtualhost.com/storage/invoices/invoice_FD0000060.pdf",
     *                       "user": {
     *                           "id": 8,
     *                           "first_name": "Krutik",
     *                           "last_name": "Patel",
     *                           "email": "kkrutikk@gmail.com",
     *                           "email_verified_at": null,
     *                           "account_code": "FD002",
     *                           "company_name": "Test Company",
     *                           "address_line1": "New Test Street",
     *                           "address_line2": "Gandhinagar",
     *                           "state": "Test State",
     *                           "zip": "1224",
     *                           "profile_image": "npDRw6Smaa.jpg",
     *                           "mobile": "1234567890",
     *                           "admin_approval_status": 2,
     *                           "created_at": "2019-09-27 08:53:57",
     *                           "updated_at": "2019-10-22 10:09:11",
     *                           "image_url": "http://flexibledrive.localhost.com/storage/users/npDRw6Smaa.jpg",
     *                           "name": "Krutik Patel"
     *                       },
     *                       "items": [
     *                           {
     *                               "id": 44,
     *                               "product_id": 5,
     *                               "order_id": 60,
     *                               "qty": 3,
     *                               "price": 5,
     *                               "total": 15,
     *                               "delivery_address_id": 0,
     *                               "pickup_address_id": 9,
     *                               "order_status": null,
     *                               "product": {
     *                                   "id": 5,
     *                                   "brand_id": 153,
     *                                   "product_nr": "0002.30",
     *                                   "name": "Brake Pad Set",
     *                                   "description": "Brake Pad Set",
     *                                  "cross_reference_numbers": "FD1394,GDB1318",
     *                                  "associated_part_numbers": "001002",
     *                                  "price_nett": 10,
     *                                  "price_retail": 0,
     *                                  "fitting_position": "Front Axle",
     *                                  "length": null,
     *                                  "height": null,
     *                                  "thickness": null,
     *                                  "weight": null,
     *                                  "standard_description_id": "402",
     *                                  "brand": {
     *                                      "id": 153,
     *                                      "name": "REMSA",
     *                                      "logo": ""
     *                                  },
     *                                  "vehicles": [
     *                                     {
     *                                         "id": 311,
     *                                         "make_id": 92,
     *                                         "model_id": 359,
     *                                         "year_from": 1984,
     *                                         "year_to": 1987,
     *                                         "year_range": "02/84-06/87",
     *                                         "sub_model": "3.2 SC Carrera",
     *                                         "chassis_code": null,
     *                                         "engine_code": null,
     *                                         "cc": "3164",
     *                                         "power": "152",
     *                                         "body_type": "Coupe",
     *                                         "brake_system": "Hydraulic",
     *                                         "pivot": {
     *                                             "product_id": 5,
     *                                              "vehicle_id": 311
     *                                          },
     *                                          "make": {
     *                                              "id": 92,
     *                                              "name": "PORSCHE"
     *                                          },
     *                                          "model": {
     *                                              "id": 359,
     *                                              "make_id": 92,
     *                                              "name": "911"
     *                                          }
     *                                      }
     *                                  ],
     *                                  "images": [],
     *                                  "categories": []
     *                              }
     *                          }
     *                      ]
     *                  }
     *              }
     *          ],
     *          "first_page_url": "http://flexibledrive.localhost.com/api/orders/favourite?page=1",
     *          "from": 1,
     *          "last_page": 1,
     *          "last_page_url": "http://flexibledrive.localhost.com/api/orders/favourite?page=1",
     *          "next_page_url": null,
     *          "path": "http://flexibledrive.localhost.com/api/orders/favourite",
     *          "per_page": 20,
     *          "prev_page_url": null,
     *          "to": 1,
     *          "total": 1
     *      },
     *      "message": "favourite orders."
     *  }
     * 
     *
     * 
     */
    public function index(Request $request)
    {
        try {
            $paginate = $request->input('paginate', true);
            $per_page = ($request->per_page) ? $request->per_page : 15;
            $favrite_orders = FavouriteOrders::where('user_id',Auth::user()->id)->with([
                'order',
                'order.user',
                'order.items',
                'order.items.product',
                'order.items.product.brand',
                'order.items.product.vehicles',
                'order.items.product.vehicles.make',
                'order.items.product.vehicles.model',
                'order.items.product.images',
                'order.items.product.categories',
                ]);
                
            if (isset($request->order_number) && !empty($request->order_number)) {
                $favrite_orders->whereHas('order',function ($orders) use ($request) {
                    $orders->where('order_number', 'like', '%' . $request->order_number . '%');
                    $orders->orWhere('reference_number', 'like', '%' . $request->order_number . '%');
                });
            }
 
            $start = '';
            $end = '';

            if (isset($request->from_date) && !empty($request->from_date)) {
                $start = Carbon::parse($request->from_date)->startOfDay();
            }
            if (isset($request->to_date) && !empty($request->to_date)) {
                $end = Carbon::parse($request->to_date)->endOfDay();
            }

            $favrite_orders->whereHas('order', function ($q)  use ($start, $end) {
                if (!empty($start) && !empty($end)) {
                    $q->whereBetween('created_at', [$start, $end]);
                } else if (!empty($start)) {
                    $q->where('created_at', '>=', $start);
                } else if (!empty($end)) {
                    $q->where('created_at', '<=', $end);
                }
            });


            if($paginate) {
                $favrite_orders = $favrite_orders->paginate($per_page);
            } else {
                $favrite_orders = $favrite_orders->get();
            }
            return $this->sendResponse($favrite_orders, "favourite orders.");
        } catch (\Exception $e) {

            return $this->sendError($e->getMessage(), [], 401);
        }
    }


    /**
     * @group Order
     * Add Order to favourite
     * @bodyParam order_id Integer required Order Id (Table : orders) Example: 144
     * @response{
     *       "success": true,
     *       "data": {
     *           "order_id": 60,
     *           "user_id": 8,
     *           "id": 3
     *       },
     *       "message": "Order added to favourite"
     *   }
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "order_id" => "required|exists:orders,id|unique:favourite_orders,order_id",
            ],['order_id.unique' => 'Aleady Added to favourite']);

            if ($validator->fails()) {
                return $this->sendError("validation errors", $validator->errors()->all(), 400);
            }
            $order = Order::where('id', $request->order_id)->where('user_id',Auth::user()->id)->first();
            if(isset($order->id) && !empty($order->id)) {
                $favourite = [
                    'order_id'=> $order->id,
                    'user_id'=> Auth::user()->id,
                ];
                $save_favrite = FavouriteOrders::create($favourite);
                if(isset($save_favrite->id)) {
                    return $this->sendResponse($save_favrite, "Order added to favourite");
                }
            }
            return $this->sendError("You are not authorise to add this order to favourite",[], 401);
        } catch (\Exception $e) {

            return $this->sendError($e->getMessage(), [], 401);
        }
    }

    /**
     * @group Order
     * Remove from favourite
     * @response{
     *       "success": true,
     *       "data": [],
     *       "message": "Order removed from favourite"
     *   }
     *  
     */
    public function destroy($order_id)
    {
        try {
            $validator = Validator::make(['order_id' => $order_id], [
                "order_id" => "required|exists:favourite_orders,order_id",
            ],['order_id.exists' => 'Order no longer exists in your favorit list.']);

            if ($validator->fails()) {
                return $this->sendError("validation errors", $validator->errors()->all(), 400);
            }
            $order = FavouriteOrders::where('order_id', $order_id)->where('user_id',Auth::user()->id)->delete();
            
            if($order) {
                return $this->sendResponse([], "Order removed from favourite");
            }
            return $this->sendError("You are not authorise to add this order to favourite",[], 401);
        } catch (\Exception $e) {

            return $this->sendError($e->getMessage(), [], 401);
        }
    }
}
