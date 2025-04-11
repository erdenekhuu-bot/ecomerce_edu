<?php

namespace App\Addons\Multivendor\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Shop;
use App\Models\User;
use App\Notifications\EmailVerificationNotification;
use App\Notifications\SellerApprovedNotification;
use Artisan;
use Cache;
use Hash;
use Illuminate\Http\Request;

class SellerController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:show_seller_products'])->only('seller_products');
        $this->middleware(['permission:show_seller_orders'])->only('seller_orders');
        $this->middleware(['permission:show_sellers'])->only('all_sellers');
    }

    // Admin Panel
    public function all_sellers(Request $request)
    {
        $admin = User::where('user_type', 'admin')->first();

        $sort_search = null;
        $approved = null;
        $shops = Shop::withCount('products')->with(['user', 'seller_package'])->where('id', '!=', $admin->shop_id);

        if ($request->has('approved_status') && $request->approved_status != null) {
            $approved = $request->approved_status;
            $shops = $shops->where('approval', $approved);
        }

        if ($request->has('search') && $request->search != null) {
            $sort_search = $request->search;
            $shops = $shops->where('name', 'like', '%' . $sort_search . '%')
                ->orWhere('phone', 'like', '%' . $sort_search . '%')
                ->orWhereHas('user', function ($query) use ($sort_search) {
                    $query->where('name', 'like', '%' . $sort_search . '%');
                });
        }

        $shops = $shops->latest()->paginate(15);
        return view('addon:multivendor::admin.sellers.index', compact('shops', 'sort_search', 'approved'));
    }

    public function seller_create()
    {
        return view('addon:multivendor::admin.sellers.create');
    }

    public function seller_store(Request $request)
    {
        if (User::where('email', $request->email)->first() != null) {
            flash(translate('Email already exists!'))->error();
            return back();
        }
        if ($request->password == $request->confirm_password) {
            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->user_type = "seller";
            $user->password = Hash::make($request->password);

            if ($user->save()) {
                if (get_setting('email_verification') != 1) {
                    $user->email_verified_at = date('Y-m-d H:m:s');
                } else {
                    $user->notify(new EmailVerificationNotification());
                }
                $user->save();

                $shop = new Shop;
                $shop->user_id = $user->id;
                $shop->name = $request->shop_name;
                $shop->slug = preg_replace('/\s+/', '-', $request->shop_name) . '-' . $shop->id;
                $shop->save();

                flash(translate('Seller has been added successfully'))->success();
                return redirect()->route('admin.all_sellers');
            }
            flash(translate('Something went wrong'))->error();
            return back();
        } else {
            flash("Password and confirm password didn't match")->warning();
            return back();
        }
    }



    public function seller_edit($id)
    {
        $seller = User::findOrFail(decrypt($id));
        return view('addon:multivendor::admin.sellers.edit', compact('seller'));
    }

    public function seller_update(Request $request)
    {
        $user = User::findOrFail($request->seller_id);
        $user->name = $request->name;
        $user->email = $request->email;
        if (User::where('id', '!=', $user->id)->where('email', $request->email)->first() == null) {
            if (strlen($request->password) > 0) {
                $user->password = Hash::make($request->password);
            }
            if ($user->save()) {
                flash(translate('Seller has been updated successfully'))->success();
                return redirect()->route('admin.all_sellers');
            }

            flash(translate('Something went wrong'))->error();
            return back();
        } else {
            flash(translate('Email Already Exists!'))->error();
            return back();
        }
    }

    public function seller_destroy($id)
    {
        $user = User::findOrFail($id);

        if (!is_null($user->products)) {
            foreach ($user->products as $product) {
                $product->product_translations()->delete();
                $product->variations()->delete();
                $product->variation_combinations()->delete();
                $product->reviews()->delete();
                $product->product_categories()->delete();
                $product->carts()->delete();
                $product->offers()->delete();
                $product->wishlists()->delete();
                $product->attributes()->delete();
                $product->attribute_values()->delete();
                $product->taxes()->delete();

                $product->delete();
            }
        }

        Shop::where('user_id', $user->id)->delete();

        if (User::destroy($id)) {
            flash(translate('Seller has been deleted successfully'))->success();
            return redirect()->route('admin.all_sellers');
        } else {
            flash(translate('Something went wrong'))->error();
            return back();
        }
    }


    public function update_seller_approval(Request $request)
    {
        $shop = Shop::findOrFail($request->id);
        $shop->approval = $request->status;

        cache_clear();

        if ($shop->save()) {
            if ($shop->approval == 1) {
                $shop->user->notify(new SellerApprovedNotification($shop));
            }
            return 1;
        }
        return 0;
    }

    public function update_shop_publish(Request $request)
    {
        $shop = Shop::findOrFail($request->id);
        $shop->published = $request->status;

        cache_clear();

        if ($shop->save()) {
            return 1;
        }
        return 0;
    }


    public function profile_modal(Request $request)
    {
        $seller = User::findOrFail($request->id);
        return view('addon:multivendor::admin.sellers.profile_modal', compact('seller'));
    }

    public function payment_modal(Request $request)
    {
        $seller = User::findOrFail($request->id);
        return view('addon:multivendor::admin.seller_payouts.payment_modal', compact('seller'));
    }


    public function seller_products(Request $request, $product_type)
    {
        $col_name = null;
        $query = null;
        $sort_search = null;
        $shop_id = null;

        $admin = User::where('user_type', 'admin')->first();
        $products = Product::orderBy('created_at', 'desc')->where('shop_id', '!=', $admin->shop->id);

        if ($request->shop_id != null) {
            $shop_id = $request->shop_id;
            $products = $products->where('shop_id', $shop_id);
        }
        if ($product_type == 'physical') {
            $products = $products->where('digital', 0);
        } else {
            $products = $products->where('digital', 1);
        }
        if ($request->search != null) {
            $products = $products->where('name', 'like', '%' . $request->search . '%');
            $sort_search = $request->search;
        }
        if ($request->type != null) {
            $var = explode(",", $request->type);
            $col_name = $var[0];
            $query = $var[1];
            $products = $products->orderBy($col_name, $query);
            $sort_type = $request->type;
        }


        $products = $products->paginate(15);
        $type = 'All';

        return view('addon:multivendor::admin.seller_products', compact('products', 'type', 'col_name', 'query', 'sort_search', 'shop_id'));
    }

    public function seller_orders(Request $request)
    {

        $payment_status = null;
        $delivery_status = null;
        $sort_search = null;
        $shop_id = null;

        $admin = User::where('user_type', 'admin')->first();
        $orders = Order::with(['combined_order', 'shop'])
            ->where('shop_id', '!=', $admin->shop_id);
        $shops = Shop::where('id', '!=', $admin->shop_id)->get();

        if ($request->shop_id != null) {
            $shop_id = $request->shop_id;
            $orders = $orders->where('shop_id', $shop_id);
        }
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
        return view('addon:multivendor::admin.seller_orders', compact('shops', 'orders', 'payment_status', 'delivery_status', 'sort_search', 'shop_id'));
    }

    public function seller_order_show($id)
    {
        $order = Order::with(['orderDetails.product', 'orderDetails.variation.combinations'])->findOrFail($id);
        $order_shipping_address = json_decode($order->shipping_address);
        $delivery_boys = User::leftJoin('addresses', 'users.id', '=', 'addresses.user_id')->where('user_type', 'delivery_boy')->where('city', $order_shipping_address->city)->where('banned', 0)->select('users.id', 'users.name')->get();
        return view('addon:multivendor::admin.seller_order_show', compact('order', 'delivery_boys'));
    }
    public function seller_verification_form()
    {
        return view('backend.seller_verification_form.index');
    }

    public function seller_verification_form_update(Request $request)
    {
        $form = array();
        $select_types = ['select', 'multi_select', 'radio'];
        $j = 0;
        if ($request->type) {
            for ($i = 0; $i < count($request->type); $i++) {
                $item['type'] = $request->type[$i];
                $item['label'] = $request->label[$i];
                if (in_array($request->type[$i], $select_types)) {
                    $item['options'] = json_encode($request['options_' . $request->option[$j]]);
                    $j++;
                }
                array_push($form, $item);
            }
        }
        $settings = Setting::where('type', 'verification_form')->first();
        $settings->value = json_encode($form);
        if ($settings->save()) {
            Artisan::call('cache:clear');

            flash(translate("Verification form updated successfully"))->success();
            return back();
        }
    }

    public function show_verification_request($id)
    {
        $shop = Shop::findOrFail($id);
        return view('addon:multivendor::admin.sellers.verification', compact('shop'));
    }

    public function approve_seller($id)
    {
        $shop = Shop::findOrFail($id);
        $shop->verification_status = 1;
        if ($shop->save()) {
            Cache::forget('verified_sellers_id');
            flash(translate('Seller has been approved successfully'))->success();
            return redirect()->route('admin.all_sellers');
        }
        flash(translate('Something went wrong'))->error();
        return back();
    }

    public function reject_seller($id)
    {
        $shop = Shop::findOrFail($id);
        $shop->verification_status = 0;
        $shop->verification_info = null;
        if ($shop->save()) {
            Cache::forget('verified_sellers_id');
            flash(translate('Seller verification request has been rejected successfully'))->success();
            return redirect()->route('admin.all_sellers');
        }
        flash(translate('Something went wrong'))->error();
        return back();
    }

    public function bulk_seller_delete(Request $request)
    {
        if ($request->id) {
            foreach ($request->id as $seller_id) {
                $this->seller_destroy($seller_id);
            }
        }

        return 1;
    }
}
