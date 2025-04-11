<?php

namespace App\Http\Controllers;

use App\Http\Services\AffiliateService;
use App\Models\CommissionHistory;
use App\Models\Order;
use App\Models\OrderUpdate;
use App\Models\OrdersExport;
use App\Models\User;
use App\Models\Wallet;
use App\Notifications\DB\OrderNotification;
use CoreComponentRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Http\Controllers\QpayController;
use Excel;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:show_orders'])->only('index');
        $this->middleware(['permission:view_orders'])->only('show');
        $this->middleware(['permission:delete_orders'])->only('destroy');
    }

    public function index(Request $request)
    {
        CoreComponentRepository::instantiateShopRepository();

        $payment_status = null;
        $delivery_status = null;
        $sort_search = null;

        $admin = User::where('user_type', 'admin')->first();
        $orders = Order::with(['combined_order'])->where('shop_id', $admin->shop_id);

        if ($request->has('search') && $request->search != null) {
            $sort_search = $request->search;
            $orders = $orders->whereHas('combined_order', function ($query) use ($sort_search) {
                $query->where('code', 'like', '%' . $sort_search . '%');
            });
        }
        if ($request->payment_status != null) {
            $orders = $orders->where('payment_status', $request->payment_status);
            $payment_status = $request->payment_status;
        }
        if ($request->delivery_status != null) {
            $orders = $orders->where('delivery_status', $request->delivery_status);
            $delivery_status = $request->delivery_status;
        }

        $orders = $orders->latest()->paginate(15);
        return view('backend.orders.index', compact('orders', 'payment_status', 'delivery_status', 'sort_search'));
    }
    public function pickup_orders(Request $request)
    {
        CoreComponentRepository::instantiateShopRepository();

        $payment_status = null;
        $delivery_status = null;
        $sort_search = null;

        $admin = User::where('user_type', 'admin')->first();
        $orders = Order::with(['combined_order'])->where('shop_id', $admin->shop_id)->where('type_of_delivery','pickup');

        if ($request->has('search') && $request->search != null) {
            $sort_search = $request->search;
            $orders = $orders->whereHas('combined_order', function ($query) use ($sort_search) {
                $query->where('code', 'like', '%' . $sort_search . '%');
            });
        }
        if ($request->payment_status != null) {
            $orders = $orders->where('payment_status', $request->payment_status);
            $payment_status = $request->payment_status;
        }
        if ($request->delivery_status != null) {
            $orders = $orders->where('delivery_status', $request->delivery_status);
            $delivery_status = $request->delivery_status;
        }

        $orders = $orders->latest()->paginate(15);
        return view('backend.orders.index', compact('orders', 'payment_status', 'delivery_status', 'sort_search'));
    }

    public function show($id)
    {
        $order = Order::with(['orderDetails.product', 'orderDetails.variation.combinations','pickupPoint'])->findOrFail($id);
        $order_shipping_address = json_decode($order->shipping_address);
        $delivery_boys = User::leftJoin('addresses', 'users.id', '=', 'addresses.user_id')->where('user_type', 'delivery_boy')->where('city', $order_shipping_address->city??"")->where('banned', 0)->select('users.id', 'users.name')->get();
        // dd($order);
        return view('backend.orders.show', compact('order', 'delivery_boys'));
    }

    public function report_index(Request $request)
    {
        CoreComponentRepository::instantiateShopRepository();

        $sale_id = null;
        $date_range = null;
        $user_id = null;
        if (isset($request->export) && $request->export == "export") {
            $this->export($request);
            return back();
        }
        $admin = User::where('user_type', 'admin')->first();
        $orders = Order::with(['combined_order'])->where('shop_id', $admin->shop_id)->where('payment_status', 'paid');

        if (isset($request->sale_id)) {
            $sale_id = $request->sale_id;
            $orders = $orders->where('assign_sale_id', $sale_id);
        }

        if (isset($request->date_range)) {
            $date_range = $request->date_range;
            list($startDate, $endDate) = explode(' to ', $request->date_range);

            // Example of using whereBetween in a query
            $orders = $orders->whereBetween('created_at', [$startDate, $endDate]);
        }
        
        if (isset($request->user_id)) {
            $orders = $orders->where('user_id', $request->user_id);
            $user_id = $request->user_id;
        }

        $orders = $orders->latest()->paginate(15);
        return view('backend.orders.report_index', compact('orders', 'sale_id', 'date_range', 'user_id'));
    }

    /**
     * Display a single sale to admin.
     *
     * @return \Illuminate\Http\Response
     */


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
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        if ($order != null) {
            foreach ($order->orderDetails as $key => $orderDetail) {
                $orderDetail->delete();
            }
            foreach ($order->refundRequests as $key => $refundRequest) {
                foreach ($refundRequest->refundRequestItems as $key => $refundRequestItem) {
                    $refundRequestItem->delete();
                }
                $refundRequest->delete();
            }

            $order_count = Order::where('combined_order_id', $order->combined_order_id)->count();
            if ($order_count == 1) {
                $order->combined_order->delete();
            }
            $order->delete();
            flash(translate('Order has been deleted successfully'))->success();
        } else {
            flash(translate('Something went wrong'))->error();
        }
        return back();
    }

    public function update_delivery_status(Request $request)
    {

        $order = Order::findOrFail($request->order_id);
        OrderUpdate::create([
            'order_id' => $order->id,
            'user_id' => auth()->user()->id,
            'note' => 'Order status updated to ' . $request->status . '.',
        ]);
        if ($order->delivery_status != 'cancelled' && $request->status == 'cancelled') {
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
            if ($order->payment_status == 'paid') {

                if ($order->payment_type == 'wallet') {
                    $user = User::where('id', $order->user_id)->first();
                    $user->balance += $order->grand_total;
                    $user->save();

                    $wallet = new Wallet;
                    $wallet->user_id = $user->id;
                    $wallet->amount = $order->grand_total;
                    $wallet->details = 'Order Cancelled. Order Code ' . $order->combined_order->code;
                    $wallet->save();
                }

                $shop = $order->shop;
                if ($shop->user->user_type == 'seller') {
                    if ($order->payment_type == 'cash_on_delivery') {
                        // For Cash on Delivery admin commmision was deducted from the seller old balance. That's why the deducted commission amount have to add again.
                        $shop->current_balance += $order->admin_commission;

                        $commission = new CommissionHistory();
                        $commission->order_id = $order->id;
                        $commission->shop_id = $shop->id;
                        $commission->seller_earning = $order->admin_commission;
                        $commission->details = format_price($order->admin_commission) . ' is Added for Cash On Delivery Order Cancellation.';
                        $commission->save();
                    } else {
                        $shop->current_balance -= $order->seller_earning;
                    }
                    $shop->save();
                }
            }
        }

        $order->delivery_status = $request->status;
        $order->save();

        if ($request->status == "shipped") {
            $qpay = new QpayController;
            $qpay->send_example(json_decode($combined_order->shipping_address)->phone, 'Tanii zahialga hurgelted garlaa. '.$order->grand_total.'. 24-48 tsagiin dotor hurgegdene.');
        }
        //    affiliate status & comissionupdate
        if ($order->delivery_status == 'delivered' && $order->payment_status == 'paid') {
            (new AffiliateService)->processAffiliatePoints($order);
        }
        foreach ($order->orderDetails as $orderDetail) {
            if (($request->status == 'delivered' || $request->status == 'cancelled') &&
                $orderDetail->product_referral_code
            ) {

                $no_of_delivered = 0;
                $no_of_canceled = 0;

                if ($request->status == 'delivered') {
                    $no_of_delivered = $orderDetail->quantity;
                }
                if ($request->status == 'cancelled') {
                    $no_of_canceled = $orderDetail->quantity;
                }

                $referred_by_user = User::where('referral_code', $orderDetail->product_referral_code)->first();

                $affiliateService = new AffiliateService;
                $affiliateService->processAffiliateStats($referred_by_user->id, 0, 0, $no_of_delivered, $no_of_canceled);
            }
        }
        $user = User::where('id', $order->user_id)->first();
        // database notification
        Notification::send($user, new OrderNotification($order));

        if (get_setting('delivery_boy') == 1) {
            if (auth()->user()->user_type == 'delivery_boy') {
                $deliveryBoyController = new DeliveryBoyController;
                $deliveryBoyController->store_delivery_history($order);
            }
        }

        flash(translate('Delivery status has been updated.'))->success();
        return 1;
    }

    public function update_payment_status(Request $request)
    {

        $order = Order::findOrFail($request->order_id);
        if ($order->payment_status == 'unpaid') {
            $order->payment_status = $request->status;
            $order->save();

            OrderUpdate::create([
                'order_id' => $order->id,
                'user_id' => auth()->user()->id,
                'note' => 'Payment status updated to ' . $request->status . '.',
            ]);

            if ($request->status == 'paid') {
                calculate_seller_commision($order);
                $order->commission_calculated = 1;
                $order->save();
            }
            flash(translate('Payment status has been updated.'))->success();
        } else {
            flash(translate('Paid status can not be changed.'))->error();
        }

        return 1;
    }

    public function add_tracking_information(Request $request)
    {

        $order = Order::findOrFail($request->order_id);

        if ($order->courier_name) {
            $update_note = 'Courier information updated';
        } else {
            $update_note = 'Courier information added';
        }

        $order->courier_name = $request->courier_name;
        $order->tracking_number = $request->tracking_number;
        $order->tracking_url = $request->tracking_url;
        $order->save();

        OrderUpdate::create([
            'order_id' => $order->id,
            'user_id' => auth()->user()->id,
            'note' => $update_note,
        ]);

        flash(translate('Courier information has been updated.'))->success();

        return back();
    }

    public function bulk_order_delete(Request $request)
    {
        if ($request->id) {
            foreach ($request->id as $order_id) {
                $this->destroy($order_id);
            }
        }

        return 1;
    }
    public function bulk_order_delivered(Request $request)
    {
        if ($request->id) {
            Order::whereIn('id', $request->id)
            ->update(['delivery_status' => 'delivered']);
        }

        return 1;
    }
    public function bulk_order_Cancelled(Request $request)
    {
        if ($request->id) {
            Order::whereIn('id', $request->id)
            ->update(['delivery_status' => 'Cancelled']);
        }

        return 1;
    }
    public function bulk_order_paid(Request $request)
    {
        if ($request->id) {
           
            // Order::whereIn('id', $request->id)
            // ->update(['payment_status' => 'paid']);
            foreach ($request->id as $order_id) {
                Order::where('id', $order_id)
                ->update(['payment_status' => 'paid']);
            }
        }

        return 1;
    }

    public function export(Request $request)
    {
        // Capture the filters from the request
        $sale_id = $request->sale_id ?? 0; // Default to 0 if not provided
        $user_id = $request->user_id ?? 0; // Default to 0 if not provided
        $date_range = $request->date_range ?? ''; // Default to empty string if not provided

        // Pass filters to the OrdersExport class
        return Excel::download(new OrdersExport($sale_id, $user_id, $date_range), 'orders.xlsx');
    }

    public function update_note(Request $request) {
        $order = Order::find($request->order_id);
        $order->note = $request->note;
        $order->save();
        return back();
    }
}
