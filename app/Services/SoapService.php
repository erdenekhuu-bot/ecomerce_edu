<?php

namespace App\Services;

class SoapService
{
    protected $client;

    public function __construct()
    {

        $username = 'orem\\webservice';  // Use double backslashes for escape
        $password = 'zc+v~.g3Mp^H';
        $url = "http://49.0.158.178:7057/DynamicsNAV111/WS/TEST%20KB%2020240528/Page/ItemListWSWHUB?wsdl";

        // Create a custom context for NTLM authentication
        $options = [
            'http' => [
                'header' => "Authorization: NTLM " . base64_encode("$username:$password")
            ]
        ];

        // Set the context to the SoapClient
        $context = stream_context_create($options);




    }

    public function callMethod($method, $params = [])
    {
        try {
            // Call the desired SOAP method
            $response = $this->client->__soapCall('ReadMultiple', [$params]);
            return $response;
        } catch (\Exception $e) {
            // Handle exceptions
            return $e->getMessage();
        }
    }
}
