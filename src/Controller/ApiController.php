<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Entity\Client;
/**
 * @Route("/Api")
 */
class ApiController extends AbstractController
{
    public function __construct(
        private HttpClientInterface $client,
    ) {
    }

    /**
    * @Route("/connect", name="Api_connect", methods={"GET","POST"})
    */
    public function connect(): void
    {
        $response = $this->client->request(
            'GET',
            'https://lbouquet.doli.sio-ndlp.fr/api/index.php/login?login=slam2&password=fgCDrvFbWu9ev7'
        );

        $statusCode = $response->getStatusCode();
        // $statusCode = 200
        $contentType = $response->getHeaders()['content-type'][0];
        // $contentType = 'application/json'
        $content = $response->getContent();
        // $content = '{"id":521583, "name":"symfony-docs", ...}'
        $content = $response->toArray();
        // $content = ['id' => 521583, 'name' => 'symfony-docs', ...]
        dump($content);
        
    }

     /**
    * @Route("/getuser", name="Api_getuser", methods={"GET","POST"})
    */
    public function getus($firstname, $lastname)
    {
        $this->connect();
        $response = $this->client->request(
            'GET',
            'https://lbouquet.doli.sio-ndlp.fr/api/index.php/thirdparties?sortfield=t.rowid&limit=1&sqlfilters=t.nom%3D\'". $firstname . " " . $lastname "\'&DOLAPIKEY=8n8O4975Miz06XpO6HAKdfmOJQpkjSz3&'
        );
        $statusCode = $response->getStatusCode();
        $user = $response->getContent();
        var_dump($user);
        if ($statusCode == 200){
            return true;
        }
        return false;
    }



    public function createuse($firstname, $lastname, $client)
    {
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);
        $jsonContent = $serializer->serialize($client, 'json');
        
        $this->connect();
        $httpclient = HttpClient::create([
            'header' => [
                'DOLAPIKEY' => '8n8O4975Miz06XpO6HAKdfmOJQpkjSz3&',
                'ContentType' => 'application/json',
            ]
        ]); 
            
        $response = $this->client->request('POST', 'https://lbouquet.doli.sio-ndlp.fr/api/index.php/thirdparties?&DOLAPIKEY=8n8O4975Miz06XpO6HAKdfmOJQpkjSz3&', $jsonContent);
        $statusCode = $response->getStatusCode();
        $user = $response->getContent();
        var_dump($user);
        if ($statusCode == 200){
            return true;
        }
        return false;
    }
}

