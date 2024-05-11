<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Models\DiscountCoupon;
use App\Models\ShippingCharge;
use Illuminate\Support\Carbon;
use App\Models\CustomerAddress;
use Illuminate\Support\Facades\Auth;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function addToCart(Request $request) {
       
    //    $product = Product::find($request->id);
    $product = Product::select('products.*','transaksis.nama_resep as titleName')
                            ->latest('products.id')
                            ->leftjoin('transaksis','transaksis.id','products.transaksi_id')
                            ->find($request->id);

        
        if ($product == null) {
            return response()->json([
                'status' => false,
                'message' => 'Product Not Found'
            ]);
        }
    
        if (Cart::count() > 0) {
            // echo "Product already in cart";

            $cartContent = Cart::content();
            $productAlreadyExist = false;

            foreach ($cartContent as $item) {
                if ($item->id == $product->id) {
                    $productAlreadyExist = true;
                }

                if ($productAlreadyExist == false) {
                    Cart::add($product->id, $product->titleName, 1, $product->price, [(!empty($product->image)) ? $product->image : '']);

                    $status = true;
                    $message = '<strong>'.$product->titleName.'</strong> Added in your cart successfully';
                    session()->flash('success',$message);


                } else {
                    $status = false;
                    $message = $product->titleName.' Already added in cart';
                }
            }
            
        } else {
            Cart::add($product->id, $product->titleName, 1, $product->price, [(!empty($product->image)) ? $product->image : '']);
            $status = true;
            $message = $product->titleName.' Added in your cart successfully';
            session()->flash('success',$message);
        }
    
        return response()->json([
            'status' => $status,
            'message' => $message
        ]);
    }
    
    public function cart() {
        $cartContent = Cart::content();
        //dd($cartContent);
        $data['cartContent'] = $cartContent;
        return view('front.cart',$data);
    }

    public function updateCart(Request $request) {
        $rowId = $request->rowId;
        $qty = $request->qty;

        $itemInfo = Cart::get($rowId);

        $product = Product::find($itemInfo->id);

        if ($product->track_qty == 'Yes') {
            if ($qty <= $product->qty) {
                Cart::update($rowId, $qty);
                $message = 'Cart Update Successfully';
                $status = true;
                session()->flash('success',$message);


            } else {
                $message = 'Requested qty('.$qty.') not available in stock';
                $status = false;
                session()->flash('error',$message);


            }
        } else {
            Cart::update($rowId, $qty);
            $message = 'Cart Update Successfully';
            $status = true;
            session()->flash('success',$message);

        }


        return response()->json([
            'status' => $status,
            'message' => $message
        ]);
    }

    public function deleteItem(Request $request) {
        $itemInfo = Cart::get($request->rowId);
        if ($itemInfo == null) {
            $errorMessage = 'Item Not Found in Cart';
            session()->flash('error', $errorMessage);
            return response()->json([
                'status' => false,
                'message' => $errorMessage
            ]);
        }

        Cart::remove($request->rowId);

        $message = 'Item Remove From Cart Successfully';
        session()->flash('success', $message);
        return response()->json([
            'status' => true,
            'message' => $message
        ]);
        
    }

    public function checkout() {

        $discount = 0;

        if (Cart::count() == 0) {
            return redirect()->route('front.cart');
        }

        if (Auth::check() == false) {

            if (!session()->has('url.intended')) {
                session(['url.intended' => url()->current()]);
            }

            
            return redirect()->route('account.login');
        }

        $customerAddress = CustomerAddress::where('user_id',Auth::user()->id)->first();

        session()->forget('url.intended');

        $cities = City::orderBy('name','ASC')->get();

        $subTotal = Cart::subtotal(2,'.','');

        //apply discount
        if (session()->has('code')) {
            $code = session()->get('code');
            if ($code->type == 'percent') {
                $discount = ($code->discount_amount/100)*$subTotal;
            } else {
                $discount = $code->discount_amount;
            }
        }

        //Calculate Shipping
        if ($customerAddress != '') {
       
            $userCity = $customerAddress->city_id;
            $shippingInfo = ShippingCharge::where('city_id',$userCity)->first();
            
            $totalQty = 0;
            $totalShippingCharge = 0;
            $grandTotal = 0;
            foreach (Cart::content() as $item) {
                $totalQty += $item->qty;
            }

            $totalShippingCharge = $totalQty*$shippingInfo->amount;
            $grandTotal = ($subTotal-$discount)+$totalShippingCharge;            
        
        } else {
            $grandTotal = ($subTotal-$discount); 
            $totalShippingCharge = 0;
           

        }


        return view('front.checkout',[
            'cities' => $cities,
            'customerAddress' => $customerAddress,
            'totalShippingCharge' => $totalShippingCharge,
            'discount' => $discount,
            'grandTotal' => $grandTotal
        ]);
    }

    public function processCheckout(Request $request) {

        //step1 apply validation

        $validator = Validator::make($request->all(),[
            'first_name' => 'required|min:5',
            'last_name' => 'required',
            'email' => 'required|email',
            'city' => 'required',
            'address' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'mobile' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Please Fix the Errors',
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        //step2 save user address

        $user = Auth::user();

        CustomerAddress::updateOrCreate(
            ['user_id' => $user->id],
            [
                'user_id' => $user->id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'city_id' => $request->city,
                'address' => $request->address,
                'apartment' => $request->apartment,
                'state' => $request->state,
                'zip' => $request->zip,
            ]
        );

        //step3 store data in orders table

        if ($request->payment_method == 'cod') {

            $discountCodeId = NULL;
            $promoCode = '';
            $shipping = 0;
            $discount = 0;
            $subTotal = Cart::subtotal(2,'.','');
             //apply discount
             if (session()->has('code')) {
                $code = session()->get('code');
                if ($code->type == 'percent') {
                    $discount = ($code->discount_amount/100)*$subTotal;
                } else {
                    $discount = $code->discount_amount;
                }

                $discountCodeId = $code->id;
                $promoCode = $code->code;
            }

            //Calculate Shipping
            $shippingInfo = ShippingCharge::where('city_id',$request->city)->first();

            $totalQty = 0;
            foreach (Cart::content() as $item) {
                $totalQty += $item->qty;
            }

            if ($shippingInfo != null) {
                $shipping = $totalQty*$shippingInfo->amount;
                $grandTotal = ($subTotal-$discount)+$shipping;

            } else {
                $shippingInfo = ShippingCharge::where('city_id','rest_of_city')->first();
                $shipping = $totalQty*$shippingInfo->amount;
                $grandTotal = ($subTotal-$discount)+$shipping;

            }

           

            $order = new Order;
            $order->subtotal = $subTotal;
            $order->shipping = $shipping;
            $order->grand_total = $grandTotal;
            $order->discount = $discount;
            $order->coupon_code_id = $discountCodeId;
            $order->coupon_code = $promoCode;
            $order->payment_status = 'not paid';
            $order->status = 'pending';
            $order->user_id = $user->id;
            $order->first_name = $request->first_name;
            $order->last_name = $request->last_name;
            $order->email = $request->email;
            $order->mobile = $request->mobile;
            $order->address = $request->address;
            $order->apartment = $request->apartment;
            $order->state = $request->state;
            $order->zip = $request->zip;
            $order->notes = $request->order_notes;
            $order->city_id = $request->city;
            $order->save();


            //step4 store order items in order items table
            foreach (Cart::content() as $item) {
                $orderItem = new OrderItem;
                $orderItem->product_id = $item->id;
                $orderItem->order_id = $order->id;
                $orderItem->name = $item->name;
                $orderItem->qty = $item->qty;
                $orderItem->price = $item->price;
                $orderItem->total = $item->price*$item->qty;
                $orderItem->save();

                //update product stock
                $productData = Product::find($item->id);
                if ($productData->track_qty == 'Yes') {
                    $currentQty = $productData->qty;
                    $updatedQty = $currentQty-$item->qty;
                    $productData->qty = $updatedQty;
                    $productData->save();
                }
                
            }

            //send order email
            orderEmail($order->id,'customer');

            session()->flash('success','You Have Successfully Placed Your Order');

            Cart::destroy();

            session()->forget('code');

            return response()->json([
                'message' => 'Order Saved Successfully',
                'orderId' => $order->id,
                'status' => true
            ]);

            

        } else {
            # code...
        }

    }

    public function thankyou($id) {
        return view('front.thanks',[
            'id' => $id
        ]);
    }

    public function getOrderSummery(Request $request) {
        $subTotal = Cart::subtotal(2,'.','');
        $discount = 0;
        $discountString= '';

        //apply discount
        if (session()->has('code')) {
            $code = session()->get('code');
            if ($code->type == 'percent') {
                $discount = ($code->discount_amount/100)*$subTotal;
            } else {
                $discount = $code->discount_amount;
            }

            $discountString = '<div class="mt-4" id="discount-response">
                <strong>'.session()->get('code')->code.'</strong>
                <a class="btn btn-sm btn-danger" id="remove-discount"><i class="fa fa-times"></i></a>
            </div>';
        }

        


        if ($request->city_id > 0) {

            $shippingInfo = ShippingCharge::where('city_id',$request->city_id)->first();

            $totalQty = 0;
            foreach (Cart::content() as $item) {
                $totalQty += $item->qty;
            }

            if ($shippingInfo != null) {

                $shippingCharge = $totalQty*$shippingInfo->amount;
                $grandTotal = ($subTotal-$discount)+$shippingCharge;

                return response()->json([
                    'status' => true,
                    'grandTotal' => number_format($grandTotal),
                    'discount' => number_format($discount),
                    'discountString' => $discountString,
                    'shippingCharge' => number_format($shippingCharge),
                ]);
            } else {
                $shippingInfo = ShippingCharge::where('city_id','rest_of_city')->first();

                $shippingCharge = $totalQty*$shippingInfo->amount;
                $grandTotal = ($subTotal-$discount)+$shippingCharge;

                return response()->json([
                    'status' => true,
                    'grandTotal' => number_format($grandTotal),
                    'discount' => number_format($discount),
                    'discountString' => $discountString,
                    'shippingCharge' => number_format($shippingCharge),
                ]);
            }

        } else {
            return response()->json([
                'status' => true,
                'grandTotal' => number_format($subTotal-$discount),
                'discount' => number_format($discount),
                'discountString' => $discountString,
                'shippingCharge' => number_format(0),
            ]);
        }
    }

    public function applyDiscount(Request $request){
        // dd($request->code);

        $code = DiscountCoupon::where('code',$request->code)->first();

        if ($code == null) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid Discount Coupon',
            ]);
        }

        //Check if coupon start date is valid or not

        $now = Carbon::now();

        //echo $now->format('Y-m-d H:i:s');

        if ($code->starts_at != "") {
            $startDate = Carbon::createFromFormat('Y-m-d H:i:s',$code->starts_at);

            if ($now->lt($startDate)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid Discount Coupon',
                ]);
            }
        }

        if ($code->expires_at != "") {
            $endDate = Carbon::createFromFormat('Y-m-d H:i:s',$code->expires_at);

            if ($now->gt($endDate)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid Discount Coupon',
                ]);
            }
        }

        //max  uses check
        if ($code->max_uses > 0) {
            $couponUsed = Order::where('coupon_code_id',$code->id)->count();

            if ($couponUsed >= $code->max_uses) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid Discount Coupon',
                ]);
            }
        }
        

        //max uses user check
        if ($code->max_uses_user > 0) {
            $couponUsedByUser = Order::where(['coupon_code_id'=> $code->id, 'user_id' => Auth::user()->id])->count();

            if ($couponUsedByUser >= $code->max_uses_user) {
                return response()->json([
                    'status' => false,
                    'message' => 'You Already used this coupon',
                ]);
            }
        }
        
        $subTotal = Cart::subtotal(2,'.','');

        //min amount condition check
        if ($code->min_amount > 0) {
            if ($subTotal < $code->min_amount) {
                return response()->json([
                    'status' => false,
                    'message' => 'Your min amount must be Rp. '.number_format($code->min_amount).'.',
                ]);
            }
        }

        session()->put('code',$code);

        return $this->getOrderSummery($request);
    }

    public function removeCoupon(Request $request) {
        session()->forget('code');
        return $this->getOrderSummery($request);
    }
}
