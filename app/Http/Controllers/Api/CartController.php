<?php

namespace App\Http\Controllers\Api;

use DB;
use PDF;
use Auth;
use Mail;
use Config;
use Storage;
use App\User;
use App\Cart;
use App\ProductQuantity;
use App\Branch;
use Validator;
use App\PickupLocation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Session;
use Symfony\Component\Console\Input\Input;


class CartController extends BaseController
{

    public function __construct()
    {
        
        $this->INVOICES_PATH = Config::get('constant.INVOICES_PATH');
        $this->GST = Config::get('constant.invoice.gst');
        $this->DELIVERY_CHARGES = Config::get('constant.invoice.delivery_charges');
    }

    /**
     * @group Cart
     * All Cart Items
     * Get all items added to cart.
     * @response {
     *  "success": true,
     *  "data": {
     *      "items": [
     *          {
     *              "id": 130,
     *              "product_id": 20,
     *             "user_id": 11,
     *             "qty": 3,
     *            "product": {
     *                "id": 20,
     *                "brand_id": 153,
     *                "product_nr": "0006.91",
     *                "name": "Brake Pad Set",
     *                 "description": "Brake Pad Set",
     *                 "cross_reference_numbers": "FD1394,GDB1318",
     *                 "associated_part_numbers": "001002",
     *                "price_nett": 15,
     *                 "price_retail": 20,
     *                "fitting_position": "Front Axle",
     *                 "length": null,
     *                 "height": null,
     *                "thickness": null,
     *                "weight": null,
     *                 "standard_description_id": "402",
     *                 "brand": {
     *                     "id": 153,
     *                     "name": "REMSA",
     *                    "logo": "https://imageapi.partsdb.com.au/api/Image?urlId=%2F0E9mNHbJEo6NhpoCE61T2KGglsoQwHJUqdd%2FHSOxgGXF6BNaAZa4FrzNpTPVO4X"
     *                 },
     *                 "vehicles": [],
     *                 "images": [
     *                     {
     *                         "id": 16,
     *                         "product_id": 20,
     *                         "image": "0006-91-MODEL.BMP",
     *                         "image_url": "http://35.164.124.177/fd-backend/public/storage/products/0006-91-MODEL.BMP"
     *                    }
     *                 ],
     *                 "categories": []
     *             }
     *         }
     *     ],
     *     "delivery_charges": 10,
     *     "GST": 4.5,
     *     "subtotal": 45,
     *     "total": 59.5,
     *     "pickup_locations": [
     *         {
     *             "id": 1,
     *             "name": "Flexible Drive Victoria",
     *             "address": "86 Stubbs Street",
     *             "city": "Kensington",
     *             "state": "VIC",
     *            "post_code": "3031",
     *             "phone": "+61 3 9381 9222",
     *            "email": "vicsales@flexibledrive.com.au",
     *            "contact": "James Ferry",
     *            "mobile": "0419 009 086",
     *             "contact_email": "jferry@flexibledrive.com.au"
     *         }
     *     ]
     *   },
     *  "message": "cart items"
     *  }
     */
    public function index()
    {
        try {
            $sub_total = 0;
            $gst = 0;
            $delivry_chage =  0;
            $total = 0;
            $products['items'] = Cart::where('user_id',Auth::user()->id)->with([
                                                                        'product',
                                                                        'product.brand', 
                                                                        'product.vehicles',
                                                                        'product.vehicles.make',
                                                                        'product.vehicles.model',
                                                                        'product.images',
                                                                        'product.categories'
                                                                    ])->get();
            if($products['items']->count() > 0) {

                $sub_total = 0;
                $cart_item_branch_qty = array();
                foreach ($products['items'] as $cart_item) {
                    $branchWiseQty = ProductQuantity::where('product_id', $cart_item -> product_id)->get();
                    $branchWiseQtyForPush = array();
                    foreach($branchWiseQty as &$branchQty){
                        $branch = Branch::where('id', $branchQty->branch_id)->first();
                        $branchQty['branch'] = $branch;
                        array_push($branchWiseQtyForPush, $branchQty);
                    }
                    $cart_item['qty_with_location'] = $branchWiseQtyForPush;
                    array_push($cart_item_branch_qty, $cart_item);
                   $sub_total += $cart_item->product->price_nett * $cart_item->qty;
                }

                $gst = $sub_total * $this->GST / 100;
                $delivry_chage =  $this->DELIVERY_CHARGES;
                
                $total = $sub_total + $gst + $delivry_chage;
                $products['items'] = $cart_item_branch_qty;
            }
            $products['pickup_locations'] = PickupLocation::all();

            $products['GST'] = number_format($gst,2);
            $products['subtotal'] = number_format($sub_total,2);
            $products['delivery'] = number_format($delivry_chage,2);
            $products['total'] = number_format($total,2);
            return $this->sendResponse($products, 'cart items');

        } catch(\Exception $e) {

            return $this->sendError($e->getMessage(), [], 401);
        }
    }

