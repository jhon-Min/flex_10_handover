<?php

namespace App\Http\Controllers\Api;

use App\Mail\MailType;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Helper;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Cart;
use App\Models\Order;
use App\Models\FavouriteOrders;
use App\Models\OrderProduct;
use App\Models\PickupAddress;
use App\Models\DeliveryAddress;
use App\Models\PickupLocation;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Repositories\OrdersRepository;
use App\Http\Controllers\BaseController;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends BaseController
{
    public $ordersrepository, $INVOICES_PATH, $GST, $DELIVERY_CHARGES;

    public function __construct(OrdersRepository $ordersrepository)
    {
        $this->ordersrepository = $ordersrepository;
        $this->INVOICES_PATH = Config::get('constant.INVOICES_PATH');
        $this->GST = Config::get('constant.invoice.gst');
        $this->DELIVERY_CHARGES = Config::get('constant.invoice.delivery_charges');
    }

    /**
     * @group Order
     * All Orders
     * This API will be use for user order list and also for seaech order by ord er by number, and also for get order for particualr date duration.
     *
     * @queryParam page (Integer) page number
     * @queryParam per_page (Integer) items per page
     * @queryParam from_date (Date)  order from date you want to filter
     * @queryParam to_date (Date)  order to date you want to filter
     * @queryParam order_number (String) order number
     * @response {
     *  "success": true,
     *  "data": {
     *      "current_page": 1,
     *      "data": [
     *           {
     *          "id": 62,
     *          "user_id": 8,
     *          "order_number": "FD0000062",
     *          "subtotal": 27236,
     *          "gst": 2723.6,
     *          "delivery": 10,
     *          "total": 29969.6,
     *          "status": 4,
     *          "reference_number": null,
     *          "repeat_order": 0,
     *          "delivery_method": "3",
     *          "invoice": "invoice_FD0000062.pdf",
     *          "created_at": "2019-10-18 10:30:12",
     *          "status_label": "Cancelled",
     *          "status_badge": "<label class=\"badge badge-danger\">Cancelled</label>",
     *          "delivery_type": "Pick Up & Delivery",
     *          "items": [
     *              {
     *                  "id": 47,
     *                  "product_id": 13625,
     *                  "order_id": 62,
     *                  "qty": 1,
     *                  "price": 13625,
     *                  "total": 13625,
     *                  "delivery_address_id": 61,
     *                  "pickup_address_id": 0,
     *                  "order_status": null,
     *                  "product": {
     *                      "id": 13625,
     *                      "brand_id": 10178,
     *                      "product_nr": "SYN0W16",
     *                      "name": "20° Piston Position Template",
     *                      "description": "20° Piston Position Template",
     *                      "cross_reference_numbers": "FD1394,GDB1318",
     *                      "associated_part_numbers": "001002",
     *                     "price_nett": 13625,
     *                      "price_retail": 0,
     *                      "fitting_position": null,
     *                      "length": null,
     *                      "height": null,
     *                      "thickness": null,
     *                      "weight": null,
     *                      "standard_description_id": "2364",
     *                      "brand": {
     *                          "id": 10178,
     *                          "name": "PARts Demo",
     *                          "logo": ""
     *                      },
     *                      "vehicles": [
     *                          {
     *                              "id": 16454,
     *                              "make_id": 111,
     *                              "model_id": 681,
     *                              "year_from": 2011,
     *                              "year_to": 2017,
     *                              "year_range": "09/11-09/17",
     *                              "sub_model": "2.5 Hybrid (AVV50_)",
     *                              "chassis_code": "AVV50",
     *                              "engine_code": null,
     *                              "cc": "2494",
     *                              "power": "118",
     *                              "body_type": "Sedan",
     *                              "brake_system": null,
     *                              "pivot": {
     *                                  "product_id": 13625,
     *                                  "vehicle_id": 16454
     *                              },
     *                              "make": {
     *                                  "id": 111,
     *                                  "name": "TOYOTA"
     *                              },
     *                              "model": {
     *                                  "id": 681,
     *                                  "make_id": 111,
     *                                  "name": "CAMRY"
     *                              }
     *                          }
     *                      ],
     *                      "images": [],
     *                      "categories": []
     *                  },
     *                  "delivery": {
     *                      "id": 61,
     *                      "user_id": 8,
     *                      "first_name": "krutik",
     *                      "last_name": "patel",
     *                      "company_name": null,
     *                      "address_line1": "test",
     *                      "address_line2": "test",
     *                      "state": "VIC",
     *                      "zip": "1234",
     *                      "mobile": "1234567890",
     *                      "email": "kkrutik@gmail.com"
     *                  },
     *                  "pickup": null
     *              },
     *              {
     *                  "id": 48,
     *                  "product_id": 13611,
     *                  "order_id": 62,
     *                  "qty": 1,
     *                  "price": 13611,
     *                  "total": 13611,
     *                  "delivery_address_id": 0,
     *                  "pickup_address_id": 10,
     *                  "order_status": null,
     *                  "product": {
     *                      "id": 13611,
     *                      "brand_id": 6370,
     *                      "product_nr": "BA135",
     *                      "name": "Accelerator Cable",
     *                      "description": "Accelerator Cable",
     *                      "cross_reference_numbers": "FD1394,GDB1318",
     *                      "associated_part_numbers": "001002",
     *                      "price_nett": 13611,
     *                      "price_retail": 0,
     *                      "fitting_position": null,
     *                      "length": null,
     *                      "height": null,
     *                      "thickness": null,
     *                      "weight": null,
     *                      "standard_description_id": "618",
     *                      "brand": {
     *                          "id": 6370,
     *                          "name": "Flexible Drive",
     *                          "logo": ""
     *                      },
     *                       "vehicles": [
     *                          {
     *                               "id": 43230,
     *                               "make_id": 20,
     *                               "model_id": 2470,
     *                               "year_from": 1965,
     *                               "year_to": 1967,
     *                               "year_range": "03/65-03/67",
     *                               "sub_model": "3.7",
     *                               "chassis_code": "AP6",
     *                               "engine_code": null,
     *                               "cc": "3697",
     *                               "power": "109",
     *                               "body_type": "Sedan",
     *                               "brake_system": null,
     *                               "pivot": {
     *                                   "product_id": 13611,
     *                                   "vehicle_id": 43230
     *                               },
     *                               "make": {
     *                                   "id": 20,
     *                                   "name": "CHRYSLER"
     *                               },
     *                               "model": {
     *                                   "id": 2470,
     *                                   "make_id": 20,
     *                                   "name": "VALIANT"
     *                               }
     *                           }
     *                        ],
     *                       "images": [],
     *                       "categories": [
     *                           {
     *                               "id": 6,
     *                               "parent_id": "5",
     *                               "name": "Accelerator Cables",
     *                               "description": "",
     *                               "icon": null,
     *                               "image": null,
     *                               "pivot": {
     *                                   "product_id": 13611,
     *                                   "category_id": 6
     *                               }
     *                           }
     *                       ]
     *                   },
     *                   "delivery": null,
     *                   "pickup": {
     *                       "id": 10,
     *                       "user_id": 8,
     *                       "pickup_location_id": 1,
     *                       "first_name": "krutik",
     *                       "last_name": "patel",
     *                       "mobile": "2345678999",
     *                       "pickup_date_time": "2019-10-20 01:15:00",
     *                       "location": {
     *                           "id": 1,
     *                           "name": "Flexible Drive Victoria",
     *                           "address": "86 Stubbs Street",
     *                           "city": "Kensington",
     *                           "state": "VIC",
     *                           "post_code": "3031",
     *                           "phone": "+61 3 9381 9222",
     *                           "email": "vicsales@flexibledrive.com.au",
     *                           "contact": "James Ferry",
     *                           "mobile": "0419 009 086",
     *                           "contact_email": "jferry@flexibledrive.com.au"
     *                       }
     *                   }
     *               }
     *          ]
     *       }
     *   ],
     *   "first_page_url": "http://flexibledrive.localhost.com/api/orders?page=1",
     *   "from": 1,
     *   "last_page": 1,
     *   "last_page_url": "http://flexibledrive.localhost.com/api/orders?page=1",
     *   "next_page_url": null,
     *   "path": "http://flexibledrive.localhost.com/api/orders",
     *   "per_page": 20,
     *   "prev_page_url": null,
     *   "to": 1,
     *   "total": 1
     *   },
     *   "message": "Your Orders!"
     * }
     *
     */
    public function index(Request $request)
    {
        //
        try {
            $paginate = $request->input('paginate', true);
            $validator = Validator::make($request->all(), [
                "order_number" => "sometimes|required",
                "from_date" => "sometimes|required|date|date_format:Y-m-d",
                "to_date" => "sometimes|required|date|date_format:Y-m-d",
                "page" => "sometimes|required|integer",
                "per_page" => "sometimes|required|integer",
            ]);
            if ($validator->fails()) {
                return $this->sendError('Invalid input', $validator->errors()->all(), 401);
            }

            $orders = $this->ordersrepository->getOrders($request->all(), $paginate, $request->page, $request->per_page);

            return $this->sendResponse($orders, "Your Orders!");
        } catch (\Exception $e) {

            return $this->sendError($e->getMessage(), [], 401);
        }
    }

    /**
     * @group Order
     * Place Order
     * This APi will place order and send email to user and admin with PDF attechment.
     * @bodyParam delivery_method Integer required  delivery methods like 1 - Delivery, 2 - Pickup, 3 - Delivery & Pickup Example:1
     * @bodyParam first_name String required first name of user Example:John
     * @bodyParam last_name String required last name of user Example:Deo
     * @bodyParam mobile String required contact number of user Example:Deo
     * @bodyParam date Date Only required if user seledted pickup method Example:2019-12-12
     * @bodyParam time Time Only required if user seledted pickup method Example:10:00
     * @bodyParam location String Only required if user selected pickup method. pass id of pickup_location ( Table : "pickup_locations" ) Example:1
     * @bodyParam address_line1 String user asddress Only required if user selected delievry method  Example: test address line1
     * @bodyParam address_line2 String user asddress Only required if user selected delievry method  Example:test address line2
     * @bodyParam state String user state. Only required if user selected delievry method  Example:ACT
     * @bodyParam postal_code String user Postal code (ZIP code) Only required if user selected delievry method  Example:1234
     * @bodyParam email String user email address. Only required if user selected delievry method  Example:1234
     * @bodyParam products Interger miltiple product it Array Example:[1,2,3,4]
     * @response
     * {
     *  "success": true,
     *  "data": {
     *      "id": 88,
     *      "user_id": 8,
     *      "order_number": "FD0000088",
     *      "subtotal": 27247,
     *      "gst": 2724.7,
     *      "delivery": 10,
     *      "total": 29981.7,
     *      "status": 0,
     *      "reference_number": null,
     *      "repeat_order": 0,
     *      "delivery_method": "1",
     *      "invoice": "invoice_FD0000088.pdf",
     *      "created_at": "2019-10-22 11:23:07",
     *      "status_label": "Submitted",
     *      "delivery_type": "Delivery"
     *      },
     *    "message": "Thank you for submitting the order. You will shortly receive a confirmation email."
     *  }
     */
    public function store(Request $request)
    {
        $messages = [
            'pickup.time.after' => ':attribute must be futuer time',
            'pickup.time.date_format' => ':attribute must be valid time hh:mm.',
            'pickup.date.after' => ':attribute must be futuer date',
        ];

        $delivery_rules = [];
        $pickup_rules = [];
        $method_rules = [];

        $method_rules = [
            "delivery_method" => "required|in:1,2,3"
        ];
        if ($request->get('delivery_method') == 3 || $request->get('delivery_method') == 1) {
            $delivery_rules = [
                "delivery.first_name" => "required|min:2",
                "delivery.last_name" => "required|min:2",
                "delivery.address_line1" => "required|min:2",
                "delivery.state" => "required|min:2",
                "delivery.zip" => "required|regex:/\b\d{4}\b/",
                "delivery.mobile" => "required|min:10|max:15|regex:/^[0-9+ ]*$/",
                "delivery.email" => "required|email",
                "delivery.products.*" => "required|exists:products,id",
            ];
        }

        if ($request->get('delivery_method') == 3 || $request->get('delivery_method') == 2) {
            $pickup_rules = [
                "pickup.first_name" => "required|min:2",
                "pickup.last_name" => "required|min:2",
                "pickup.mobile" => "required|min:10|max:15|regex:/^[0-9+ ]*$/",
                "pickup.date" => "required|date",
                "pickup.time" => "required|date_format:H:i",
                "pickup.location" => "required",
                "pickup.products.*" => "required|exists:products,id",
            ];
        }
        $validator = Validator::make($request->all(), array_merge($method_rules, $delivery_rules, $pickup_rules), ['delivery.zip.required' => 'Postal Code is Required.', 'delivery.zip.regex' => 'Postal Code format is invalid.']);

        try {

            if ($validator->fails()) {
                return $this->sendError("validation errors", $validator->errors()->all(), 400);
            }

            $cart = Cart::where('user_id', Auth::user()->id)->with(['product'])->get();

            if ($cart->count() > 0) {

                $user = Auth::user();

                $sub_total = 0;
                $delivery_address_id = 0;
                $pickup_address_id = 0;
                $cart_data = [];
                $order = [];
                $order_products = [];
                $order_data = [
                    'user_id' => $user->id,
                    'delivery_method' => $request->delivery_method,
                ];
                $order = Order::create($order_data);
                $order_number = orderNumber($order->id);

                if ($request->delivery_method == 1 || $request->delivery_method == 3) {
                    $delivery_address =  [
                        'user_id' => $user->id,
                        'first_name' => $request['delivery']['first_name'],
                        'last_name' => $request['delivery']['last_name'],
                        'company_name' => ($request['delivery']['company_name']) ? $request['delivery']['company_name'] : '',
                        'address_line1' => $request['delivery']['address_line1'],
                        'address_line2' => ($request['delivery']['address_line2']) ? $request['delivery']['address_line2'] : '',
                        'state' => $request['delivery']['state'],
                        'zip' => $request['delivery']['zip'],
                        'mobile' => $request['delivery']['mobile'],
                        'email' => $request['delivery']['email'],
                    ];

                    $delivery_save = DeliveryAddress::create($delivery_address);
                    $delivery_address_id = $delivery_save->id;
                } else {
                    $delivery_address = [];
                }

                if ($request['pickup']['location'] && $request['pickup']['location'] != 9999 && ($request->delivery_method == 2 || $request->delivery_method == 3)) {
                    $pickup_address = [
                        'user_id' => $user->id,
                        'pickup_location_id' => $request['pickup']['location'],
                        'first_name' => $request['pickup']['first_name'],
                        'last_name' => $request['pickup']['last_name'],
                        'mobile' => $request['pickup']['mobile'],
                        'pickup_date_time' => date('Y-m-d H:i', strtotime($request['pickup']['date'] . $request['pickup']['time'])),
                    ];
                    $pickup_save = PickupAddress::create($pickup_address);
                    $pickup_address_id = $pickup_save->id;
                }

                $product_quntities_to_reduce = [];

                foreach ($cart as $item) {
                    if ($item->product->qty < $item->qty) {
                        $order->status = Config::get('constant.BACK_ORDER_STATUS_ID');
                    }
                    $item_price = $item->product->price_nett * $item->qty;
                    $sub_total += $item_price;

                    $cart_data['products'][] = [
                        'product_nr' => $item->product->product_nr,
                        'name' => $item->product->name,
                        'qty' => $item->qty,
                        'price' => number_format((float)$item->product->price_nett, 2, '.', ''),
                        'item_price' => number_format((float)$item_price, 2, '.', '')
                    ];

                    if (isset($request['delivery']['products']) && is_array($request['delivery']['products']) && in_array($item->product->id, $request['delivery']['products'])) {

                        $order_products = [
                            'product_id' => $item->product->id,
                            'order_id' => $order->id,
                            'qty' => $item->qty,
                            'price' => $item->product->price_nett,
                            'total' => $item_price,
                            'delivery_address_id' => $delivery_address_id,
                            'pickup_address_id' => 0,
                        ];
                    }
                    if (isset($request['pickup']['products']) && is_array($request['pickup']['products']) && in_array($item->product->id, $request['pickup']['products'])) {
                        $order_products = [
                            'product_id' => $item->product->id,
                            'order_id' => $order->id,
                            'qty' => $item->qty,
                            'price' => $item->product->price_nett,
                            'total' => $item_price,
                            'pickup_address_id' => $pickup_address_id,
                            'delivery_address_id' => 0,
                        ];
                    }

                    OrderProduct::create($order_products);
                    $product_quntities_to_reduce[$item->product_id] = $item->qty;
                }

                $gst = $sub_total * $this->GST / 100;
                $cart_data['order_number'] = $order_number;
                $cart_data['gst'] = number_format((float)$gst, 2, '.', '');
                $cart_data['subtotal'] = number_format((float)$sub_total, 2, '.', '');
                $cart_data['delivery'] = number_format((float)$this->DELIVERY_CHARGES, 2, '.', '');
                $cart_data['total'] = number_format((float)$sub_total + $gst + $this->DELIVERY_CHARGES, 2, '.', '');
                $cart_data['created_at'] = $order->created_at;

                $order->subtotal = $sub_total;
                $order->gst = $gst;
                $order->delivery = $this->DELIVERY_CHARGES;
                $order->total = $sub_total + $gst + $this->DELIVERY_CHARGES;
                $order->reference_number = ($request->reference_number) ? $request->reference_number : NULL;
                $order->order_number = $order_number;
                $pdf_data = $this->pdfGenerate($order, $user);
                $order->invoice = $pdf_data['file_name'];
                $order->is_external_pickup = $request['pickup']['location'] == "9999";
                $order->save();

                //Reduce product quantity based on order if status is not back order
                if ($order->status != Config::get('constant.BACK_ORDER_STATUS_ID') && count($product_quntities_to_reduce) > 0) {
                    foreach ($product_quntities_to_reduce as $product_id => $qty) {
                        $product_obj = Product::find($product_id);
                        $product_obj->qty = $product_obj->qty - $qty;
                        $product_obj->save();
                    }
                }

                //send admin emails state wise
                $admin_emails = [];
                if (isset($delivery_address) && isset($delivery_address['state']) && config('constant.ADMINISTRATOR_EMAIL_' . $delivery_address['state'])) {
                    $admin_emails[] = config('constant.ADMINISTRATOR_EMAIL_' . $delivery_address['state']);
                }

                if (isset($pickup_address)) {
                    $location = PickupLocation::find($pickup_address['pickup_location_id']);
                    if ($location && config('constant.ADMINISTRATOR_EMAIL_' . $location->state)) {
                        $admin_emails[] = config('constant.ADMINISTRATOR_EMAIL_' . $location->state);
                    }
                }

                
                // mail to admin
                // $mail_attributes = [
                //     // 'mail_template' => "invoice.invoice_pdf",
                //     'mail_to_email' => (count($admin_emails) > 0) ? $admin_emails : config('app.administrator_email_generic'),
                //     'mail_to_name' => config('app.mail_from_name'),
                //     // 'mail_subject' => "FlexibleDrive : New Order Received!",
                //     'mail_body' => [
                //         'order' => $order,
                //         'is_for_admin' => 1,
                //         'has_exclam'=>true
                //     ],
                //     'mail_attachement' => [
                //         'file_full_path' => $pdf_data['invoice_path'],
                //         'file_name' => $pdf_data['file_name'],
                //         'file_mime' => 'application/pdf',
                //     ],
                // ];
                // Helper::sendEmail($mail_attributes,MailType::NewOrderRecieved);

                // //mail to user
                // // $mail_attributes['mail_template'] = "emails.order_confirmation";
                // $mail_attributes['mail_to_email'] =  $order->user->email;
                // // $mail_attributes['mail_subject'] =  'FlexibleDrive : Your Order!';
                // $mail_attributes['mail_body']['is_for_admin'] = 0;
                // Helper::sendEmail($mail_attributes,MailType::OrderConfirmation);

                Cart::where('user_id', Auth::user()->id)->delete();
                $order_detail = Order::find($order->id);
                return $this->sendResponse($order_detail, "Thank you for submitting the order. You will shortly receive a confirmation email.");
            }
            return $this->sendResponse([], "Cart is empty");
        } catch (\Exception $e) {
            dd($e->getTraceAsString());
            return $this->sendError($e->getMessage(), [], 401);
        }
    }

    /**
     * @group Order
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * @group Order
     * Reference Number
     * add refrence number to order.
     * @bodyParam order_number String required order number  ( Table : "orders" )  Example:FD000001
     * @bodyParam reference_number String user will add his refrence number  Example:FD000001
     * @response
     * {
     *   "success": true,
     *   "data": {
     *          "id": 87,
     *          "user_id": 8,
     *          "order_number": "FD0000087",
     *          "subtotal": 6,
     *          "gst": 0.6,
     *          "delivery": 10,
     *          "total": 16.6,
     *          "status": 0,
     *          "reference_number": "zssdasdasd",
     *          "repeat_order": 0,
     *          "delivery_method": "1",
     *          "invoice": "invoice_FD0000087.pdf",
     *          "created_at": "2019-10-21 13:13:21",
     *          "status_label": "Submitted",
     *          "delivery_type": "Delivery"
     *      },
     *      "message": "Reference number updated to order!"
     *   }
     *
     */
    public function update(Request $request, $id)
    {
        //
        try {
            $request->merge(['order' => $id]);
            $validator = Validator::make($request->all(), [
                "order" => "required|exists:orders,id",
                //"reference_number" => "sometimes|required|min:2",
            ]);

            if ($validator->fails()) {
                return $this->sendError("validation errors", $validator->errors()->all(), 400);
            }

            $order = Order::where('id', $request->id)->where('user_id', Auth::user()->id)->first();

            $delivery_address = DeliveryAddress::where('user_id', Auth::user()->id)->first();
            $pickup_address = PickupAddress::where('user_id', Auth::user()->id)->first();


            $order->reference_number = $request->reference_number;
            $order->save();

            $order = Order::where('id', $request->id)->where('user_id', Auth::user()->id)->first();
            $user = Auth::user();
            $pdf_data = $this->pdfGenerate($order, $user);

            $order->invoice = $pdf_data['file_name'];
            $order->save();

            // get state from user for admin emails
            $user_state = $user->state;

            //send admin emails state wise
            $admin_emails = [];
            // if (isset($delivery_address) && isset($delivery_address->state) && config('constant.ADMINISTRATOR_EMAIL_'.$delivery_address->state)) {
            // 	$admin_emails[] = config('constant.ADMINISTRATOR_EMAIL_'.$delivery_address->state);
            // }

            // if (isset($pickup_address)) {
            // 	$location = PickupLocation::find($pickup_address->pickup_location_id);
            // 	if($location && config('constant.ADMINISTRATOR_EMAIL_' . $location->state)){
            // 		$admin_emails[] = config('constant.ADMINISTRATOR_EMAIL_' . $location->state);
            // 	}
            // }

            if (isset($user_state)) {
                if (config('constant.ADMINISTRATOR_EMAIL_' . $user_state)) {
                    $admin_emails[] = config('constant.ADMINISTRATOR_EMAIL_' . $user_state);
                }
            }

            // mail to admin
            $mail_attributes = [
                // 'mail_template' => "invoice.invoice_pdf",
                'mail_to_email' => (count($admin_emails) > 0) ? $admin_emails : config('app.administrator_email_generic'),
                'mail_to_name' => config('app.mail_from_name'),
                // 'mail_subject' => "FlexibleDrive : New Order Received!",
                'mail_body' => [
                    'order' => $order,
                    'is_for_admin' => 1,
                    'has_exclam'=>false
                ],
                'mail_attachement' => [
                    'file_full_path' => $pdf_data['invoice_path'],
                    'file_name' => $pdf_data['file_name'],
                    'file_mime' => 'application/pdf',
                ],
            ];
            Helper::sendEmail($mail_attributes,MailType::NewOrderRecieved);

            //mail to user
            // $mail_attributes['mail_template'] = "emails.order_confirmation";
            $mail_attributes['mail_to_email'] =  $order->user->email;
            // $mail_attributes['mail_subject'] =  'Flexible Drive : Your Order';
            $mail_attributes['mail_body']['is_for_admin'] = 0;
            Helper::sendEmail($mail_attributes,MailType::OrderConfirmation);

            return $this->sendResponse($order, "Reference number updated to order!");
        } catch (\Exception $e) {

            return $this->sendError($e->getMessage(), [], 401);
        }
    }

    /**
     * @group Order
     * Delete Order
     * @queryParam order_id required Interger (Tabale: orders) Example:1
     * @response
     * {
     *    "success": true,
     *    "data": {
     *        "id": 67,
     *        "user_id": 8,
     *        "order_number": "FD0000067",
     *        "subtotal": 20737,
     *        "gst": 2073.7,
     *        "delivery": 10,
     *        "total": 22820.7,
     *        "status": 0,
     *        "reference_number": null,
     *        "repeat_order": 0,
     *        "delivery_method": "2",
     *        "invoice": "invoice_FD0000067.pdf",
     *        "created_at": "2019-10-18 12:16:59",
     *        "status_label": "Submitted",
     *        "delivery_type": "Pick Up",
     *        "items": [
     *            {
     *               "id": 63,
     *               "product_id": 4864,
     *               "order_id": 67,
     *               "qty": 1,
     *               "price": 4864,
     *               "total": 4864,
     *               "delivery_address_id": 0,
     *               "pickup_address_id": 14,
     *               "order_status": null,
     *               "product": {
     *                   "id": 4864,
     *                   "brand_id": 153,
     *                   "product_nr": "62036.10",
     *                   "name": "Brake Disc Rotor",
     *                   "description": "Brake Disc Rotor",
     *                   "cross_reference_numbers": "FD1394,GDB1318",
     *                   "associated_part_numbers": "001002",
     *                   "price_nett": 4864,
     *                   "price_retail": 0,
     *                   "fitting_position": "Rear Axle",
     *                   "length": null,
     *                   "height": null,
     *                   "thickness": null,
     *                   "weight": null,
     *                   "standard_description_id": "82"
     *               }
     *           }
     *        ]
     *    },
     *    "message": "Order Deleted!"
     * }
     *
     */
    public function destroy($order_id)
    {
        try {
            $is_delete = 0;
            $validator = Validator::make(['order_id' => $order_id], [
                "order_id" => "required|exists:orders,id",
            ]);
            if ($validator->fails()) {
                return $this->sendError("validation errors", $validator->errors()->all(), 400);
            }
            $order = Order::where('id', $order_id)->whereIn('status', ['0', '4'])->where('user_id', Auth::user()->id)->first();
            if (isset($order->id)) {

                $user = User::find($order->user_id);

                $mail_attributes = [
                    // 'mail_template' => "emails.order_cancel",
                    'mail_to_email' => config('app.administrator_email'),
                    'mail_to_name' => config('app.mail_from_name'),
                    // 'mail_subject' => "FlexibleDrive : Order Deleted - " . $order->order_number,
                    'mail_body' => [
                        'order' => $order,
                        'order_id'=>$order->order_number,
                        'action' => 'Deleted',
                    ]
                ];
                // mail to admin
                Helper::sendEmail($mail_attributes,MailType::OrderCancelation);

                $is_delete = $order->delete();
                $order->favourite->delete();
            }

            if ($is_delete) {
                return $this->sendResponse($order, "Order Deleted!");
            } else {
                return $this->sendError("cannot delete this order.", []);
            }
        } catch (\Exception $e) {

            return $this->sendError($e->getMessage(), [], 401);
        }
    }
    /**
     * @group Order
     * Bulk Order Delete
     * @bodyParam orders required order id Array. array elements Interger (Tabale: orders) Example:[1,2]
     * @response
     * {
     *  "success": true,
     *   "data": [],
     *  "message": "Order Deleted!"
     * }
     *
     */
    public function bulkDestroy(Request $request)
    {
        try {
            $orders = Order::where('user_id', Auth::user()->id)->pluck('id')->toArray();
            $exists = implode(',', $orders);
            $is_delete = [];
            $validator = Validator::make($request->all(), [
                "orders.*" => "required|in:" . $exists,
            ]);
            if ($validator->fails()) {
                return $this->sendError("validation errors", $validator->errors()->all(), 400);
            }
            $orders =  Order::whereIn('id', $request->orders)->whereIn('status', ['0', '4'])->get();

            $order_numbers = implode(',', $orders->pluck('order_number')->toArray());

            $orders_delete = Order::whereIn('id', $request->orders)->whereIn('status', ['0', '4'])->delete();
            FavouriteOrders::whereIn('order_id', $request->orders)->delete();
            $mail_attributes = [
                // 'mail_template' => "emails.order_cancel",
                'mail_to_email' => config('app.administrator_email'),
                'mail_to_name' => config('app.mail_from_name'),
                // 'mail_subject' => "FlexibleDrive : Order Deleted - " . $order_numbers,
                'mail_body' => [
                    'order_id' => $order_numbers,
                    'order'=>$orders,
                    'action' => 'Deleted',
                ]
            ];
            // mail to admin
            Helper::sendEmail($mail_attributes,MailType::OrderCancelation);

            if ($orders_delete > 0) {
                return $this->sendResponse([], "Order Deleted!");
            } else {
                return $this->sendError("orders having status Processing, Delivering or Completed cannot be deleted.", []);
            }
        } catch (\Exception $e) {

            return $this->sendError($e->getMessage(), [], 401);
        }
    }

    private function pdfGenerate($order_data, $user)
    {

        // return View::make('invoice.invoice_pdf', ['cart' => $cart, 'user' => $user]);

        $pdf = Pdf::loadView('invoice.invoice_pdf', ['order' => $order_data, 'user' => $user]);

        $content = $pdf->download()->getOriginalContent();

        $file_name = "invoice_" . $order_data['order_number'] . '.pdf';

        $store_path = $this->INVOICES_PATH . $file_name;

        $invoice_save = Storage::disk('public')->put($store_path, $content);

        $invoice_path = Storage::disk('public')->url($store_path);

        $pdf_data = [
            'file_name' => $file_name,
            'invoice_path' => $invoice_path,
        ];

        return $pdf_data;
    }

    /**
     * @group Order
     * Export order
     * @queryParam orders required (Array) Order ID , items (Integer)  ( Table : "orders" )
     * @response
     * {
     *       "success": true,
     *       "data": {
     *           "download_url": "http://flexibledrive.localhost.com/storage/invoices/1571748692.pdf"
     *      },
     *      "message": "Invoices Link"
     * }
     */
    public function export(Request $request)
    {
        try {
            $orders = Order::where('user_id', Auth::user()->id)->pluck('id')->toArray();
            $exists = implode(',', $orders);
            $validator = Validator::make($request->all(), [
                'orders' => 'required',
                'orders.*' => 'required|in:' . $exists,
            ], ['orders.*.required' => 'The orders field is required.']);
            if ($validator->fails()) {
                return $this->sendError("validation errors", $validator->errors()->all(), 400);
            }

            $orders = Order::whereIn('id', $request->orders)->with(['user', 'items', 'items.product'])->get();

            foreach ($orders as $order) {
                $order_data = [];
                if (isset($order->items) && $order->items->count() > 0) {

                    foreach ($order->items as $item) {

                        $order_data['products'][] = [
                            'product_nr' => $item->product->product_nr,
                            'name' => $item->product->name,
                            'qty' => $item->qty,
                            'price' => number_format((float) $item->price, 2, '.', ''),
                            'item_price' => number_format((float) $item->total, 2, '.', '')
                        ];
                    }
                    $order_data['order_number'] = $order->order_number;
                    $order_data['gst'] = number_format((float) $order->gst, 2, '.', '');
                    $order_data['subtotal'] = number_format((float) $order->sub_total, 2, '.', '');
                    $order_data['delivery'] = number_format((float) $order->delivery, 2, '.', '');
                    $order_data['total'] = number_format((float)  $order->total, 2, '.', '');
                    $order_data['created_at'] = $order->created_at;
                    $user = $order->user;
                    if (!isset($order->invoice) || empty($order->invoice) || !Storage::disk('public')->exists($this->INVOICES_PATH . $order->invoice)) {
                        $pdf_data = $this->pdfGenerate($order, $user);
                        Order::where('id', $order->id)->update(['invoice' => $pdf_data['file_name']]);
                        $pdf_file = Storage::disk('public')->path($this->INVOICES_PATH . $pdf_data['file_name']);
                    }
                }
            }
            if ($orders->count() == 1 && isset($order->invoice) && !empty($order->invoice) && Storage::disk('public')->exists($this->INVOICES_PATH . $order->invoice)) {

                $store_path =  $this->INVOICES_PATH . $order->invoice;
                $message = "Invoice Link";
            } else {

                $pdf = Pdf::loadView('invoice.invoice_pdf', ['orders' => $orders]);
                $content = $pdf->download()->getOriginalContent();

                $file_name = $user->first_name . "_" . $user->last_name . "_invoices_" . strtotime(date('Y-m-d H:i:s')) . ".pdf";

                $store_path = $this->INVOICES_PATH . $file_name;

                $invoice_save = Storage::disk('public')->put($store_path, $content);
                $message = "Invoices Link";
            }
            $invoice_path = Storage::disk('public')->url($store_path);

            return $this->sendResponse(['download_url' => $invoice_path], $message);
        } catch (\Exception $e) {

            return $this->sendError($e->getMessage(), [], 401);
        }
    }
    /**
     * @group Order
     * Cancel Order
     * @queryParam order_id required Interger (Tabale: orders) Example:1
     * @response
     * {
     *       "success": true,
     *       "data": {
     *           "id": 59,
     *           "user_id": 8,
     *           "order_number": "FD0000059",
     *           "subtotal": 14,
     *           "gst": 1.4,
     *           "delivery": 10,
     *           "total": 25.4,
     *           "status": 4,
     *           "reference_number": null,
     *           "repeat_order": 0,
     *           "delivery_method": "1",
     *           "invoice": "invoice_FD0000059.pdf",
     *           "created_at": "2019-10-17 15:05:39",
     *           "status_label": "Cancelled",
     *           "delivery_type": "Delivery",
     *           "items": [
     *               {
     *                   "id": 42,
     *                   "product_id": 2,
     *                   "order_id": 59,
     *                   "qty": 2,
     *                   "price": 2,
     *                   "total": 4,
     *                   "delivery_address_id": 59,
     *                   "pickup_address_id": 0,
     *                   "order_status": null,
     *                   "product": {
     *                       "id": 2,
     *                       "brand_id": 153,
     *                       "product_nr": "0002.00",
     *                       "name": "Brake Pad Set",
     *                       "description": "Brake Pad Set",
     *                       "cross_reference_numbers": "FD1394,GDB1318",
     *                       "associated_part_numbers": "001002",
     *                       "price_nett": 2,
     *                       "price_retail": 0,
     *                       "fitting_position": "Front Axle",
     *                       "length": null,
     *                       "height": null,
     *                       "thickness": null,
     *                       "weight": null,
     *                       "standard_description_id": "402"
     *                   }
     *               },
     *               {
     *                   "id": 43,
     *                   "product_id": 5,
     *                   "order_id": 59,
     *                   "qty": 2,
     *                   "price": 5,
     *                   "total": 10,
     *                   "delivery_address_id": 0,
     *                   "pickup_address_id": 0,
     *                   "order_status": null,
     *                   "product": {
     *                       "id": 5,
     *                       "brand_id": 153,
     *                       "product_nr": "0002.30",
     *                       "name": "Brake Pad Set",
     *                       "description": "Brake Pad Set",
     *                       "cross_reference_numbers": "FD1394,GDB1318",
     *                       "associated_part_numbers": "001002",
     *                       "price_nett": 5,
     *                       "price_retail": 0,
     *                       "fitting_position": "Front Axle",
     *                       "length": null,
     *                       "height": null,
     *                       "thickness": null,
     *                       "weight": null,
     *                       "standard_description_id": "402"
     *                   }
     *               }
     *           ]
     *       },
     *       "message": "Order Cancelled!"
     *   }
     *
     */
    public function cancel($order_id)
    {
        try {
            $is_cancel = 0;
            $validator = Validator::make(['order_id' => $order_id], [
                "order_id" => "required|exists:orders,id",
            ]);
            if ($validator->fails()) {
                return $this->sendError("validation errors", $validator->errors()->all(), 400);
            }
            $order = Order::where('id', $order_id)->where('user_id', Auth::user()->id)->with(['items', 'items.product'])->first();

            if (isset($order->id)) {
                $user = User::find($order->user_id);

                // if (isset($order->invoice) && !empty($order->invoice)) {
                //     $store_path = $this->INVOICES_PATH . $order->invoice;
                //     Storage::disk('public')->delete($store_path);
                // }
                $order->status = 4;
                $is_cancel = $order->save();

                $mail_attributes = [
                    // 'mail_template' => "emails.order_cancel",
                    'mail_to_email' => config('app.administrator_email'),
                    'mail_to_name' => config('app.mail_from_name'),
                    // 'mail_subject' => "FlexibleDrive : Order Cancelled - " . $order->order_number,
                    'mail_body' => [
                        'order' => $order,
                        'action' => 'Cancelled',
                        'order_id'=>$order->order_number
                    ]
                ];
                // mail to admin
                Helper::sendEmail($mail_attributes,MailType::OrderCancelation);
            }
            if ($is_cancel > 0) {
                return $this->sendResponse($order, "Order Cancelled!");
            } else {
                return $this->sendError("You are not Authorised to cancel this order.");
            }
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), [], 401);
        }
    }
}
