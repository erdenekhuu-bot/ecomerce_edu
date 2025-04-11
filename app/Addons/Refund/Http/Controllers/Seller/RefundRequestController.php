<?php

namespace App\Addons\Refund\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
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
    public function refund_requests(Request $request){
        $sort_search = null;
        $refund_requests = RefundRequest::latest()->where('shop_id', Auth::user()->shop_id);
        if ($request->has('search') && $request->search) {
            $sort_search = $request->search;

            $refund_requests = $refund_requests->whereHas('order', function ($query) use ($sort_search) {
                $query->WhereHas('combined_order', function ($query) use ($sort_search) {
                    $query->where('code', 'like', '%' . $sort_search . '%');
                });
            });
        }

        $refund_requests = $refund_requests->paginate(10);
        
        return view('addon:refund::seller.refund_request.index',compact('refund_requests','sort_search'));
    }

    public function refund_request_create($id){
        $order = Order::where('id',$id)->first();
        if($order->shop_id != auth()->user()->shop_id){
            abort(403);
        }
        return view('addon:refund::seller.refund_request.create',compact('order'));
    }

    public function refund_request_store(Request $request){
        $order = Order::findOrFail($request->order_id);
        if($order->shop_id != auth()->user()->shop_id){
            abort(403);
        }
        if($request->order_detail_ids != null){
            $refund_request =  new RefundRequest;
            $refund_request->order_id = $order->id;
            $refund_request->user_id = $order->user_id;
            $refund_request->shop_id = $order->shop_id;
            $refund_request->seller_approval = 1;
            $refund_request->amount = $request->refund_amount;
            $refund_request->reasons = $request->refund_reasons != null ? json_encode($request->refund_reasons) : '[]';
            $refund_request->refund_note = $request->refund_note;
            $refund_request->attachments = $request->attachments;
            $refund_request->save();

            foreach($order->orderDetails as $key => $orderDetail){
               if(in_array($orderDetail->id,$request->order_detail_ids)){
                    $refund_request_item =  new RefundRequestItem;
                    $refund_request_item->refund_request_id = $refund_request->id;
                    $refund_request_item->order_detail_id = $orderDetail->id;
                    $refund_request_item->quantity = $request['quantity_for_'.$orderDetail->id];
                    $refund_request_item->save();
               }
            }

            OrderUpdate::create([
                'order_id' => $order->id,
                'user_id' => auth()->user()->id,
                'note' => 'Refund request created.',
            ]);
            
            flash(translate('Refunded Successfully.'))->success();
            return redirect()->route('seller.orders_show', $order->id);
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

    public function refund_request_accept($id){
        $refund_request = RefundRequest::findOrFail($id);
        if($refund_request->shop_id != auth()->user()->shop_id){
            abort(403);
        }
        $refund_request->seller_approval = 1;
        $refund_request->save();

        OrderUpdate::create([
            'order_id' => $refund_request->order->id,
            'user_id' => auth()->user()->id,
            'note' => 'Refund request approved.',
        ]);

        flash(translate('Refunded Rejuest Accepted.'))->success();
        return back();
    }

    public function refund_request_reject($id){
        $refund_request = RefundRequest::findOrFail($id);
        if($refund_request->shop_id != auth()->user()->shop_id){
            abort(403);
        }
        $refund_request->seller_approval = 2;
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
