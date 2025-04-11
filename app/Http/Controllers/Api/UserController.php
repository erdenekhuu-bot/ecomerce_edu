<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ProductCollection;
use App\Http\Resources\UserCollection;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Upload;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Membership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function dashboard()
    {
        $total_order_products = OrderDetail::distinct()
            ->whereIn('order_id', Order::where('user_id', auth('api')->user()->id)->pluck('id')->toArray());

        $recent_purchased_products = Product::whereIn('id', $total_order_products->pluck('product_id')->toArray())->limit(10)->get();
        $last_recharge = Wallet::where('user_id', auth('api')->user()->id)->latest()->first();

        $percent = 0;
        $user = auth('api')->user();
        if ($user) {
            $total_order_price = $user->orders()->where('payment_status','paid')->sum('grand_total');
            $memberships = Membership::orderby('value', 'ASC')->get();
            foreach ($memberships as $key => $membership) {
                if ($membership->value <= $total_order_price) {
                    $percent = $membership->percent;
                }
            }
        }

        return response()->json([
            'success' => true,
            'last_recharge' => [
                'amount' => $last_recharge ? $last_recharge->amount : 0.00,
                'date' => $last_recharge ? $last_recharge->created_at->format('d.m.Y') : '',
            ],
            'sale_percent' => $percent,
            'total_order_products' => $total_order_products->count('product_variation_id'),
            'recent_purchased_products' => new ProductCollection($recent_purchased_products)
        ]);
    }

    public function notification()
    {
        return response()->json([
            'success' => true,
            'notifications' => auth()->user()->unreadNotifications->take(10),
            'data' => auth()->user()->notifications()->paginate(15),
        ]);
    }
    public function all_notification()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return response()->json([
            'success' => true,
            'notifications' => auth()->user()->unreadNotifications->take(10),
            'data' => auth()->user()->notifications()->paginate(15),
        ]);
    }

    public function info()
    {
        $user = User::find(auth('api')->user()->id);

        return response()->json([
            'success' => true,
            'user' => new UserCollection($user),
            'followed_shops' => $user->followed_shops->pluck('id')->toArray()
        ]);
    }

    public function updateInfo(Request $request)
    {
        $user = User::find(auth('api')->user()->id);
        // if (Hash::check($request->oldPassword, $user->password)) {

        if ($request->hasFile('avatar')) {
            $upload = new Upload;
            $upload->file_original_name = null;
            $arr = explode('.', $request->file('avatar')->getClientOriginalName());

            for ($i = 0; $i < count($arr) - 1; $i++) {
                if ($i == 0) {
                    $upload->file_original_name .= $arr[$i];
                } else {
                    $upload->file_original_name .= "." . $arr[$i];
                }
            }

            $upload->file_name = $request->file('avatar')->store('uploads/all');
            $upload->user_id = $user->id;
            $upload->extension = $request->file('avatar')->getClientOriginalExtension();
            $upload->type = 'image';
            $upload->file_size = $request->file('avatar')->getSize();
            $upload->save();

            $user->update([
                'avatar' => $upload->id,
            ]);
        }
        $user->update([
            'name' => $request->name,
            // 'phone' => $request->phone
        ]);

        if ($request->password) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }
        $user->save();

        return response()->json([
            'success' => true,
            'message' => translate('Profile information has been updated successfully'),
            'user' => new UserCollection($user)
        ]);
        // } else {
        //     return response()->json([
        //         'success' => false,
        //         'message' => translate('The old password you have entered is incorrect')
        //     ]);
        // }
    }

    public function userList() {
        $user = User::find(auth('api')->user()->id);
        
        $users = [];
        if ($user->user_type == "staff") {
            $users = User::where('user_type', 'customer')->select('id', 'name')->get();
        }
        
        return [
            "success" => true,
            "message" => "",
            "data" => $users,
        ];
    }
}