    /**
     * @group Cart
     * Add to Cart
     * This API will add producrs into the cart.
     * @bodyParam products Integer required  Product ID ( Table : "products" ) Example:1
     * @bodyParam qty Integer required Product Quantitiy Example:2
     *  
     */
    public function store(Request  $request)
    {
        $validator = Validator::make(['id' => $request->product_id, 'qty' => $request->qty],[
            "id" => "required|exists:products",
            "qty" => "required|numeric|min:1"
        ]);

        try {  
            
            if($validator->fails()) {
                return $this->sendError("validation errors", $validator->errors()->all(), 400);    
            }
            
            $cart = [
                'product_id' => $request->product_id,
                'user_id' => Auth::user()->id,
            ];

            $user_cart = Cart::firstorcreate($cart);
            $user_cart->qty = $request->qty;
            $user_cart->save();

            $products = Cart::where('user_id',Auth::user()->id)->with([
                                                                        'product',
                                                                        'product.brand', 
                                                                        'product.vehicles',
                                                                        'product.vehicles.make',
                                                                        'product.vehicles.model',
                                                                        'product.images',
                                                                        'product.categories'
                                                                    ])->get()->toArray();
            
            return $this->sendResponse(['cart_items' => $products], "Added to Cart");

        } catch(\Exception $e) {

            return $this->sendError($e->getMessage(), [], 401);
        }
    }


    /**
     * @group Cart
     * Bulk Product Add to Cart
     * This API will add bulk products into the cart.
     * @bodyParam products Integer required  Product ID ( Table : "products" ) Example:1
     * @bodyParam qty Integer required Product Quantitiy Example:2
     *  
     */
    public function bulkStore(Request  $request)
    {
        $validator = Validator::make($request->all(),[
            "products" => "required|array",
            "products.*.product_id" => "required|integer|exists:products,id",
            "products.*.qty" => "required|numeric"
        ]);

        try {  
            
            if($validator->fails()) {
                return $this->sendError("validation errors", $validator->errors()->all(), 400);    
            }

            foreach ($request->products as $product) {

                $cart = [
                    'product_id' => $product['product_id'],
                    'user_id' => Auth::user()->id,
                ];

                $user_cart = Cart::firstorcreate($cart);
                $user_cart->qty = $product['qty'];
                $user_cart->save();
            }

            $products = Cart::where('user_id',Auth::user()->id)->with([
                                                                        'product',
                                                                        'product.brand', 
                                                                        'product.vehicles',
                                                                        'product.vehicles.make',
                                                                        'product.vehicles.model',
                                                                        'product.images',
                                                                        'product.categories'
                                                                    ])->get()->toArray();
            
            return $this->sendResponse(['cart_items' => $products], "Added to Cart");

        } catch(\Exception $e) {

            return $this->sendError($e->getMessage(), [], 401);
        }
    }
    /**
     * @group Cart
     * Remove item from cart
     * This API will remove items from cart. Product id need to be passed Ie. (/cart/11/remove)
     */
    public function destroy($product)
    {
    
        try {  
            $remove_item = Cart::where('product_id',$product)
                                ->where('user_id',Auth::user()->id)
                                ->delete();
            
            $message = "Removed from cart";
            if(!$remove_item){
                $message = 'No items in cart for this product';
            }

            $products = Cart::where('user_id',Auth::user()->id)->with([
                                'product'
                            ])->get()->toArray();
            
            return $this->sendResponse(['cart_items' => $products], $message);

        } catch(\Exception $e) {

            return $this->sendError($e->getMessage(), [], 401);
        }
    }
}
