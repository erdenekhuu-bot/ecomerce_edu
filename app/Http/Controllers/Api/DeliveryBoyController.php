<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\OrderController;
use App\Http\Resources\Deliveryboy\TotalCollectionResource;
use App\Http\Resources\Deliveryboy\TotalEarningResource;
use App\Models\DeliveryBoy;
use App\Models\DeliveryHistory;
use App\Models\Order;
use Illuminate\Http\Request;

class DeliveryBoyController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        $data['deliveryboy'] = DeliveryBoy::select('total_collection', 'total_earning')->where('user_id', $user->id)->first();

        $assigned_orders = Order::where('assign_delivery_boy', $user->id);
        $data['total_complete_delivery'] = $assigned_orders->where('delivery_status', 'delivered')->count();

        $data['total_pending_delivery'] = $assigned_orders->where('delivery_status', '!=', 'delivered')
            ->where('delivery_status', '!=', 'cancelled')
            ->where('cancel_request', '0')
            ->count();

        return response()->json([
            'status' => 200,
            'success' => true,
            'data' =>  $data,
        ]);
    }

    public function assigned_delivery()
    {
        $order_query = Order::with('combined_order')->where('assign_delivery_boy', auth()->id());

        $order_query->where(function ($query) {
            $query->where(function ($q) {
                $q->where('delivery_status', 'pending')
                    ->where('cancel_request', '0');
            })->orWhere(function ($q) {
                $q->where('delivery_status', 'confirmed')
                    ->where('cancel_request', '0');
            });
        });

        $assigned_deliveries = $order_query->paginate(10);
        return response()->json([
            'status' => 200,
            'data' => $assigned_deliveries
        ]);
    }

    public function cancel_delivery()
    {
        $cancelled_deliveries = Order::with('combined_order')->where('assign_delivery_boy', auth()->id())
            ->where('delivery_status', 'cancelled')
            ->paginate(10);

        return response()->json([
            'status' => 200,
            'data' => $cancelled_deliveries
        ]);
    }
    public function completed_delivery()
    {
        // $completed_deliveries = DeliveryHistory::with('order')->where('delivery_boy_id', auth()->id())
        // ->where('delivery_status', 'delivered')
        // ->paginate(10);
        $completed_deliveries = Order::with('combined_order')->where('assign_delivery_boy', auth()->id())
            ->where('delivery_status', 'delivered')
            ->where('cancel_request', '0')
            ->paginate(10);
        return response()->json([
            'status' => 200,
            'data' => $completed_deliveries
        ]);
    }
    public function pending_delivery()
    {
        $pending_deliveries = Order::with('combined_order')->where('assign_delivery_boy', auth()->id())
            ->where('delivery_status', '!=', 'delivered')
            ->where('delivery_status', '!=', 'cancelled')
            ->where('cancel_request', '0')
            ->paginate(10);
        return response()->json([
            'status' => 200,
            'data' => $pending_deliveries
        ]);
    }
    public function picked_up_delivery()
    {
        $pickup_deliveries = Order::with('combined_order')->where('assign_delivery_boy', auth()->id())
            ->where('delivery_status', 'picked_up')
            ->where('cancel_request', '0')
            ->paginate(10);
        return response()->json([
            'status' => 200,
            'data' => $pickup_deliveries
        ]);
    }
    public function on_the_way_delivery()
    {
        $on_the_way_deliveries = Order::with('combined_order')->where('assign_delivery_boy', auth()->id())
            ->where('delivery_status', 'on_the_way')
            ->where('cancel_request', '0')
            ->paginate(10);
        return response()->json([
            'status' => 200,
            'data' => $on_the_way_deliveries
        ]);
    }

    public function update_delivery_status(Request $request)
    {
        // dd($request);
        $update_status = (new OrderController)->update_delivery_status($request);
        return response()->json([
            'success' => false,
            'message' => translate('Delivery status updated!'),
        ]);
    }

    public function cancel_request(Request $request)
    {
        $order = Order::find($request->order_id);
        $order->cancel_request = '1';
        $order->cancel_request_at = date("Y-m-d H:i:s");
        $order->save();

        return response()->json([
            'success' => false,
            'message' => translate('Cancel request submitted successfully!'),
        ]);
    }

    public function total_collection()
    {
        $today_collections = DeliveryHistory::with('order')->where('delivery_boy_id', auth()->id())
            ->where('delivery_status', 'delivered')
            ->where('payment_type', 'cash_on_delivery')
            ->paginate(10);
        return TotalCollectionResource::collection($today_collections);
        // return response()->json([
        //     'status'=> 200,
        //     'data'=> $today_collections
        // ]);
    }

    public function total_earning()
    {
        $total_earnings = DeliveryHistory::with('order')->where('delivery_boy_id', auth()->id())
            ->where('delivery_status', 'delivered')
            ->paginate(10);
        return TotalEarningResource::collection($total_earnings);
    }

    public function store_delivery_history($order)
    {
        $delivery_history = new DeliveryHistory;

        $delivery_history->order_id         = $order->id;
        $delivery_history->delivery_boy_id  = auth()->id();
        $delivery_history->delivery_status  = $order->delivery_status;
        $delivery_history->payment_type     = $order->payment_type;
        if ($order->delivery_status == 'delivered') {
            $delivery_boy = DeliveryBoy::where('user_id', auth()->id())->first();

            if (get_setting('delivery_boy_payment_type') == 'commission') {
                $delivery_history->earning      = get_setting('delivery_boy_commission');
                $delivery_boy->total_earning   += get_setting('delivery_boy_commission');
            }
            if ($order->payment_type == 'cash_on_delivery') {
                $delivery_history->collection    = $order->grand_total;
                $delivery_boy->total_collection += $order->grand_total;
                $order->payment_status           = 'paid';
            }

            $delivery_boy->save();
        }
        $order->delivery_history_date = date("Y-m-d H:i:s");

        $order->save();
        $delivery_history->save();
    }

    public function collection_from_delivery_boy(Request $request)
    {
        $delivery_boy = DeliveryBoy::where('user_id', $request->delivery_boy_id)->first();

        if ($request->payout_amount > $delivery_boy->total_collection) {
            flash(translate('Collection Amount Can Not Be Larger Than Collected Amount'))->error();
            return redirect()->route('delivery-boys.index');
        }

        $delivery_boy->total_collection -= $request->payout_amount;

        if ($delivery_boy->save()) {
            $delivery_boy_collection          = new DeliveryBoyCollection;
            $delivery_boy_collection->user_id = $request->delivery_boy_id;
            $delivery_boy_collection->collection_amount = $request->payout_amount;

            $delivery_boy_collection->save();

            flash(translate('Collection From Delivery Boy Successfully'))->success();
        } else {
            flash(translate('Something went wrong'))->error();
        }

        return redirect()->route('delivery-boys.index');
    }

    public function paid_to_delivery_boy(Request $request)
    {
        $delivery_boy = DeliveryBoy::where('user_id', $request->delivery_boy_id)->first();

        if ($request->paid_amount > $delivery_boy->total_earning) {
            flash(translate('Paid Amount Can Not Be Larger Than Payable Amount'))->error();
            return redirect()->route('delivery-boys.index');
        }

        $delivery_boy->total_earning -= $request->paid_amount;

        if ($delivery_boy->save()) {
            $delivery_boy_payment          = new DeliveryBoyPayment;
            $delivery_boy_payment->user_id = $request->delivery_boy_id;
            $delivery_boy_payment->payment = $request->paid_amount;

            $delivery_boy_payment->save();

            flash(translate('Pay To Delivery Boy Successfully'))->success();
        } else {
            flash(translate('Something went wrong'))->error();
        }

        return redirect()->route('delivery-boys.index');
    }
}
