<?php

namespace App\Addons\Refund\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CommissionHistory;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderUpdate;
use App\Models\RefundRequest;
use App\Models\RefundRequestItem;
use App\Models\User;
use App\Models\Wallet;
use Auth;

class RefundRequestController extends Controller
{
    public function __construct()
    {
        // $this->middleware(['permission:show_commission_log'])->only('commission_history');
    }

    public function refund_settings(){
        return view('addon:refund::admin.refund_settings');
    }

    public function refund_requests(Request $request){
        $sort_search = null;
        $shop_id = null;
        $refund_requests = RefundRequest::latest();
        if(!addon_is_activated('multi_vendor')){
            $admin = User::where('user_type','admin')->first(); 
            $refund_requests = $refund_requests->where('shop_id',$admin->shop_id);
        }
        if ($request->shop_id) {
            $shop_id = $request->shop_id;
            $refund_requests = $refund_requests->where('shop_id', $shop_id);
        }
        if ($request->has('search') && $request->search) {
            $sort_search = $request->search;

            $refund_requests = $refund_requests->whereHas('order', function ($query) use ($sort_search) {
                $query->WhereHas('combined_order', function ($query) use ($sort_search) {
                    $query->where('code', 'like', '%' . $sort_search . '%');
                });
            });
        }

        $refund_requests = $refund_requests->paginate(10);
        
        return view('addon:refund::admin.refund_request.index',compact('refund_requests','shop_id','sort_search'));
    }

    public function refund_request_create($id){
        $order = Order::where('id',$id)->first();
        return view('addon:refund::admin.refund_request.create',compact('order'));
    }

    public function refund_request_store(Request $request){

        if($request->order_detail_ids != null){
            $refund_product_quantity = 0;
            $order = Order::findOrFail($request->order_id);
            $order_product_quantity = $order->orderDetails->sum('quantity');

            $refund_request =  new RefundRequest;
            $refund_request->order_id = $order->id;
            $refund_request->user_id = $order->user_id;
            $refund_request->shop_id = $order->shop_id;
            $refund_request->admin_approval = 1;
            $refund_request->amount = $request->refund_amount;
            $refund_request->reasons = $request->refund_reasons != null ? json_encode($request->refund_reasons) : '[]';
            $refund_request->refund_note = $request->refund_note;
            $refund_request->attachments = $request->attachments;
            $refund_request->payment_type = $request->payment_type;
            $refund_request->save();

            foreach($order->orderDetails as $key => $orderDetail){
               if(in_array($orderDetail->id,$request->order_detail_ids)){

                    $refund_request_item =  new RefundRequestItem;
                    $refund_request_item->refund_request_id = $refund_request->id;
                    $refund_request_item->order_detail_id = $orderDetail->id;
                    $refund_request_item->quantity = $request['quantity_for_'.$orderDetail->id];
                    $refund_request_item->save();

                    $refund_product_quantity += $request['quantity_for_'.$orderDetail->id];
               }
            }
            if($request->payment_type == 'wallet'){
                $user = $order->user;
                $user->balance += $request->refund_amount;
                $user->save();

                $wallet = new Wallet;
                $wallet->user_id = $user->id;
                $wallet->amount = $request->refund_amount;
                $wallet->details = 'Refund for Order'.$order->combined_order->code;
                $wallet->save();
            }
            

            $order->refund_status = $refund_product_quantity < $order_product_quantity ? "partially_refunded" : "fully_refunded";
            $order->refund_amount = $request->refund_amount;
            $order->save();

            OrderUpdate::create([
                'order_id' => $order->id,
                'user_id' => auth()->user()->id,
                'note' => 'Refund request created.',
            ]);
            
            flash(translate('Refunded Successfully.'))->success();
            return redirect()->route('orders.show', $order->id);
        }
        else{
            flash(translate('Please Select an item first.'))->warning();
            return back();
        }
    }

    public function refund_request_view_detials(Request $request){
        $refund_request = RefundRequest::findOrFail($request->id);
        return view('addon:refund::refund_request_detail_modal', compact('refund_request'));
    }

    public function refund_request_accept(Request $request){
        $refund_request = RefundRequest::findOrFail($request->refund_request_id);
        $order = Order::findOrFail($refund_request->order_id);

        // If seller Approved the refund request, deduct amount from the seller balance.
        if ($refund_request->seller_approval == 1) {
            $seller = $order->shop;
            if ($seller != null) {
                $seller->current_balance -= $request->amount;
                $seller->save();

                $commission = new CommissionHistory;
                $commission->order_id = $order->id;
                $commission->shop_id = $order->shop->id;
                $commission->seller_earning = $request->amount;
                $commission->type = 'Deducted';
                $commission->details = format_price($request->amount).' is Deducted for Order Refund.';
                $commission->save();
            }
            
        }

        $refund_request->admin_approval = 1;
        $refund_request->amount = $request->amount;
        $refund_request->payment_type = $request->button;
        $refund_request->save();

        // Add refund amount to the customer wallet and balance
        if($request->button == 'wallet'){
            $user = $refund_request->user;
            $user->balance += $request->amount;
            $user->save();

            $wallet = new Wallet;
            $wallet->user_id = $user->id;
            $wallet->amount = $request->amount;
            $wallet->details = 'Refund for Order '.$order->combined_order->code;
            $wallet->save();
        }

        // Fully Refunded/ Partially Refunded Check
        $refund_product_quantity = 0;
        foreach($refund_request->refundRequestItems as $key => $refundRequestItem){
            $refund_product_quantity += $refundRequestItem->quantity;
        }
        $order->refund_status = $refund_product_quantity < $order->orderDetails->sum('quantity') ? "partially_refunded" : "fully_refunded";
        $order->refund_amount = $request->amount;
        $order->save();

        OrderUpdate::create([
            'order_id' => $order->id,
            'user_id' => auth()->user()->id,
            'note' => 'Refund request approved.',
        ]);

        flash(translate('Refunded Successfully.'))->success();
        return back();
    }
    
    public function refund_request_reject($id){
        $refund_request = RefundRequest::findOrFail($id);
        $refund_request->admin_approval = 2;
        $refund_request->save();

        OrderUpdate::create([
            'order_id' => $refund_request->order->id,
            'user_id' => auth()->user()->id,
            'note' => 'Refund request declined.',
        ]);

        flash(translate('Refunded Rejuest Rejected.'))->success();
        return back();
    }
}
