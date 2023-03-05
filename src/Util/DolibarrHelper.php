<?php

namespace App\Util;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class DolibarrHelper
{

    private $httpClient;
    private $DOLIBARR_URL;
    private $DOLIBARR_APIKEY;
    private $flashBag;

    public function __construct(ParameterBagInterface $params, FlashBagInterface $flashBag)
    {
        ////////////////////////////////////////////////////////////////
        // @TODO à retirer
        // UPDATE tbl_intervention SET STATUS='En cours' WHERE id = 2;
        ////////////////////////////////////////////////////////////////

        // Créer l'objet HttpClient
        $this->httpClient = HttpClient::create();

        $this->DOLIBARR_URL = $params->get('DOLIBARR_URL');
        if (substr($this->DOLIBARR_URL, -1) != '/') {
            $this->DOLIBARR_URL .= '/';
        }
        $this->DOLIBARR_APIKEY = $params->get('DOLIBARR_APIKEY');

        $this->flashBag = $flashBag;

    }

    public function getDolibarrClientId($intervention)
    {
        $dolibarrClientId = null;

        try {
            $client = $intervention->getClient();
            $client_name = trim($client->getFirstName() . ' ' . $client->getLastName());            

            $this->flashBag->add('info', "Recherche du client dans Dolibarr...");

            // Exécuter la requête
            $response = $this->httpClient->request('GET', $this->DOLIBARR_URL . 'api/index.php/thirdparties?DOLAPIKEY=' . $this->DOLIBARR_APIKEY . '&sqlfilters=t.nom=\'' . $client_name . '\'&limit=1');

            // Afficher le code de retour
            $statusCode = $response->getStatusCode();

            if ($statusCode != 404) {

                // Afficher l'entête de la réponse
                $contentType = $response->getHeaders()['content-type'][0];
                print($contentType . "<br/><br/>");

                // Afficher le contenu JSON de la réponse
                $content = $response->getContent();
                print($content . "<br/><br/>");

                // $this->flashBag->add('success', 'response = "'.print_r($response, true).'"');

                /*

                // Démarche pour obtenir uniquement l'ID du client' :

                // Afficher le contenu OBJET de la réponse
                $content_decode = json_decode($content);
                print_r($content_decode);
                print("<br/><br/>");

                // ID du client
                print("ID du client = " . $content_decode[0]->id);
                print("<br/><br/>");

                    */

                $this->flashBag->add('success', "La facture a été transmise à Dolibarr");
                return $this->redirectToRoute('index');
            } else {
                $this->flashBag->add('info', "Le client '" . $client_name . "' n'a pas trouvé dans Dolibarr");

                $this->flashBag->add('info', "Ajout du client '" . $client_name . "' dans Dolibarr...");
            }
        } catch (\Throwable $th) {
            $this->flashBag->add('success', 'Une erreur est intervenue lors de la transmission de la facture à Dolibarr' . $th->getMessage());
        }

        return $dolibarrClientId;

    }
}
