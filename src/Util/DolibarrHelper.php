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
    private $TAUX_TVA;
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

        $this->TAUX_TVA = $params->get('TAUX_TVA');

        $this->flashBag = $flashBag;
    }

    public function getDolibarrClientId($client)
    {
        $dolibarrClientId = null;

        try {
            $client_name = trim($client->getFirstName() . ' ' . $client->getLastName());

            $action = 'la recherche du client dans Dolibarr';
            // $this->flashBag->add('info', "Recherche du client '" . $client_name . "' dans Dolibarr...");

            // Exécuter la requête
            $response = $this->httpClient->request('GET', $this->DOLIBARR_URL . 'api/index.php/thirdparties?DOLAPIKEY=' . $this->DOLIBARR_APIKEY . '&sqlfilters=t.nom=\'' . $client_name . '\'&limit=1');

            // Afficher le code de retour
            $statusCode = $response->getStatusCode();

            if ($statusCode != 404) {

                // Afficher l'entête de la réponse
                $contentType = $response->getHeaders()['content-type'][0];
                // $this->flashBag->add('info', $contentType);

                // Afficher le contenu JSON de la réponse
                $content = $response->getContent();
                // $this->flashBag->add('info', $content);

                // Afficher le contenu OBJET de la réponse
                $content_decode = json_decode($content);
                // $this->flashBag->add('info', print_r($content_decode, true));

                // ID du client
                $dolibarrClientId = $content_decode[0]->id;
                // $this->flashBag->add('info', "ID du client = " . $dolibarrClientId);
            } else {
                $action = 'la création du client dans Dolibarr';
                // $this->flashBag->add('info', "Le client '" . $client_name . "' n'a pas trouvé, ajout du client dans Dolibarr...");

                $response = $this->httpClient->request('POST', $this->DOLIBARR_URL . 'api/index.php/thirdparties?DOLAPIKEY=' . $this->DOLIBARR_APIKEY, [
                    'body' => [
                        'client' => 1,
                        'code_client' => -1,
                        'name' => $client_name,
                        'address' => $client->getStreet(),
                        'zip' => $client->getPostalCode(),
                        'town' => $client->getCity(),
                        'status' => 1,
                        'email' => $client->getEmail(),
                        'phone' => $client->getPhone(),
                    ],
                ]);

                // Afficher le code de retour
                $statusCode = $response->getStatusCode();
                // $this->flashBag->add('info', $statusCode);

                // Afficher l'entête de la réponse
                $contentType = $response->getHeaders()['content-type'][0];
                // $this->flashBag->add('info', $contentType);

                // Afficher le contenu JSON de la réponse
                $dolibarrClientId = $response->getContent();
                // $this->flashBag->add('info', "ID du client qui vient d'être créé : " . $dolibarrClientId);
            }
        } catch (\Throwable $th) {
            $this->flashBag->add('success', 'Une erreur est intervenue lors de ' . $action . ' : ' . $th->getMessage());
        }

        return $dolibarrClientId;
    }

    public function getDolibarrProductServiceId($product, $type)
    {
        $dolibarrProductId = null;

        if ($type == 'service') {
            $type = 1;
        } else {
            $type = 0;
        }

        try {
            $product_name = $product->getTitle();

            $action = 'la recherche de la tâche dans Dolibarr';
            // $this->flashBag->add('info', "Recherche du product '" . $product_name . "' dans Dolibarr...");

            // Exécuter la requête
            $response = $this->httpClient->request('GET', $this->DOLIBARR_URL . 'api/index.php/products?DOLAPIKEY=' . $this->DOLIBARR_APIKEY . '&sqlfilters=t.label=\'' . $product_name . '\'&limit=1');

            // Afficher le code de retour
            $statusCode = $response->getStatusCode();

            if ($statusCode != 404) {

                // Afficher l'entête de la réponse
                $contentType = $response->getHeaders()['content-type'][0];
                // $this->flashBag->add('info', $contentType);

                // Afficher le contenu JSON de la réponse
                $content = $response->getContent();
                // $this->flashBag->add('info', $content);

                // Afficher le contenu OBJET de la réponse
                $content_decode = json_decode($content);
                // $this->flashBag->add('info', print_r($content_decode, true));

                // ID du product
                $dolibarrProductId = $content_decode[0]->id;
                // $this->flashBag->add('info', "ID du product = " . $dolibarrProductId);
            } else {
                $action = 'la création de la tâche dans Dolibarr';
                // $this->flashBag->add('info', "Le product '" . $product_name . "' n'a pas trouvé, ajout du product dans Dolibarr...");


                $price = ($product->getPrice() / (1 + ($this->TAUX_TVA / 100)));
                $price_ttc = $product->getPrice();
                $tva_tx = $this->TAUX_TVA;
                $this->flashBag->add('info', "product_name = '" . $product_name
                    . "' price = '" . $price
                    . "' price_ttc = '" . $price_ttc
                    . "' tva_tx = '" . $tva_tx . "' ");
                $response = $this->httpClient->request('POST', $this->DOLIBARR_URL . 'api/index.php/products?DOLAPIKEY=' . $this->DOLIBARR_APIKEY, [
                    'body' => [
                        'ref' => 'ATEDI-' . str_pad($product->getId(), 3, "0", STR_PAD_LEFT),
                        'label' => 'Intervention : ' . $product_name,
                        'type' => $type,
                        'price' => $price,
                        'price_ttc' => $price_ttc,
                        'price_base_type' => 'HT',
                        'tva_tx' => $tva_tx,
                        'status' => 1, //tosell
                    ],
                ]);

                // Afficher le code de retour
                $statusCode = $response->getStatusCode();
                // $this->flashBag->add('info', $statusCode);

                // Afficher l'entête de la réponse
                $contentType = $response->getHeaders()['content-type'][0];
                // $this->flashBag->add('info', $contentType);

                // Afficher le contenu JSON de la réponse
                $dolibarrProductId = $response->getContent();
                // $this->flashBag->add('info', "ID du product qui vient d'être créé : " . $dolibarrProductId);
            }
        } catch (\Throwable $th) {
            $this->flashBag->add('success', 'Une erreur est intervenue lors de ' . $action . ' : ' . $th->getMessage());
        }

        return $dolibarrProductId;
    }
}
