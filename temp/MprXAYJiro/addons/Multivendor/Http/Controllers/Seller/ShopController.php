<?php

namespace App\Addons\Multivendor\Http\Controllers\Seller;

use App\Addons\Multivendor\Http\Services\ShopService;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Shop;
use App\Models\User;
use App\Notifications\DB\SellerVerificationNotification;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shop = Shop::with('products')->where('user_id', auth()->user()->id)->first();
        return view('addon:multivendor::seller.shop_settings', compact('shop'));
    }

    public function update(Request $request, $id)
    {
        if ($id != auth()->user()->shop_id) {
            abort(403);
        }
        (new ShopService)->update($request, $id);

        return back();
    }
    public function verify_form()
    {
        if (auth()->user()->shop->verification_info == null) {
            $shop = auth()->user()->shop;
            return view('addon:multivendor::seller.verify_form', compact('shop'));
        } else {
            flash(translate('Sorry! You have already sent a verification request.'))->warning();
            return back();
        }
    }

    public function verify_form_store(Request $request)
    {
        $data = array();
        $i = 0;
        foreach (json_decode(Setting::where('type', 'verification_form')->first()->value) as $key => $element) {
            $item = array();
            if ($element->type == 'text') {
                $item['type'] = 'text';
                $item['label'] = $element->label;
                $item['value'] = $request['element_' . $i];
            } elseif ($element->type == 'select' || $element->type == 'radio') {
                $item['type'] = 'select';
                $item['label'] = $element->label;
                $item['value'] = $request['element_' . $i];
            } elseif ($element->type == 'multi_select') {
                $item['type'] = 'multi_select';
                $item['label'] = $element->label;
                $item['value'] = json_encode($request['element_' . $i]);
            } elseif ($element->type == 'file') {
                $item['type'] = 'file';
                $item['label'] = $element->label;
                $item['value'] = $request['element_' . $i]->store('uploads/verification_form');
            }
            array_push($data, $item);
            $i++;
        }
        $shop = auth()->user()->shop;
        $shop->verification_info = json_encode($data);
        //Database Notification
        $admin = User::where('user_type', 'admin')->first();
        try {
            // db notification
            $admin->notify(new SellerVerificationNotification($shop));
        } catch (\Exception $e) {
        }
        if ($shop->save()) {
            flash(translate('Your shop verification request has been submitted successfully!'))->success();
            return redirect()->route('seller.dashboard');
        }

        flash(translate('Sorry! Something went wrong.'))->error();
        return back();
    }
}
