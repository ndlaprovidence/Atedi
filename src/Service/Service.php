<?php

namespace App\Service;


use Symfony\Component\HttpClient\HttpClient ;
use App\Entity\Intervention;
use Error;
class Service 
{
    
    function call_dolibarr_api($endpoint, $method = 'GET',$dolapikey, $data) {
        
        $httpClient = HttpClient::create();
        // $data = json_encode($data);
        $dolibarrApiParams = [
            'sqlfilters' => $data['sqlfilters'],
        ];
        $response = $httpClient->request($method, 'https://lbouquet.doli.sio-ndlp.fr/api/index.php/'.$endpoint , [
            'headers' => [
                'DOLAPIKEY' => $dolapikey,
            ],
            'query' => $dolibarrApiParams,
        ]);
        
        $result = $response->getStatusCode();


        
        return $result;
    }
    function create_user_dolibarr_api($endpoint, $method = 'POST',$dolapikey, $data) {
        
        $httpClient = HttpClient::create();
        // $data = json_encode($data);
        dump(json_encode($data));
        
        $response = $httpClient->request($method, 'https://lbouquet.doli.sio-ndlp.fr/api/index.php/'.$endpoint, [
            'headers' => [
                'DOLAPIKEY' => $dolapikey,
                'Content-type' => "application/json",
            ],
            "body" => json_encode($data),
        ]);
        $result = $response->getStatusCode();
        dump($response);
        dump($result);
        return $result;
        
        
        
        
    }
    public function getThird_party_id_per_phone_number(string $phoneNumber, $endpoint, $data, $dolapikey): ?int
    {
        $httpClient = HttpClient::create();
        $dolibarrSqlReq = "t.phone = $phoneNumber";
        $query = ["limit" => "1", "sqlfilters" => $dolibarrSqlReq];
        $dolibarrApiParams = [
            'sqlfilters' => $data['t.phone'],
        ];
        $response = $httpClient->request("GET", 'https://lbouquet.doli.sio-ndlp.fr/api/index.php/' . $endpoint, [
            'headers' => [
                'DOLAPIKEY' => $dolapikey ,
                'Content-type' => "application/json",

            ],
            'query' => $query,
        ]);
        $decodedPayload = $response->toArray();
        dump($decodedPayload);
        $thirdParty = $decodedPayload[0];
        if (isset($thirdParty)) {
            return (int) $thirdParty["id"];
        }

        return null;
    }

    function create_invoice_dolibarr_api( $dolapikey, $method = "POST", $endpoint, $data)
    {
        $httpClient = HttpClient::create();
        // $client = $intervention->getClient();
        // $clientId = $client->getId();
        
        
        $response = $httpClient->request($method,'https://lbouquet.doli.sio-ndlp.fr/api/index.php/'. $endpoint, [
            'headers'=> [
                'DOLAPIKEY' => $dolapikey,
                'Content-type' => 'application/json',
            ],
            "body" => json_encode($data),
        ]);

        dump(json_encode($data));
        dump($response);
        
        $statusCode = $response->getStatusCode();
        
        if ($statusCode !== 200) {
            throw new Error("Impossible to send the invoice to Dolibarr : got $statusCode");
        }
        return $response->getStatusCode();
    
    }
    public function getProductIdPerDesc(string $desc, $dolapikey): ?int {
        $httpClient = HttpClient::create();

        $query = ["sqlfilters" => "t.label='" . $desc . "'"];
        $response = $httpClient->request("GET", 'https://lbouquet.doli.sio-ndlp.fr/api/index.php/products', [
            'headers' => [
                'DOLAPIKEY' => $dolapikey,
                'Content-type' => 'application/json',
            ],
            'query' => $query
        ]);

        if ($response->getStatusCode() !== 200) {
            return null;
        }

        $decodedPayload = $response->toArray();
        $product = $decodedPayload[0];

        if (isset($product)) {
            return (int) $product["id"];
        }

        return null;
    }
    public function createProduct($task, $dolapikey) {
        $httpClient = HttpClient::create();

        $body = [
            "ref" => $task->getTitle(),
            "label" => $task->getTitle(),
            "total_ht" => $task->getPrice(),
        ];

        $response = $httpClient->request("POST", 'https://lbouquet.doli.sio-ndlp.fr/api/index.php/products', [
            'headers'=> [
                'DOLAPIKEY' => $dolapikey,
                'Content-type' => 'application/json',
            ],
            'json' =>
            $body
        ]);
        
        $statusCode = $response->getStatusCode();
        if ($statusCode !== 200) {
            return null;
        }
        return $response->getContent();
    }
}
