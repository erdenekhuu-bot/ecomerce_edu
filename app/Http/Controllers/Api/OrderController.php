<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\DownloadedProductResource;
use App\Http\Resources\OrderCollection;
use App\Http\Resources\QuoteCollection;
use App\Http\Resources\OrderSingleCollection;
use App\Http\Services\AffiliateService;
use App\Http\Resources\QuoteSingleCollection;
use App\Mail\InvoiceEmailManager;
use App\Models\Address;
use App\Models\Cart;
use App\Models\City;
use App\Models\Country;
use App\Models\CombinedOrder;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\Language;
use App\Models\ManualPaymentMethod;
use App\Models\Membership;
use App\Models\Order;
use App\Models\PriceQuote;
use App\Models\PriceQuoteDetail;
use App\Models\OrderDetail;
use App\Models\OrderUpdate;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\Shop;
use App\Models\State;
use App\Models\Upload;
use App\Models\User;
use App\Models\Wallet;
use App\Notifications\DB\OrderNotification;
use App\Notifications\OrderPlacedNotification;
use App\Notifications\SellerInvoiceNotification;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use PDF;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\QpayController;

class OrderController extends Controller
{
    public function index()
    {
        return new OrderCollection(CombinedOrder::with(['user', 'orders.orderDetails.variation.product', 'orders.orderDetails.variation.combinations', 'orders.shop'])->where('user_id', auth()->id())->latest()->paginate(12));
    }

    public function quoteIndex()
    {
        return new QuoteCollection(PriceQuote::with(['user', 'orderDetails.variation.product', 'orderDetails.variation.combinations', 'shop'])->where('user_id', auth()->id())->latest()->paginate(12));
    }

