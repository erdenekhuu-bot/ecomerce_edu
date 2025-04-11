<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\CombinedOrder;
use App\Models\User;
use Illuminate\Http\Request;
 
class PayherePaymentController extends Controller
{
    public function index(){
        // dd(get_setting('payhere_sandbox') == 1 ? 'https://sandbox.payhere.lk/pay/checkout' : 'https://www.payhere.lk/pay/checkout');
        $user = User::find(session('user_id'));

        $payment_type = session('payment_type');
        $data['user_id'] = $user->id;
        $data['first_name'] = $user->name;
        $data['last_name'] = 'X';
        $data['phone'] = '123456789';
        $data['email'] = $user->email;
        $data['address'] = 'dummy address';
        $data['city'] = 'Colombo';
        $data['payment_type'] = $payment_type;

        switch ($payment_type) {
            case 'cart_payment':
                $combined_order = CombinedOrder::where('code', session('order_code'))->first();
                $data['order_id'] = $combined_order->id;
                $data['amount'] = $combined_order->grand_total;
                $data['first_name'] = json_decode($combined_order->shipping_address)->name;
                $data['phone'] = json_decode($combined_order->shipping_address)->phone;
                $data['email'] = json_decode($combined_order->shipping_address)->email;
                $data['address'] = json_decode($combined_order->shipping_address)->address;
                $data['city'] = json_decode($combined_order->shipping_address)->city;
                $data['custom_1'] = $payment_type . '-' . $user->id . '-' . $combined_order->code;
                $data['hash_value'] = static::getHash($data['order_id'], $data['amount']);
                return view('frontend.payment.payhere', $data);
            case 'repayment':
                $combined_order = CombinedOrder::where('code', session('order_code'))->first();
                $data['order_id'] = $combined_order->id;
                $data['amount'] = $combined_order->grand_total;
                $data['first_name'] = json_decode($combined_order->shipping_address)->name;
                $data['phone'] = json_decode($combined_order->shipping_address)->phone;
                $data['email'] = json_decode($combined_order->shipping_address)->email;
                $data['address'] = json_decode($combined_order->shipping_address)->address;
                $data['city'] = json_decode($combined_order->shipping_address)->city;
                $data['custom_1'] = $payment_type . '-' . $user->id . '-' . $combined_order->code;
                $data['hash_value'] = static::getHash($data['order_id'], $data['amount']);

                return view('frontend.payment.payhere', $data);

            case 'wallet_payment':
                $data['order_id'] = rand(100000, 999999);
                $data['amount'] = session('amount');
                $data['custom_1'] = $payment_type . '-' . $user->id . '-' . rand(0, 100000);
                $data['hash_value'] = static::getHash($data['order_id'], $data['amount']);

                return view('frontend.payment.payhere', $data);

            case 'seller_package_payment':
                $data['order_id'] = rand(100000, 999999);
                $data['package_id'] = session('seller_package_id');
                $data['amount'] = session('amount');
                $data['custom_1'] = $payment_type . '-' . $user->id . '-' . session('seller_package_id');
                $data['hash_value'] = static::getHash($data['order_id'], $data['amount']);

                return view('frontend.payment.payhere', $data);

            default:
                $amount = 0;
        }
    }

    public function payhere_return () {
        return (new PaymentController)->payment_success();
    }

    public function payhere_cancel  () {
        return (new PaymentController)->payment_failed();

    }

    public function payhere_notify  () {
        $merchant_id = $_POST['merchant_id'];
        $order_id = $_POST['order_id'];
        $payhere_amount = $_POST['payhere_amount'];
        $payhere_currency = $_POST['payhere_currency'];
        $status_code = $_POST['status_code'];
        $md5sig = $_POST['md5sig'];

        $merchant_secret = env('PAYHERE_SECRET'); 

        $local_md5sig = strtoupper(md5($merchant_id . $order_id . $payhere_amount . $payhere_currency . $status_code . strtoupper(md5($merchant_secret))));

        if (($local_md5sig === $md5sig) and ($status_code == 2)) {
            //custom_1 will have order_id
            self::setSession($_POST);
            return (new PaymentController)->payment_success($_POST);
        }
        return (new PaymentController)->payment_failed();
    }

    public static function getHash($order_id, $payhere_amount)
    {
        $hash = strtoupper(
            md5(
                env('PAYHERE_MERCHANT_ID').
                $order_id.
                number_format($payhere_amount, 2, '.', '').
                env('PAYHERE_CURRENCY').
                strtoupper(md5(env('PAYHERE_SECRET'))) 
            )
        );
        return $hash;
    }

    public static function setSession($response){

        $payment_type = explode("-", $response['custom_1']);

        $payment_type[1] != null? auth()->login(User::findOrFail($payment_type[1])) : '';
        session()->put('user_id', $payment_type[1]);
        session()->put('payment_type', $payment_type[0]);
        session()->put('amount', ($response['amount']));
        session()->put('transactionId', $response['transactionId']);
        session()->put('payment_method', 'payhere');

        if($payment_type[0] == 'wallet_payment'){
            session()->put('redirect_to', "/user/dashboard");
        }

        switch ($payment_type[0]) {
            case 'cart_payment':
                session()->put('redirect_to', "/checkout");
                session()->put('order_code', "$payment_type[2]-$payment_type[3]");

                break;
            case 'repayment':
                session()->put('redirect_to', "user/purchase-history/$payment_type[2]-$payment_type[3]");
                session()->put('order_code', "$payment_type[2]-$payment_type[3]");

                break;
            case 'wallet_payment':
                session()->put('redirect_to', "/user/dashboard");
            case 'seller_package_payment':
                session()->put('seller_package_id', $payment_type[2]);

                break;
            default:
            session()->put('redirect_to', "/");
        }

    }

}
