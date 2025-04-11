<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\CombinedOrder;
use App\Models\CustomerPackage;
use App\Models\SellerPackage;
use Illuminate\Http\Request;
use MyFatoorah\Library\MyFatoorah;
use MyFatoorah\Library\API\Payment\MyFatoorahPayment;
use MyFatoorah\Library\API\Payment\MyFatoorahPaymentEmbedded;
use MyFatoorah\Library\API\Payment\MyFatoorahPaymentStatus;
use MyFatoorah\Library\PaymentMyfatoorahApiV2;
use Session;
use Redirect;

class MyfatoorahPaymentController extends Controller
{
    /**
     * @var array
     */
    public $mfConfig = [];

    /**
     * Initiate MyFatoorah Configuration
     */
    public function __construct() {
        $this->mfConfig = [
            'apiKey'      => env('MYFATOORAH_TOKEN'),
            'isTest'      => get_setting('myfatoorah_sandbox') == 1 ? true : false,
            'countryCode' => env('MYFATOORAH_COUNTRY_ISO'),
        ];
    }

    /**
     * Create MyFatoorah invoice
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $user = auth()->user();
        $payment_type = session('payment_type');

        switch ($payment_type) {
            case 'cart_payment':
                $amount = CombinedOrder::where('code', session('order_code'))->first()->grand_total;
                $CustomerReference =  $payment_type . '-' . session('order_code') . '-' . $user->id;

                break;
            case 'repayment':
                $amount = CombinedOrder::where('code', session('order_code'))->first()->grand_total;
                $CustomerReference =  $payment_type . '-' . session('order_code') . '-' . $user->id;

                break;
            case 'wallet_payment':
                $amount = session('amount');
                $CustomerReference = $payment_type . '-' . $amount . '-' . $user->id;

                break;
            case 'seller_package_payment':
                $amount = session('amount');
                $CustomerReference =  $payment_type . '-' . $amount . '-' . $user->id;

                break;
            default:
                $CustomerReference =  $payment_type . '-' . $user->id;
                $amount = 0;
        }

        $currency_code = \App\Models\Currency::find(get_setting('system_default_currency'))->code;
        $paymentMethodId = session('amount');
        $callbackURL = route('myfatoorah.callback');

        $data = [
            'InvoiceValue'       => $amount,
            'DisplayCurrencyIso' => $currency_code,
            'CallBackUrl'        => $callbackURL,
            'ErrorUrl'           => $callbackURL,
            'paymentMethodId'    => $paymentMethodId,
            'CustomerName'       => $user->name,
            'CustomerEmail'      => $user->email ?? 'test@test.com',
            'MobileCountryCode'  => '+965',
            'CustomerMobile'     => '12345678',
            'Language'           => 'en',
            'CustomerReference'  => $CustomerReference,
        ];
        
        try {
            $mfObj   = new MyFatoorahPayment($this->mfConfig);
            $data = $mfObj->getInvoiceURL($data, $paymentMethodId);

            if ($data['invoiceId']) {
                $checkoutUrl = $data['invoiceURL'];
                return Redirect::to($checkoutUrl);
            }
        } catch (\Exception $e) {
            return (new PaymentController)->payment_failed();
        }
    }

    /**
     * Get MyFatoorah payment information
     * 
     * @return \Illuminate\Http\Response
     */

    public function callback()
    {
        try {
            $mfObj   = new MyFatoorahPaymentStatus($this->mfConfig);
            $payment_detalis = $mfObj->getPaymentStatus(request('paymentId'), 'PaymentId');

            if ($payment_detalis->InvoiceStatus == 'Paid') {

                return (new PaymentController)->payment_success($payment_detalis);

            } else {
                return (new PaymentController)->payment_failed();
            }
        } catch (\Exception $e) {
            return (new PaymentController)->payment_failed();
        }
    }
}
