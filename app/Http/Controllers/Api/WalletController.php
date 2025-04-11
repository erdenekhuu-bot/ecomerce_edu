<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\WalletCollection;
use App\Models\User;
use App\Models\Wallet;

class WalletController extends Controller
{
    public function walletRechargeHistory()
    {
        return new WalletCollection(Wallet::where('user_id', auth('api')->user()->id)->latest()->paginate(12));
    }

    public function wallet_payment_done($payment_data, $payment_details)
    {

        $user = User::find($payment_data['user_id']);

        $wallet = new Wallet;
        $wallet->user_id = $user->id;
        $wallet->amount = $payment_data['amount'];
        $wallet->payment_details = session('transactionId');

        if (strpos($payment_data['payment_method'], 'offline_payment') !== false || $payment_data['payment_method'] == "qpay") {
            
            // save receipt
            if ($payment_data['receipt'] != null) {
                $wallet->reciept = $payment_data['receipt']->store(
                    'uploads/offline_payments'
                );
            }

            // offline payment
            $wallet->approval = 0;
            $wallet->offline_payment = 1;
            
            
            $wallet->type = "Pending";
            if ($payment_data['payment_method'] == "qpay") {
                $wallet->details = $payment_details;
                $wallet->payment_method = "qpay";
                $wallet->order_no = $payment_data['order_no'];
                $wallet->save();
                return redirect('/user/wallet');
            }else{
                $wallet->payment_method = session('manualPaymentMethod')->heading;
                $wallet->details = json_decode($payment_details);
            }
        } else {
            // online payment
            $wallet->approval = 1;
            $wallet->offline_payment = 0;
            $wallet->payment_method = $payment_data['payment_method'];
            $wallet->details = 'Recharge';
            // add balance
            $user->balance = $user->balance + $payment_data['amount'];
            $user->save();
        }

        $wallet->save();
    }

    public function isPaid() {
        
    }
}
