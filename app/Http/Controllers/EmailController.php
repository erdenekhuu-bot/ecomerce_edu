<?php

namespace App\Http\Controllers;

use App\Services\MailerSendService;
use App\Mail\OrderSuccessEmailManager;
use Illuminate\Support\Facades\Mail;
use App\Models\CombinedOrder;

class EmailController extends Controller
{


    public function sendEmail()
    {
        $combined_order = CombinedOrder::orderBy('id', 'DESC')->first();
        $array['view'] = 'emails.order_success';
        $array['subject'] = translate('A new order has been placed') . ' - ' . $combined_order->code;
        $array['from'] = env('MAIL_FROM_ADDRESS');
        $array['combined_order'] = $combined_order;

        Mail::to('xbayarsukh@gmail.com')->queue(new OrderSuccessEmailManager($array));
    }
}
