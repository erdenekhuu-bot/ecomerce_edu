<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvoiceEmailManager;
use App\Models\CombinedOrder;
use App\Http\Controllers\QpayController;

class GolomtController extends Controller
{   
    public function authenticate()
    {
        $userName = env('GOLOMT_USERNAME');
        $ivKey = env('GOLOMT_IV_KEY'); // Base64 decode the IV key
        $sessionKey = env('GOLOMT_SESSION_KEY'); // Base64 decode the session key
        $password = env('GOLOMT_PASSWORD');

        $encrypted = openssl_encrypt($password, 'aes-128-cbc', $sessionKey, 0, $ivKey);

        if (now() > Carbon::parse(env('GOLOMT_EXPIRE_DATE')) || env('GOLOMT_EXPIRE_DATE') == null) {
            $curl = curl_init();
            $url = 'https://openbank.golomtbank.com/api/v1/auth/login';

            $data = array(
                "name" => $userName,
                "password" => $encrypted,
            );

            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($data),
                CURLOPT_HTTPHEADER => array(
                    "Content-Type: application/json",
                    "X-Golomt-Checksum: sdf",
                    "X-Golomt-Service: LGIN",
                ),
            ));

            $responseData = curl_exec($curl);

            if (curl_errno($curl)) {
                // Handle cURL errors
                return 'cURL Error: ' . curl_error($curl);
            }

            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            if ($httpCode != 200) {
                // Handle non-200 HTTP response
                return 'HTTP Error: ' . $httpCode;
            }

            curl_close($curl);

            $date = Carbon::now()->addSeconds(json_decode($responseData)->expiresIn);
            
            $this->envChange(json_decode($responseData)->token, "GOLOMT_ACCESS_TOKEN");
            $this->envChange(json_decode($responseData)->refreshToken, "GOLOMT_REFRESH_TOKEN");
            $this->envChange('"'.$date.'"', "GOLOMT_EXPIRE_DATE");
            return json_decode($responseData)->token;
        }

        return env('GOLOMT_ACCESS_TOKEN');
    }

    public function reToken() {
        $curl = curl_init();
        $url = 'https://openbank.golomtbank.com/api/v1/auth/refresh';

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Authorization: Bearer " . env('GOLOMT_REFRESH_TOKEN'),
            ),
        ));

        $responseData = curl_exec($curl);

        if (curl_errno($curl)) {
            // Handle cURL errors
            return 'cURL Error: ' . curl_error($curl);
        }

        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ($httpCode != 200) {
            // Handle non-200 HTTP response
            
            return $this->authenticate();
        }

        curl_close($curl);

        $date = Carbon::now()->addSeconds(json_decode($responseData)->expiresIn);
        
        $this->envChange(json_decode($responseData)->token, "GOLOMT_ACCESS_TOKEN");
        $this->envChange(json_decode($responseData)->refreshToken, "GOLOMT_REFRESH_TOKEN");
        $this->envChange('"'.$date.'"', "GOLOMT_EXPIRE_DATE");
        return json_decode($responseData)->token;
    }

    

    public function envChange($title, $key) {
        $envFilePath = base_path('.env');
        $envFileContent = File::get($envFilePath);

        // Check if the key exists in the .env file
        if (Str::contains($envFileContent, "$key=")) {
            // Update the existing key
            $envFileContent = preg_replace("/$key=(.*)/", "$key=$title", $envFileContent);
        } else {
            // Append the new key-value pair
            $envFileContent .= "\n$key=$title";
        }

        // Write the updated content back to the .env file
        File::put($envFilePath, $envFileContent);
        return $envFileContent;
    }

    public function isPaid($id)
    {
        $curl = curl_init();
        $ivKey = env('GOLOMT_IV_KEY'); // Base64 decode the IV key
        $sessionKey = env('GOLOMT_SESSION_KEY'); // Base64 decode the session key
        $password = env('GOLOMT_PASSWORD');
        
        $data = array(
            "registerNo" => "3624935",
            "accountId" => "1705240039",
            "startDate" => Carbon::now()->subDays(10)->format('Y-m-d'),
            "endDate" => Carbon::now()->format('Y-m-d'),
        );

        $encrypted = openssl_encrypt(hash('sha256', json_encode($data)), 'aes-128-cbc', $sessionKey, 0, $ivKey);

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://openbank.golomtbank.com/api/v1/account/operative/statement?client_id=" . env('GOLOMT_CLIENT_ID') . "&state=&scope=",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Authorization: Bearer " . $this->reToken(),
                "X-Golomt-Checksum: " . $encrypted,
                "X-Golomt-Service: OPERACCTSTA",
            ),
        ));

        $responseFromApi = curl_exec($curl);
        $encrypted = openssl_decrypt($responseFromApi, 'aes-128-cbc', $sessionKey, 0, $ivKey);
        if (curl_errno($curl)) {
            // Handle cURL errors
            return 'cURL Error: ' . curl_error($curl);
        }

        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ($httpCode != 200) {
            // Handle non-200 HTTP response
            
            return $encrypted;
        }

        curl_close($curl);

        $arrayData = json_decode($encrypted);
        // return $arrayData;
        $orderID = $id;

        $found = collect($arrayData->statements)->first(function ($statement) use ($orderID) {
            return Str::contains($statement->tranDesc, $orderID);
        });

        $payment_status = "unpaid";

        if ($found) {
            if ($orderID) {
                $combined_order = CombinedOrder::where('code', $orderID)->first();
                if ($combined_order) {
                    foreach ($combined_order->orders as $order) {
                        if ($order->payment_status == 'pending' && ((int)$found->tranAmount == (int)$order->grand_total)) {
                            // commission calculation
                            calculate_seller_commision($order);
                            $order->payment_status = 'paid';
                            $order->payment_type = "qpay";
                            $order->save();

                            $array['view'] = 'emails.order_success';
                            $array['subject'] = "Захиалга төлөгдлөө" . ' - ' . $combined_order->code;
                            $array['from'] = env('MAIL_FROM_ADDRESS');
                            $array['combined_order'] = $combined_order;
                            if ($order->user->email != null) {
                                Mail::to($order->user->email)->queue(new InvoiceEmailManager($array));
                            }
                            $qpay = new QpayController;
                            $qpay->send_example(json_decode($combined_order->shipping_address)->phone, 'Tanii tulbur amjilttai tulugdluu.');
                        }
                    }
                    $payment_status = $combined_order->orders[0]->payment_status;
                } 
            } 
        }

        return $found ? "paid" : "unpaid";
    }
}