<?php

namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;


class DolibarrHelper
{
    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
    }
    
    public function getuse( $prenom, $nom) // On récupère le nom et le prénom du client 
    {
        $Dolapikey = $this->parameterBag->get('DOLAPIKEY');
        $httpClient = HttpClient::create();
        
        $response = $httpClient->request('GET', 'http://lbouquet.doli.sio-ndlp.fr/api/index.php/thirdparties?&sqlfilters=t.nom=\''. $prenom ." ". $nom .'\'&limit=1&DOLAPIKEY='. $Dolapikey); // puis on les renvoie dans une requete http
        dump($response);
        
        $content = $response->getContent();
        return $content;
        
        
    }
    public function postuse($prenom, $nom) // On récupère le nom et le prénom du client 
    {
        $Dolapikey = $this->parameterBag->get('DOLAPIKEY');
        $httpClient = HttpClient::create();
        array = {
            'code': '1',
            'code_client': -1,
            'nom': $prenom . $nom
        }
        $response = $httpClient->request('POST', 'http://lbouquet.doli.sio-ndlp.fr/api/index.php/thirdparties?&sqlfilters=t.nom=\''. $prenom ." ". $nom .'\'&limit=1&DOLAPIKEY='. $Dolapikey); // puis on les renvoie dans une requete http
        dump($response);
        
        $content = $response->getContent();
        return $content;
        
        
    }
    
}