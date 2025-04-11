<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Models\CombinedOrder;
use App\Models\Wallet;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvoiceEmailManager;
use GuzzleHttp\Client;

class QpayController extends Controller
{   
    public function authenticate()
    {
        $userName = env('QPAY_USERNAME');
        $password = env('QPAY_PASSWORD');
        if (now() > Carbon::parse(env('QPAY_EXPIRE_DATE')) || env('QPAY_EXPIRE_DATE') == null) {
            $curl = curl_init();
            $url = 'https://merchant.qpay.mn/v2/auth/token';

            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
                CURLOPT_USERPWD => $userName . ':' . $password,
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
            $timestamp = json_decode($responseData)->expires_in;
            $date = Carbon::createFromTimestamp($timestamp);
            
            $this->envChange(json_decode($responseData)->access_token, "QPAY_ACCESS_TOKEN");
            $this->envChange('"'.$date.'"', "QPAY_EXPIRE_DATE");
        }

        return env('QPAY_ACCESS_TOKEN');
    }

    public function create($response)
    {
        $curl = curl_init();
        $callback = url('/qpay/combined-order/webhook?invoiceId=' . $response['bill_no']);
        $data = array(
            "invoice_code" => env('QPAY_INVOICE_NO'),
            "sender_invoice_no" => $response['bill_no'],
            "invoice_receiver_code" => "terminal_02",
            "invoice_description" => $response['bill_no'],
            "amount" => $response["amount"],
            "callback_url" => $callback,
        );

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://merchant.qpay.mn/v2/invoice',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Authorization: Bearer " . env('QPAY_ACCESS_TOKEN'),
            ),
        ));

        $responseFromApi = curl_exec($curl);

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

        return $responseFromApi;
    }

    public function createWallet($response)
    {
        $curl = curl_init();
        $callback = url('/qpay/wallet/webhook?invoiceId=' . $response['bill_no']);
        $data = array(
            "invoice_code" => env('QPAY_INVOICE_NO'),
            "sender_invoice_no" => $response['bill_no'],
            "invoice_receiver_code" => "terminal_02",
            "invoice_description" => $response['bill_no'],
            "amount" => $response["amount"],
            "callback_url" => $callback,
        );

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://merchant.qpay.mn/v2/invoice',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Authorization: Bearer " . env('QPAY_ACCESS_TOKEN'),
            ),
        ));

        $responseFromApi = curl_exec($curl);

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

        return $responseFromApi;
    }

    public function webhook(Request $request)
    {
        if ($request->invoiceId) {
            $combined_order = CombinedOrder::where('code', $request->invoiceId)->first();
            if ($combined_order) {
                foreach ($combined_order->orders as $order) {
                    if ($order->payment_status = 'pending') {
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
                        $this->send_example(json_decode($combined_order->shipping_address)->phone, 'Tanii tulbur amjilttai tulugdluu.');
                    }
                }
                return response()->json(['success' => true, 'status' => 200, 'message' => 'Төлбөр төлөлт амжилттай.']);
            } else {
                return response()->json(['success' => false, 'status' => 401, 'message' => 'Хүсэлтийн дугаар алдаатай байна. Хүсэлтээ шалгана уу?.']);
            }
        } else {
            return response()->json(['success' => false, 'status' => 401, 'message' => 'Хүсэлтийн дугаар алдаатай байна. Хүсэлтээ шалгана уу?.']);
        }
    }

    public function webhookWallet(Request $request)
    {
        if ($request->invoiceId) {
            $wallet = Wallet::where('order_no', $request->invoiceId)->where('type', 'Pending')->first();
            if ($wallet) {
               
                $wallet->approval = 1;
                $wallet->type = "Added";

                $user = $wallet->user;
                $user->balance = $user->balance + $wallet->amount;
                $user->save();
                if ($wallet->save()) {
                    return response()->json(['success' => true, 'status' => 200, 'message' => 'Төлбөр төлөлт амжилттай.']);
                }
                return response()->json(['success' => true, 'status' => 200, 'message' => 'Төлбөр төлөлт амжилттай.']);
            } else {
                return response()->json(['success' => false, 'status' => 401, 'message' => 'Хүсэлтийн дугаар алдаатай байна. Хүсэлтээ шалгана уу?.']);
            }
        } else {
            return response()->json(['success' => false, 'status' => 401, 'message' => 'Хүсэлтийн дугаар алдаатай байна. Хүсэлтээ шалгана уу?.']);
        }
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

    public function getItems() {
        $url = "http://nav00.orem.local:7047/DynamicsNAV110/WS/TEST%20KB%2020240528/Page/ItemListWSWHUB";
        $response = Http::get($url);
        if ($response->successful()) {
            return $response->json();
        }

        return [
            'error' => $response->status(),
            'message' => $response->body(),
        ];
    }

    public function isPaidWallet($order_id) {
        $wallet = Wallet::where('order_no', $order_id)->first();
        return $wallet->type == "Added" ? "paid" : "unpaid";
    }

    public function isPaidOrder($order_id) {
        $combined_order = CombinedOrder::where('code', $order_id)->first();
        if (!$combined_order) {
            return "unpaid";
        }
        return $combined_order->orders[0]->payment_status == "paid" ? "paid" : "unpaid";
    }

    public function send_example($receiver, $title) {
    // public function send_example(Request $request)
    // {
    //     $receiver = $request->phone;
    //     $title = $request->title;
        if (strlen($receiver) <= 12 && strlen($receiver) >= 8) {
            $msg = $title;
            $data = 'from='. 72010200 . '&to=' . $receiver . '&text='.$msg;

            $url = "https://api.messagepro.mn/send?" . $data;

            $response = Http::withHeaders([
                'Content-Type' => 'application/json', // Example header
                'x-api-key' => env('SMS_TOKEN'), // Example token
            ])->get($url);

            return $response->json();
        }
    }

    public function test(Request $request){
        $username = 'orem\webservice'; // Combine domain and username
        $password = 'zc+v~.g3Mp^H';
        $url = "http://49.0.158.178:7057/DynamicsNAV111/WS/TEST%20KB%2020240528/Page/" . $request->page;
        $method = 'GET'; // or 'POST', depending on your needs

        // Create a Guzzle client instance
        $client = new Client();

        $options = [
            'auth' => [$username, $password, 'ntlm'], // NTLM Authentication
            'headers' => [
                'Accept' => 'application/xml', // Request XML response
            ],
        ];

        $response = $client->request($method, $url, $options);

        // Get the raw XML response body
        $xmlBody = $response->getBody()->getContents();

        // Load the XML response
        $xml = simplexml_load_string($xmlBody);
        $namespaces = $xml->getNamespaces(true); // Нэрийн бүсүүдийг авч байна

        // XPath ашиглан элементүүдийг олох
        $headers = ['Key'];
        foreach ($xml->xpath('//*[local-name()="enumeration"]') as $element) {
            $headers[] = (string) $element['value'];
        }

        // Debug: Print extracted headers
        if (empty($headers)) {
            echo "No headers found. Check the structure of your SOAP response.";
            die();
        }
    
        echo '
            <style>
                *{
                    font-size: 13px;
                }
            </style>
        ';


        // Generate an HTML table with the headers
        echo "<table border='1'>";
        echo "<thead>";
        echo "<tr>";
        foreach ($headers as $header) {
            echo "<th>" . htmlspecialchars($header) . "</th>";
        }
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        $res = $this->test2($request->page, $headers);
        echo $res["body"];
        echo "</tbody>";
        echo "</table>";
        
        echo $res["length"];
    }

    public function test2($text, $headData) {
        $username = 'orem\webservice'; // Combine domain and username
        $password = 'zc+v~.g3Mp^H';
        $url = "http://49.0.158.178:7057/DynamicsNAV111/WS/TEST%20KB%2020240528/Page/$text";
        $method = 'POST'; // Use 'POST' to send XML data

        $lowercaseText = strtolower($text);
        // XML body data to send in the request
        $xmlData = <<<XML
        <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:web="urn:microsoft-dynamics-schemas/page/{$lowercaseText}">
            <soapenv:Body>
                <web:ReadMultiple>
                    <!-- <web:filter>
                        <web:Field>No</web:Field>
                        <web:Criteria>002158</web:Criteria>
                    </web:filter> -->
                    <!-- <web:setSize>10</web:setSize> -->
                </web:ReadMultiple>
            </soapenv:Body>
        </soapenv:Envelope>
        XML;

        $client = new Client();

        // Options for the request
        $options = [
            'auth' => [$username, $password, 'ntlm'], // NTLM Authentication
            'headers' => [
                'Accept' => 'application/xml', // Request XML response
                'Content-Type' => 'application/xml', // Content type is XML
                'soapAction' => "urn:microsoft-dynamics-schemas/page/$lowercaseText:ReadMultiple",
            ],
            'body' => $xmlData, // Attach XML data in the request body
        ];

        $response = $client->request($method, $url, $options);

        // Get the raw XML response body
        $xmlBody = $response->getBody()->getContents();

        // Load the XML response
        $xml = simplexml_load_string($xmlBody);
        $result = "";
        $length = 0;
        // Нэрийн бүсийг бүртгэх
        foreach ($xml->xpath('//*[local-name()="'.$text.'"]') as $element) {
            $result .= "<tr>";
            $data = [];
        
            // Convert XML element to array
            $elementArray = json_decode(json_encode($element), true);
        
            foreach ($headData as $key => $head) {
                $result .= "<td>" . (isset($elementArray[$head]) ? $elementArray[$head] : '') . "</td>";
                $data[$head] = (isset($elementArray[$head]) ? $elementArray[$head] : '');
            }
        
            // Add the extracted data to the header array
            $header[] = $data;
            $result .= "</tr>";
            $length++;
        }
        
        // Return the headers array
        return [
           "body" => $result,
           "length" => $length,
        ];
    }
}