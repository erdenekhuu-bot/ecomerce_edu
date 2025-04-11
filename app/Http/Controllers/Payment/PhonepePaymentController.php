<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\CombinedOrder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class PhonepePaymentController extends Controller
{
    public function index()
    {

        // dd(session()->all());
        
        $user = User::find(session('user_id'));

        $payment_type = session('payment_type');
        $combined_order = CombinedOrder::where('code', session('order_code'))->first();
        switch ($payment_type) {
            case 'cart_payment':
                $title = translate('Cart payment');
                $amount = $combined_order->grand_total;
                $merchantTransactionId = $payment_type . '-' . $user->id . '-' . $combined_order->code;
                $merchantUserId = $user->id;

                break;
            case 'repayment':
                $title = translate('repayment');
                $amount = $combined_order->grand_total;
                $merchantTransactionId = $payment_type . '-' . $user->id . '-' . $combined_order->code;
                $merchantUserId = $user->id;

                break;
            case 'wallet_payment':
                $title = translate('Wallet payment');
                $amount = session('amount');
                $merchantTransactionId = $payment_type . '-' . $user->id . '-' . rand(0, 100000);
                $merchantUserId = $user->id;

                break;
            case 'seller_package_payment':
                $title = translate('Seller package payment');
                $amount = session('amount');
                $merchantTransactionId = $payment_type . '-' . $user->id . '-' . session('seller_package_id');
                $merchantUserId = $user->id;

                break;
            default:
                $title = '';
                $amount = 0;
                $merchantTransactionId = $payment_type . '-' . $user->id . '-' . rand(0, 100000);
                $merchantUserId = $user->id;
        }

        $merchantId = env('PHONEPE_MERCHANT_ID');
        $salt_key = env('PHONEPE_SALT_KEY');
        $salt_index = env('PHONEPE_SALT_INDEX');


        $base_url = (get_setting('phonepe_sandbox') == 1) ? "https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/pay" : "https://api.phonepe.com/apis/hermes/pg/v1/pay";

        $post_field = [
            'merchantId' => $merchantId,
            'merchantTransactionId' => $merchantTransactionId,
            'merchantUserId' => $merchantUserId,
            'amount' => $amount * 100,
            'redirectUrl' => route('phonepe.redirect'),
            'redirectMode' => 'POST',
            'callbackUrl' =>  route('phonepe.callback'),
            'mobileNumber' =>  $user->phone ? $user->phone : "9999999999",
            'param1'=>session('redirect_to'),
            "paymentInstrument" => [
                "type" => "PAY_PAGE",
            ],
        ];

        // dd($post_field);
        $payload = base64_encode(json_encode($post_field));

        $hashedkey =  hash('sha256', $payload . "/pg/v1/pay" . $salt_key) . '###' . $salt_index;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $base_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'X-VERIFY: ' . $hashedkey . '',
            'accept: application/json',
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "\n{\n  \"request\": \"$payload\"\n}\n");

        $response = curl_exec($ch);
        curl_close($ch);
        $res = (json_decode($response));

        return Redirect::to($res->data->instrumentResponse->redirectInfo->url);

    }

    public function phonepe_redirecturl(Request $request)
    {

        $response = $request->all();
        self::setSession($response);
        if ($response['code']  == 'PAYMENT_SUCCESS') {
            return (new PaymentController)->payment_success($response);
        }
        return (new PaymentController)->payment_failed();
    }

    public function phonepe_callbackUrl(Request $request)
    {
        $payment_type = explode("-", $request['transactionId']);
        auth()->login(User::findOrFail($payment_type[1]));
        $response = $request->all();

        if ($response['code']  == 'PAYMENT_SUCCESS') {
            return (new PaymentController)->payment_success($response);
        }
        return (new PaymentController)->payment_failed();
    }

    public static function setSession($response){

        $payment_type = explode("-", $response['transactionId']);

        $payment_type[1] != null? auth()->login(User::findOrFail($payment_type[1])) : '';
        session()->put('user_id', $payment_type[1]);
        session()->put('payment_type', $payment_type[0]);
        session()->put('amount', ($response['amount']/100));
        session()->put('transactionId', $response['transactionId']);
        session()->put('payment_method', 'phonepe');

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