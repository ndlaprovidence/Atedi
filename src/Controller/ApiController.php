<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

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
        var_dump($content);
        
    }

     /**
    * @Route("/getuser", name="Api_getuser", methods={"GET","POST"})
    */
    public function getuser()
    {
        $this->connect();
        $response = $this->client->request(
            'GET',
            'https://lbouquet.doli.sio-ndlp.fr/api/index.php/thirdparties?sortfield=t.rowid&limit=1&sqlfilters=t.nom%3D\'Christelle%20LAVEILLE\'&DOLAPIKEY=8n8O4975Miz06XpO6HAKdfmOJQpkjSz3&'
        );
        $statusCode = $response->getStatusCode();
        $user = $response->getContent();
        var_dump($user);
        if ($statusCode == 200){
            return true;
        }
        return false;
    }
}

