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
                'DOLAPIKEY' => $dolapikey ,
            ],
            'query' => $dolibarrApiParams,
        ]);
        
        $result = $response->getStatusCode();


        
        return $result;
    }
    function create_user_dolibarr_api($endpoint, $method = 'POST',$dolapikey, $data) {
        
        $httpClient = HttpClient::create();
        // $data = json_encode($data);
        
        $response = $httpClient->request($method, 'https://lbouquet.doli.sio-ndlp.fr/api/index.php/'.$endpoint, [
            'headers' => [
                'DOLAPIKEY' => $dolapikey,
                'Content-type' => 'application/json',
            ],
            "body" => json_encode($data),
        ]);
        $result = $response->getStatusCode();
        dump($response);
        dump($result);
        
        
        
        
    }
    public function getThird_party_id_per_phone_number(string $phoneNumber, $endpoint, $data, $dolapikey): ?int
    {
        $httpClient = HttpClient::create();
        $dolibarrSqlReq = "t.phone = $phoneNumber";
        $query = ["limit" => "1", "sqlfilters" => $dolibarrSqlReq];
        $dolibarrApiParams = [
            'sqlfilters' => $data['sqlfilters'],
        ];
        $response = $httpClient->request("GET", 'https://lbouquet.doli.sio-ndlp.fr/api/index.php/' . $endpoint, [
            'headers' => [
                'DOLAPIKEY' => $dolapikey ,
            ],
            'query' => $dolibarrApiParams,
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
}