    public function show($order_code)
    {
        $order = CombinedOrder::where('code', $order_code)->with(['user', 'orders.orderDetails.variation.product', 'orders.orderDetails.variation.combinations', 'orders.shop'])->first();
        if ($order) {
            if (auth('api')->user()->id == ($order->user_id || $order->assign_delivery_boy)) {
                return new OrderSingleCollection($order);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => translate("This order is not your. You can't check details of this order"),
                    'status' => 200
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => translate("No order found by this code"),
                'status' => 404
            ]);
        }
    }

    public function quoteShow($order_code)
    {
        $order = PriceQuote::where('quote_no', $order_code)->with(['user', 'orderDetails.variation.product', 'orderDetails.variation.combinations', 'shop'])->first();
        if ($order) {
            if (auth('api')->user()->id == ($order->user_id || $order->assign_delivery_boy)) {
                return new QuoteSingleCollection($order);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => translate("This order is not your. You can't check details of this order"),
                    'status' => 200
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => translate("No order found by this code"),
                'status' => 404
            ]);
        }
    }

    public function get_shipping_cost(Request $request, $address_id)
    {
        $address = Address::find($address_id);
        $city = State::find($address->state_id);

        if ($city && $city->zone != null) {
            return response()->json([
                'success' => true,
                'standard_delivery_cost' => $city->zone->standard_delivery_cost,
                'express_delivery_cost' => $city->zone->express_delivery_cost,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'standard_delivery_cost' => 0,
                'express_delivery_cost' => 0,
            ]);
        }
    }

    public function invoice_download(Request $request, $order_id)
    {
        $currency_code = env('DEFAULT_CURRENCY_CODE');

        $language_code = app()->getLocale();

        if (optional(Language::where('code', $language_code)->first())->rtl == 1) {
            $direction = 'rtl';
            $default_text_align = 'right';
            $reverse_text_align = 'left';
        } else {
            $direction = 'ltr';
            $default_text_align = 'left';
            $reverse_text_align = 'right';
        }


        if ($currency_code == 'BDT' || $language_code == 'bd') {
            // bengali font
            $font_family = "'Hind Siliguri','sans-serif'";
        } elseif ($currency_code == 'KHR' || $language_code == 'kh') {
            // khmer font
            $font_family = "'Hanuman','sans-serif'";
        } elseif ($currency_code == 'AMD') {
            // Armenia font
            $font_family = "'arnamu','sans-serif'";
        } elseif ($currency_code == 'ILS') {
            // Israeli font
            $font_family = "'Varela Round','sans-serif'";
        } elseif ($currency_code == 'AED' || $currency_code == 'EGP' || $language_code == 'sa' || $currency_code == 'IQD' || $language_code == 'ir') {
            // middle east/arabic font
            $font_family = "'XBRiyaz','sans-serif'";
        } else {
            // general for all
            $font_family = "'Roboto','sans-serif'";
        }

        $order = Order::where('id', $order_id)->first();

        $invoice_directory = 'public/invoices';

        if (! File::exists($invoice_directory)) {
            File::makeDirectory($invoice_directory, 0755, true, true);
        }

        $pdf =  PDF::loadView('backend.invoices.invoice', [
            'order' => $order,
            'font_family' => $font_family,
            'direction' => $direction,
            'default_text_align' => $default_text_align,
            'reverse_text_align' => $reverse_text_align
        ], [], [])->save(public_path('invoices/') . 'order-invoice-' . $order->combined_order->code . '.pdf');

        $pdf = static_asset('invoices/' . 'order-invoice-' . $order->combined_order->code . '.pdf');

        try {
            return response()->json([
                'success' => true,
                'message' => translate('Invoice generated.'),
                'invoice_url' => $pdf,
                'invoice_name' => $order->combined_order->code,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => translate('Something went wrong!'),
                'invoice_url' => ''
            ]);
        }
    }

    public function cancel($order_id)
    {
        $order = Order::findOrFail($order_id);
        if (auth('api')->user()->id !==  $order->user_id) {
            return response()->json(null, 401);
        }

        if ($order->delivery_status == 'order_placed' && $order->payment_status == 'unpaid') {
            $order->delivery_status = 'cancelled';
            $order->save();

            $array['view'] = 'emails.order_cancelled';
            $array['subject'] = "Захиалга цуцлагдлаа" . ' - ' . $combined_order->code;
            $array['from'] = env('MAIL_FROM_ADDRESS');
            $array['combined_order'] = $combined_order;
            if ($order->user->email != null) {
                Mail::to($order->user->email)->queue(new InvoiceEmailManager($array));
            }

            foreach ($order->orderDetails as $orderDetail) {
                try {
                    foreach ($orderDetail->product->categories as $category) {
                        $category->sales_amount -= $orderDetail->total;
                        $category->save();
                    }

                    $brand = $orderDetail->product->brand;
                    if ($brand) {
                        $brand->sales_amount -= $orderDetail->total;
                        $brand->save();
                    }
                } catch (\Exception $e) {
                }
                $orderDetail->variation->update(['current_stock' => $orderDetail->variation->current_stock + $orderDetail->quantity ]);
            }

            return response()->json([
                'success' => true,
                'message' => translate("Order has been cancelled"),
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => translate("This order can't be cancelled."),
            ]);
        }
    }

    public function store(Request $request)
    {
        $cartItems = Cart::whereIn('id', $request->cart_item_ids)->with(['variation.product'])->get();
        $shippingAddress = Address::find($request->shipping_address_id);
        $shippingCity = State::with('zone')->find($shippingAddress?->state_id);
        $user = auth('api')->user();

        if ($cartItems->count() < 1)
            return response()->json([
                'success' => false,
                'message' => translate('Your cart is empty. Please select a product.')
            ]);



        if (!$request->type_of_delivery)
            return response()->json([
                'success' => false,
                'message' => translate('Please select a delivery type.')
            ]);

        if ($request->type_of_delivery !== 'pickup') {
            if ($request->delivery_type != 'standard' && $request->delivery_type != 'express')
                return response()->json([
                    'success' => false,
                    'message' => translate('Please select a delivery option.')
                ]);

            if (!$request->shipping_address_id)
                return response()->json([
                    'success' => false,
                    'message' => translate('Please select a shipping address.')
                ]);

            if (!$shippingCity->zone)
                return response()->json([
                    'success' => false,
                    'message' => translate('Sorry, delivery is not available in this shipping address.')
                ]);
        }

        foreach ($cartItems as $cartItem) {
            if (!$cartItem->variation->stock) {
                return response()->json([
                    'success' => false,
                    'message' => $cartItem->variation->product->getTranslation('name') . ' ' . translate('is out of stock.')
                ]);
            }
            if ($cartItem->variation->current_stock != null && $cartItem->quantity > $cartItem->variation->current_stock) {
                return response()->json([
                    'success' => false,
                    'message' => $cartItem->variation->product->getTranslation('name') . ' ' . translate('is out of stock.')
                ]);
            }
        }

        if ($request->delivery_type == 'standard') {
            $shipping_cost = $shippingCity->zone->standard_delivery_cost;
        } elseif ($request->delivery_type == 'express') {
            $shipping_cost = $shippingCity->zone->express_delivery_cost;
        }

        if ($request->type_of_delivery == 'pickup') {
            $shipping_cost = 0;
        }

        // generate array of shops cart items
        $shops_cart_items = array();
        $club_points = 0;

        foreach ($cartItems as $cartItem) {
            $cart_ids = array();
            $product = $cartItem->variation->product;
            if (isset($shops_cart_items[$product->shop_id])) {
                $cart_ids = $shops_cart_items[$product->shop_id];
            }
            array_push($cart_ids, $cartItem->id);

            $shops_cart_items[$product->shop_id] = $cart_ids;

            if (get_setting('club_point') == 1) {
                if ($product->earn_point != null) {
                    $club_points += $cartItem->product->earn_point * $cartItem->quantity;
                }
            }
        }

        // get coupon data based on request
        $coupons = collect();
        if ($request->coupon_codes && !empty($request->coupon_codes)) {
            $coupons = Coupon::where(function ($query) use ($request) {
                foreach ($request->coupon_codes as $coupon_code) {
                    $query->orWhere('code', $coupon_code);
                }
            })->get();
        }



        $combined_order = new CombinedOrder;
        $combined_order->user_id = $user->id;
        $combined_order->code = "ORD".date('Ymd') . "-" . rand(1000, 9999);
        $combined_order->shipping_address = json_encode($shippingAddress);
        $combined_order->save();

        $grand_total = 0;

        // all shops order place
        $package_number = 1;
        foreach ($shops_cart_items as $shop_id => $shop_cart_item_ids) {

            $shop_cart_items = $cartItems->whereIn('id', $shop_cart_item_ids);

            $shop_subTotal = 0;
            $shop_tax = 0;
            $shop_coupon_discount = 0;
            $percent = 0;
            //shop total amount calculation
            foreach ($shop_cart_items as $cartItem) {
                $itemPriceWithoutTax = variation_discounted_price($cartItem->variation->product, $cartItem->variation, false) * $cartItem->quantity;
                $itemTax = product_variation_tax($cartItem->variation->product, $cartItem->variation) * $cartItem->quantity;
                $membership_price = variation_discounted_price($cartItem->variation->product, $cartItem->variation, false) * $cartItem->quantity;
                $percent = 0;
                $user = auth('api')->user();
                if ($user) {
                    if ($user->user_type == "customer") {
                        $total_order_price = $user->orders()->where('payment_status','paid')->sum('grand_total');
                        $memberships = Membership::orderby('value', 'ASC')->get();
                        foreach ($memberships as $key => $membership) {
                            if ($membership->value <= $total_order_price) {
                                $percent = ($membership_price / 100) * $membership->percent;
                                $membership_price -= ($membership_price / 100) * $membership->percent;
                            }
                        }
                    }
                }
                $shop_subTotal += $membership_price;
                $shop_tax += $itemTax;
            }
            $shop_total = $shop_subTotal + $shipping_cost + $shop_tax;


            // shop coupon check & disount calculation
            if ($request->coupon_codes && !empty($request->coupon_codes)) {

                $coupon = $coupons->firstWhere('shop_id', $shop_id);
                if ($coupon) {
                    $shop_coupon_discount = (new CouponController)->calculate_discount($coupon, $shop_total, $shop_cart_items);

                    $shop_total -= $shop_coupon_discount;

                    $coupon_usage = new CouponUsage();
                    $coupon_usage->user_id = $user->id;
                    $coupon_usage->coupon_id = $coupon->id;
                    $coupon_usage->save();
                }
            }

            // shop order place
            $order = Order::create([
                'user_id' => auth('api')->user()->id,
                'shop_id' => $shop_id,
                'combined_order_id' => $combined_order->id,
                'code' => $package_number,
                'shipping_address' => json_encode($shippingAddress),
                'shipping_cost' => $shipping_cost,
                'grand_total' => $shop_total,
                'coupon_code' => $coupon->code ?? null,
                'membership' => $percent,
                'coupon_discount' => $shop_coupon_discount,
                'delivery_type' => $request->delivery_type,
                'payment_type' => $request->payment_type,
                'type_of_delivery' => $request->type_of_delivery,
                'pickup_point_id' => $request->pickup_point_id,
            ]);

            $package_number++;
            $grand_total += $shop_total;


            foreach ($shop_cart_items as $cartItem) {
                $itemPriceWithoutTax = variation_discounted_price($cartItem->variation->product, $cartItem->variation, false);
                $itemTax = product_variation_tax($cartItem->variation->product, $cartItem->variation);

                $orderDetail = OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'product_variation_id' => $cartItem->product_variation_id,
                    'price' => $itemPriceWithoutTax,
                    'tax' => $itemTax,
                    'total' => ($itemPriceWithoutTax + $itemTax) * $cartItem->quantity,
                    'quantity' => $cartItem->quantity,
                    'product_referral_code' => $cartItem->product_referral_code,
                ]);

                $cartItem->product->update([
                    'num_of_sale' => DB::raw('num_of_sale + ' . $cartItem->quantity)
                ]);

                foreach ($orderDetail->product->categories as $category) {
                    $category->sales_amount += $orderDetail->total;
                    $category->save();
                }

                $brand = $orderDetail->product->brand;
                if ($brand) {
                    $brand->sales_amount += $orderDetail->total;
                    $brand->save();
                }
                // stock manage 
                if ($cartItem->variation->current_stock != null) {
                    $cartItem->variation->update(['current_stock' => $cartItem->variation->current_stock - $cartItem->quantity]);
                }
            }

            $order_price = $order->grand_total - $order->shipping_cost - $order->orderDetails->sum(function ($t) {
                return $t->tax * $t->quantity;
            });

            $shop_commission = Shop::find($shop_id)->commission;
            $admin_commission = 0.00;
            $seller_earning = $shop_total;
            if ($shop_commission > 0) {
                $admin_commission = ($shop_commission * $order_price) / 100;
                $seller_earning = $shop_total - $admin_commission;
            }

            $order->admin_commission = $admin_commission;
            $order->seller_earning = $seller_earning;
            $order->commission_percentage = $shop_commission;
            $order->save();
            OrderUpdate::create([
                'order_id' => $order->id,
                'user_id' => auth('api')->user()->id,
                'note' => 'Order has been placed.',
            ]);
        }
        $combined_order->grand_total = $grand_total;
        $combined_order->save();
        // affiliate status update
        if ($orderDetail->product_referral_code) {
            $referred_by_user = User::where('referral_code', $orderDetail->product_referral_code)->first();

            $affiliateService = new AffiliateService;
            $affiliateService->processAffiliateStats($referred_by_user->id, 0, $orderDetail->quantity, 0, 0);
        }
        //Invioce mail send to the customer and seller
        //sends email to customer with the invoice pdf attached

        try {
            $array['view'] = 'emails.order_create';
            $array['subject'] = "Захиалга бүртгэгдлээ" . ' - ' . $combined_order->code;
            $array['from'] = env('MAIL_FROM_ADDRESS');
            $array['combined_order'] = $combined_order;
            if ($order->user->email != null) {
                Mail::to($order->user->email)->queue(new InvoiceEmailManager($array));
            }
            foreach ($combined_order->orders as $order) {
                $seller = $order->orderDetails->first()->product->shop->user;
                Mail::to($seller->email)->queue(new InvoiceEmailManager($array));
                // database notification
                Notification::send($seller, new OrderNotification($order));
            }
        } catch (\Exception $e) {
        }
        // add club points
        if (get_setting('club_point') == 1) {
            (new ClubPointController)->processClubPoints($combined_order, $club_points);
        }

        // clear user's cart
        Cart::destroy($request->cart_item_ids);

        if ($request->payment_type == 'wallet') {
            $user->balance -= $combined_order->grand_total;
            $user->save();

            $wallet = new Wallet;
            $wallet->user_id = $user->id;
            $wallet->amount = $combined_order->grand_total;
            $wallet->type = 'Deducted';
            $wallet->details = 'Order Placed. Order Code ' . $combined_order->code;
            $wallet->save();

            $this->paymentDone($combined_order, $request->payment_type);
        }

        if ($request->payment_type == 'cash_on_delivery' || $request->payment_type == 'wallet' || $request->payment_type == 'qpay' || $request->payment_type == 'golomt' || strpos($request->payment_type, 'offline_payment')  !== false) {
            $go_to_payment = false;
            // check if offline payment type & set manual payment data from here.
            // dd(strpos($request->payment_type, 'offline_payment'));
            if (strpos($request->payment_type, 'offline_payment')  !== false) {
                // set manual payment data
                $splittedPaymentMethod = explode('-', $request->payment_type);
                $offlinePaymentId = array_pop($splittedPaymentMethod);
                $manualPaymentMethod = ManualPaymentMethod::find((int) $offlinePaymentId);

                $manual_payment_data = new ManualPaymentMethod;
                $manual_payment_data->transactionId = $request->transactionId;
                $manual_payment_data->payment_method = $manualPaymentMethod->heading;


                // store receipt here 
                if ($request->hasFile('receipt')) {
                    $manual_payment_data->reciept = $request->receipt->store(
                        'uploads/offline_payments'
                    );
                } else {
                    $manual_payment_data->reciept = null;
                }

                $order->manual_payment = 1;
                $order->manual_payment_data = json_encode($manual_payment_data);
                $order->save();

                // $this->paymentDone($combined_order, 'offline_payment');
            }
            $qpay = new QpayController;
            if ($request->payment_type == 'qpay') {
                $qpay_auth_response = $qpay->authenticate();

                $send['bill_no'] = $combined_order->code;
                $send['amount'] = $combined_order->grand_total;

                $qpay_create_response = $qpay->create($send);

                $qpay_create = json_decode($qpay_create_response);
                $order->qpay_id = $qpay_create->invoice_id;
                $order->qpay_qrcode = $qpay_create->qr_text;
                $order->qpay_qrimage = $qpay_create->qr_image;
                $order->qpay_url = $qpay_create->qPay_shortUrl;
                $order->qpay_short_url = $qpay_create->qPay_shortUrl;
                $order->qpay_deeplinks = json_encode($qpay_create->urls);
                $order->access_token = $qpay_auth_response;
                $order->save();
                $qpay->send_example($shippingAddress->phone, 'Tanii zahialga burtgegdlee. Tulbur tulsunuur tanii zahialga batalgaajna.');
            }else{
                $qpay->send_example($shippingAddress->phone, 'Tanii zahialga burtgegdlee. Tulbur tuluh dans 1705240039 ОРЕМХАН БОГД ХХК '.$grand_total.' '.$combined_order->code.'.');
            }
        } else {
            $go_to_payment = true;
        }


        return response()->json([
            'success' => true,
            'go_to_payment' => $go_to_payment,
            'grand_total' => $grand_total,
            'payment_method' => $request->payment_type,
            'message' => translate('Your order has been placed successfully'),
            'order_code' => $combined_order->code
        ]);
    }

    public function quoteStore(Request $request, $code)
    {
        $quote = PriceQuote::where('quote_no', $code)->first();
        $user = auth('api')->user();
        
        $data = [];
        foreach ($quote->orderDetails as $cartItem) {
            $variation = ProductVariation::find($cartItem->product_variation_id);

            if ($variation->current_stock == 0) {
                return response()->json([
                    'success' => false,
                    'message' => $variation->product->getTranslation('name') . ' барааны ' . "Үлдэгдэл дууссан байна.",
                ]);
            }

            if ($variation->current_stock < $cartItem->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => $variation->product->getTranslation('name') . ' барааны ' . "Үлдэгдэл хүрэлцэхгүй байна.",
                ]);
            }
        }

        if ($quote->is_buy == 1) {
            return response()->json([
                'success' => false,
                'message' => "Тус үнийн саналыг ганц удаа худалдана авах боломжтой",
            ]);
        }

        $combined_order = new CombinedOrder;
        $combined_order->user_id = $user->id;
        $combined_order->code = "ORD".date('Ymd') . "-" . rand(1000, 9999);
        $combined_order->shipping_address = $quote->shipping_address;
        $combined_order->save();

        // shop order place
        $order = Order::create([
            'user_id' => $user->id,
            'shop_id' => $quote->shop_id,
            'combined_order_id' => $combined_order->id,
            'code' => 1,
            'shipping_address' => $quote->shipping_address,
            'shipping_cost' => $quote->shipping_cost,
            'grand_total' => $quote->grand_total,
            'coupon_discount' => $quote->coupon_discount,
            'discount' => $quote->discount,
            'membership' => $quote->membership,
            'delivery_type' => $quote->delivery_type,
            'payment_type' => $request->payment_type,
            'type_of_delivery' => $quote->type_of_delivery,
            'pickup_point_id' => $quote->pickup_point_id,
            'assign_sale_id' => $quote->assign_sale_id,
        ]);

        foreach ($quote->orderDetails as $cartItem) {
            $itemPriceWithoutTax = variation_discounted_price($cartItem->variation->product, $cartItem->variation, false);
            $itemTax = product_variation_tax($cartItem->variation->product, $cartItem->variation);

            $orderDetail = OrderDetail::create([
                'order_id' => $order->id,
                'product_id' => $cartItem->product_id,
                'product_variation_id' => $cartItem->product_variation_id,
                'price' => $itemPriceWithoutTax,
                'tax' => $itemTax,
                'total' => $cartItem->total,
                'quantity' => $cartItem->quantity,
                'discount' => $cartItem->discount,
                'discount_type' => $cartItem->discount_type,
                'product_referral_code' => $cartItem->product_referral_code,
            ]);

            $cartItem->product->update([
                'num_of_sale' => DB::raw('num_of_sale + ' . $cartItem->quantity)
            ]);

            foreach ($orderDetail->product->categories as $category) {
                $category->sales_amount += $orderDetail->total;
                $category->save();
            }

            $brand = $orderDetail->product->brand;
            if ($brand) {
                $brand->sales_amount += $orderDetail->total;
                $brand->save();
            }
            // stock manage 
            if ($cartItem->variation->current_stock != null) {
                $cartItem->variation->update(['current_stock' => $cartItem->variation->current_stock - $cartItem->quantity]);
            }
        }

        $order_price = $order->grand_total - $order->shipping_cost - $order->orderDetails->sum(function ($t) {
            return $t->tax * $t->quantity;
        });

        $shop_commission = Shop::find($quote->shop_id)->commission;

        $order->admin_commission = $quote->admin_commission;
        $order->seller_earning = $quote->seller_earning;
        $order->commission_percentage = $quote->commission_percentage;
        $order->save();
        OrderUpdate::create([
            'order_id' => $order->id,
            'user_id' => auth('api')->user()->id,
            'note' => 'Order has been placed.',
        ]);
        
        $combined_order->grand_total = $quote->grand_total;
        $combined_order->save();
        // affiliate status update
        if ($orderDetail->product_referral_code) {
            $referred_by_user = User::where('referral_code', $orderDetail->product_referral_code)->first();

            $affiliateService = new AffiliateService;
            $affiliateService->processAffiliateStats($referred_by_user->id, 0, $orderDetail->quantity, 0, 0);
        }
        //Invioce mail send to the customer and seller
        //sends email to customer with the invoice pdf attached

        try {
            $array['view'] = 'emails.order_create';
            $array['subject'] = "Захиалга бүртгэгдлээ" . ' - ' . $combined_order->code;
            $array['from'] = env('MAIL_FROM_ADDRESS');
            $array['combined_order'] = $combined_order;
            if ($order->user->email != null) {
                Mail::to($order->user->email)->queue(new InvoiceEmailManager($array));
            }
            foreach ($combined_order->orders as $order) {
                $seller = $order->orderDetails->first()->product->shop->user;
                Mail::to($seller->email)->queue(new InvoiceEmailManager($array));
                // database notification
                Notification::send($seller, new OrderNotification($order));
            }
        } catch (\Exception $e) {
        }
        // add club points
        // if (get_setting('club_point') == 1) {
        //     (new ClubPointController)->processClubPoints($combined_order, $club_points);
        // }

        // clear user's cart
        Cart::destroy($request->cart_item_ids);

        if ($request->payment_type == 'wallet') {
            $user->balance -= $combined_order->grand_total;
            $user->save();

            $wallet = new Wallet;
            $wallet->user_id = $user->id;
            $wallet->amount = $combined_order->grand_total;
            $wallet->type = 'Deducted';
            $wallet->details = 'Order Placed. Order Code ' . $combined_order->code;
            $wallet->save();

            $this->paymentDone($combined_order, $request->payment_type);
        }

        if ($request->payment_type == 'cash_on_delivery' || $request->payment_type == 'wallet' || $request->payment_type == 'qpay' || $request->payment_type == 'golomt' || strpos($request->payment_type, 'offline_payment')  !== false) {
            $go_to_payment = false;
            // check if offline payment type & set manual payment data from here.
            // dd(strpos($request->payment_type, 'offline_payment'));
            if (strpos($request->payment_type, 'offline_payment')  !== false) {
                // set manual payment data
                $splittedPaymentMethod = explode('-', $request->payment_type);
                $offlinePaymentId = array_pop($splittedPaymentMethod);
                $manualPaymentMethod = ManualPaymentMethod::find((int) $offlinePaymentId);

                $manual_payment_data = new ManualPaymentMethod;
                $manual_payment_data->transactionId = $request->transactionId;
                $manual_payment_data->payment_method = $manualPaymentMethod->heading;


                // store receipt here 
                if ($request->hasFile('receipt')) {
                    $manual_payment_data->reciept = $request->receipt->store(
                        'uploads/offline_payments'
                    );
                } else {
                    $manual_payment_data->reciept = null;
                }

                $order->manual_payment = 1;
                $order->manual_payment_data = json_encode($manual_payment_data);
                $order->save();

                // $this->paymentDone($combined_order, 'offline_payment');
            }
            $qpay = new QpayController;
            if ($request->payment_type == 'qpay') {
                
                $qpay_auth_response = $qpay->authenticate();

                $send['bill_no'] = $combined_order->code;
                $send['amount'] = $combined_order->grand_total;

                $qpay_create_response = $qpay->create($send);

                $qpay_create = json_decode($qpay_create_response);
                $order->qpay_id = $qpay_create->invoice_id;
                $order->qpay_qrcode = $qpay_create->qr_text;
                $order->qpay_qrimage = $qpay_create->qr_image;
                $order->qpay_url = $qpay_create->qPay_shortUrl;
                $order->qpay_short_url = $qpay_create->qPay_shortUrl;
                $order->qpay_deeplinks = json_encode($qpay_create->urls);
                $order->access_token = $qpay_auth_response;
                $order->save();
                $qpay->send_example($shippingAddress->phone, 'Tanii zahialga burtgegdlee. Tulbur tulsunuur tanii zahialga batalgaajna.');
            }else{
                $qpay->send_example($shippingAddress->phone, 'Tanii zahialga burtgegdlee. Tulbur tuluh dans 1705240039 ОРЕМХАН БОГД ХХК '.$grand_total.' '.$combined_order->code.'.');
            }
        } else {
            $go_to_payment = true;
        }
        $quote->is_buy = 1;
        $quote->save();

        return response()->json([
            'success' => true,
            'go_to_payment' => $go_to_payment,
            'grand_total' => $combined_order->grand_total,
            'payment_method' => $request->payment_type,
            'message' => translate('Your order has been placed successfully'),
            'order_code' => $combined_order->code
        ]);
    }

    public function quoteCreate(Request $request) {
        $cartItems = Cart::whereIn('id', $request->cart_item_ids)->with(['variation.product'])->get();
        $shippingCity = State::with('zone')->find($request->state_id);
        $country = Country::find($request->country_id);
        $user = auth('api')->user();

        if ($cartItems->count() < 1)
            return response()->json([
                'success' => false,
                'message' => translate('Your cart is empty. Please select a product.')
            ]);


        foreach ($cartItems as $cartItem) {
            if (!$cartItem->variation->stock) {
                return response()->json([
                    'success' => false,
                    'message' => $cartItem->variation->product->getTranslation('name') . ' ' . translate('is out of stock.')
                ]);
            }
            if ($cartItem->variation->current_stock != null && $cartItem->quantity > $cartItem->variation->current_stock) {
                return response()->json([
                    'success' => false,
                    'message' => $cartItem->variation->product->getTranslation('name') . ' ' . translate('is out of stock.')
                ]);
            }
        }

        $shipping_cost = 0;

        $shipping_cost = $shippingCity->zone->standard_delivery_cost ?? 0;

        // generate array of shops cart items
        $shops_cart_items = array();
        $club_points = 0;

        foreach ($cartItems as $cartItem) {
            $cart_ids = array();
            $product = $cartItem->variation->product;
            if (isset($shops_cart_items[$product->shop_id])) {
                $cart_ids = $shops_cart_items[$product->shop_id];
            }
            array_push($cart_ids, $cartItem->id);

            $shops_cart_items[$product->shop_id] = $cart_ids;

            if (get_setting('club_point') == 1) {
                if ($product->earn_point != null) {
                    $club_points += $cartItem->product->earn_point * $cartItem->quantity;
                }
            }
        }

        $grand_total = 0;

        // all shops order place
        $package_number = 1;
        foreach ($shops_cart_items as $shop_id => $shop_cart_item_ids) {

            $shop_cart_items = $cartItems->whereIn('id', $shop_cart_item_ids);

            $shop_subTotal = 0;
            $shop_tax = 0;
            $shop_coupon_discount = 0;

            //shop total amount calculation
            foreach ($shop_cart_items as $cartItem) {
                $itemPriceWithoutTax = variation_discounted_price($cartItem->variation->product, $cartItem->variation, false) * $cartItem->quantity;
                $itemTax = product_variation_tax($cartItem->variation->product, $cartItem->variation) * $cartItem->quantity;

                $shop_subTotal += $itemPriceWithoutTax;
                $shop_tax += $itemTax;
            }
            $shop_total = $shop_subTotal + $shipping_cost + $shop_tax;

            $membership_price = $shop_total;
            $percent = 0;
            $user = auth('api')->user();
            if ($user) {
                if ($user->user_type == "customer") {
                    $total_order_price = $user->orders()->where('payment_status','paid')->sum('grand_total');
                    $memberships = Membership::orderby('value', 'ASC')->get();
                    foreach ($memberships as $key => $membership) {
                        if ($membership->value <= $total_order_price) {
                            $percent = ($membership_price / 100) * $membership->percent;
                            $membership_price -= ($membership_price / 100) * $membership->percent;
                        }
                    }
                }
            }

            // shop order place
            $order = PriceQuote::create([
                'quote_no' => "QO".date('Ymd') . "-" . rand(1000, 9999),
                'user_id' => $user->user_type == 'staff' ? $request->user_id : auth('api')->user()->id,
                'shop_id' => $shop_id,
                'code' => $package_number,
                'shipping_address' => json_encode([
                    "name" => $request->name,
                    "organization" => $request->organization,
                    "email" => $request->email,
                    "address" => $request->address,
                    "country" => $country->name,
                    "city" => "",
                    "state" => $shippingCity->name,
                    "postal_code" => "",
                    "phone" => $request->phone,
                    "phone2" => $request->phone2
                ]),
                'membership' => $percent,
                'shipping_cost' => $shipping_cost,
                'grand_total' => $membership_price,
                'coupon_code' => $coupon->code ?? null,
                'coupon_discount' => $shop_coupon_discount,
                'delivery_type' => $request->delivery_type,
                'payment_type' => $request->payment_type,
                'type_of_delivery' => $request->type_of_delivery,
                'pickup_point_id' => $request->pickup_point_id,
                'quote_status' => 'user_send',
                'assign_sale_id' => $user->user_type == 'staff' ? $user->id : null,
            ]);

            $package_number++;
            $grand_total += $shop_total;


            foreach ($shop_cart_items as $cartItem) {
                $itemPriceWithoutTax = variation_discounted_price($cartItem->variation->product, $cartItem->variation, false);
                $itemTax = product_variation_tax($cartItem->variation->product, $cartItem->variation);

                $orderDetail = PriceQuoteDetail::create([
                    'price_quote_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'product_variation_id' => $cartItem->product_variation_id,
                    'price' => $itemPriceWithoutTax,
                    'tax' => $itemTax,
                    'total' => ($itemPriceWithoutTax + $itemTax) * $cartItem->quantity,
                    'quantity' => $cartItem->quantity,
                    'product_referral_code' => $cartItem->product_referral_code,
                ]);

                foreach ($orderDetail->product->categories as $category) {
                    $category->sales_amount += $orderDetail->total;
                    $category->save();
                }

            }

            $order_price = $order->grand_total - $order->shipping_cost - $order->orderDetails->sum(function ($t) {
                return $t->tax * $t->quantity;
            });

            $shop_commission = Shop::find($shop_id)->commission;
            $admin_commission = 0.00;
            $seller_earning = $shop_total;
            if ($shop_commission > 0) {
                $admin_commission = ($shop_commission * $order_price) / 100;
                $seller_earning = $shop_total - $admin_commission;
            }

            $order->admin_commission = $admin_commission;
            $order->seller_earning = $seller_earning;
            $order->commission_percentage = $shop_commission;
            $order->save();

        }

        // try {
        //     $array['view'] = 'emails.invoice';
        //     $array['subject'] = "Үнийн санал илгээгдлээ" . ' - ' . $order->quote_no;
        //     $array['from'] = env('MAIL_FROM_ADDRESS');
        //     $array['combined_order'] = $order;
        //     if ($order->user->email != null) {
        //         Mail::to($order->user->email)->queue(new InvoiceEmailManager($array));
        //     }
        // } catch (\Exception $e) {
        // }

        // clear user's cart
        Cart::destroy($request->cart_item_ids);


        return response()->json([
            'success' => true,
            'go_to_payment' => "",
            'grand_total' => $grand_total,
            'payment_method' => '',
            'message' => translate('Your order has been placed successfully'),
            'order_code' => $order->quote_no
        ]);
    }

    public function paymentDone($combined_order, $payment_method, $payment_info = null)
    {
        foreach ($combined_order->orders as $order) {

            // commission calculation
            calculate_seller_commision($order);

            $order->payment_status = 'paid';
            $order->payment_type = $payment_method;
            $order->payment_details = $payment_info;
            $order->save();
        }
    }

    public function productDownloads()
    {
        $products = DB::table('orders')
            ->orderBy('id', 'desc')
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->where('orders.user_id', auth()->id())
            ->where('products.digital', '1')
            ->where('orders.payment_status', 'paid')
            ->select('products.*')
            ->paginate(15);

        return DownloadedProductResource::collection($products)->additional([
            'success' => true,
            'status' => 200
        ]);
    }
    public function download($product)
    {
        $product = Product::findOrFail(($product));

        $upload = Upload::findOrFail($product->file_name);

        if (env('FILESYSTEM_DRIVER') == "s3") {
            return \Storage::disk('s3')->download($upload->file_name, $upload->file_original_name . "." . $upload->extension);
        } else {

            if (file_exists(base_path('public/' . $upload->file_name))) {
                return response()->download($_SERVER['HTTP_HOST'] . 'public/' . $upload->file_name);
            }
        }
    }

    public function reOrder($orderCode)
    {
        $order = CombinedOrder::where('code', $orderCode)->with(['user', 'orders.orderDetails.variation.product', 'orders.orderDetails.variation.combinations', 'orders.shop'])->first();
        dd($order->orders->products->count());
    }
}
