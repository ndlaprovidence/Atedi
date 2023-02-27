<?php

namespace App\Controller;

use App\Entity\BillingLine;
use App\Entity\Intervention;
use App\Entity\InterventionReport;
use App\Entity\SoftwareInterventionReport;
use App\Form\BillingLineType;
use App\Form\InterventionType;
use App\Repository\ActionRepository;
use App\Repository\BillingLineRepository;
use App\Repository\BookletRepository;
use App\Repository\ClientRepository;
use App\Repository\InterventionRepository;
use App\Repository\SoftwareInterventionReportRepository;
use App\Repository\SoftwareRepository;
use App\Repository\TechnicianRepository;
use App\Util\AtediHelper;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/intervention")
 */
class InterventionController extends AbstractController
{
    private $AtediHelper;

    public function __construct(AtediHelper $AtediHelper)
    {
        $this->atediHelper = $AtediHelper;
    }

    /**
     * @Route("/", name="intervention_index", methods={"GET"})
     */
    public function index(InterventionRepository $interventionRepository): Response
    {
        return $this->render('intervention/index.html.twig', [
            'interventions' => $interventionRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="intervention_new", methods={"GET","POST"})
     */
    function new (Request $request, ClientRepository $cr, EntityManagerInterface $em): Response {
        $em = $em;

        $intervention = new Intervention();
        $interventionReport = new InterventionReport();

        $form = $this->createForm(InterventionType::class, $intervention);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $interventionReport->setStep(1);
            $em->persist($interventionReport);
            $em->flush();

            $intervention->setInterventionReport($interventionReport);

            $totalPrice = $this->atediHelper->strTotalPrice($intervention);

            $intervention->setTotalPrice($totalPrice);
            $em->persist($intervention);
            $em->flush();

            return $this->redirectToRoute('intervention_show', [
                'id' => $intervention->getId(),
            ]);
        }

        return $this->render('intervention/new.html.twig', [
            'intervention' => $intervention,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="intervention_show", methods={"GET","POST"})
     */
    public function show(Request $request, Intervention $intervention, EntityManagerInterface $em, SoftwareRepository $sr, ActionRepository $ar, SoftwareInterventionReportRepository $sirr): Response
    {
        $theStatus = $intervention->getStatus();

        if ($request->request->has('status')) {
            $newStatus = $request->request->get('status');

            if ($theStatus != $newStatus) {

                switch ($newStatus) {
                    case "En attente":
                        $intervention->getInterventionReport()->setStep(1);
                        $intervention->setStatus($newStatus);
                        $intervention->setReturnDate(null);
                        break;

                    case "En cours":
                        $intervention->getInterventionReport()->setStep(1);
                        $intervention->setStatus($newStatus);
                        if ($theStatus == "Terminée") {
                            $intervention->setReturnDate(null);
                        }
                        break;

                    case "Terminée":
                        if ($intervention->getInterventionReport()->getStep() == 8 && $intervention->getReturnDate()) {
                            $intervention->setStatus($newStatus);
                            $em->persist($intervention);
                            $em->flush();

                            ////////////////////////////////////////////////////////////////
                            // @TODO à retirer
                            // UPDATE tbl_intervention SET STATUS='En cours' WHERE id = 2;
                            ////////////////////////////////////////////////////////////////

                            // Créer l'objet HttpClient
                            $httpClient = HttpClient::create();

                            $DOLIBARR_URL = $this->getParameter('DOLIBARR_URL');
                            if (substr($DOLIBARR_URL, -1) != '/') {
                                $DOLIBARR_URL .= '/';
                            }
                            $DOLIBARR_APIKEY = $this->getParameter('DOLIBARR_APIKEY');

                            try {
                                $client = $intervention->getClient();
                                $client_name = trim($client->getFirstName() . ' ' . $client->getLastName());
                                $this->addFlash('info', "Recherche du client '" . $client_name . "' dans Dolibarr...");

                                // Exécuter la requête
                                $response = $httpClient->request('GET', $DOLIBARR_URL . 'api/index.php/thirdparties?DOLAPIKEY=' . $DOLIBARR_APIKEY . '&sqlfilters=t.nom=\'' . $client_name . '\'&limit=1');

                                // Afficher le code de retour
                                $statusCode = $response->getStatusCode();                                                         

                                if ($statusCode != 404) {

                                    // Afficher l'entête de la réponse
                                    $contentType = $response->getHeaders()['content-type'][0];
                                    print($contentType . "<br/><br/>");

                                    // Afficher le contenu JSON de la réponse
                                    $content = $response->getContent();
                                    print($content . "<br/><br/>");

                                    // $this->addFlash('success', 'response = "'.print_r($response, true).'"');

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

                                    $this->addFlash('success', "La facture a été transmise à Dolibarr");
                                    return $this->redirectToRoute('index');

                                }
                                else {
                                    $this->addFlash('info', "Le client '" . $client_name . "' n'a pas trouvé dans Dolibarr");

                                    $this->addFlash('info', "Ajout du client '" . $client_name . "' dans Dolibarr...");




                                    

                                }

                            } catch (\Throwable$th) {
                                $this->addFlash('success', 'Une erreur est intervenue lors de la transmission de la facture à Dolibarr' . $th->getMessage());
                            }
                        }
                        break;
                }

                $em->persist($intervention);
                $em->flush();
            }
        }

        if ($request->request->has('return-date')) {
            $returnDate = $request->request->get('return-date');
            $intervention->setReturnDate(new \DateTime());

            $em->persist($intervention);
            $em->flush();
        }

        if ($request->request->has('download')) {

            $download = $request->request->get('download');

            switch ($download) {
                case "request":
                    $cleaningSoftwares = $sr->findAllByType('Nettoyage');
                    $actions = $ar->findAll();

                    // Configure Dompdf according to your needs
                    $pdfOptions = new Options();
                    $pdfOptions->set('defaultFont', 'Arial');

                    // Instantiate Dompdf with our options
                    $dompdf = new Dompdf($pdfOptions);

                    // Retrieve the HTML generated in our twig file
                    $html = $this->renderView('intervention/request_pdf.html.twig', [
                        'intervention' => $intervention,
                        'cleaningSoftwares' => $cleaningSoftwares,
                        'actions' => $actions,
                    ]);

                    // Load HTML to Dompdf
                    $dompdf->loadHtml($html);

                    // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
                    $dompdf->setPaper('A4', 'portrait');

                    // Render the HTML as PDF
                    $dompdf->render();

                    $pdfName = $intervention->getClient()->getLastName() . '-DEMANDE-' . time() . '.pdf';
                    // Output the generated PDF to Browser (force download)
                    $dompdf->stream($pdfName, [
                        "Attachment" => true,
                    ]);
                    break;

                case "bill":
                    $interventionReportId = $intervention->getInterventionReport()->getId();
                    $softwares = $sirr->findAllByReport($interventionReportId);
                    $actions = $intervention->getInterventionReport()->getActions();
                    $booklets = $intervention->getInterventionReport()->getBooklets();
                    $technicians = $intervention->getInterventionReport()->getTechnicians();

                    // Configure Dompdf according to your needs
                    $pdfOptions = new Options();
                    $pdfOptions->set('defaultFont', 'Arial');

                    // Instantiate Dompdf with our options
                    $dompdf = new Dompdf($pdfOptions);

                    // Retrieve the HTML generated in our twig file
                    $html = $this->renderView('intervention/bill_pdf.html.twig', [
                        'intervention' => $intervention,
                        'softwares' => $softwares,
                        'actions' => $actions,
                        'booklets' => $booklets,
                        'technicians' => $technicians,
                    ]);

                    // Load HTML to Dompdf
                    $dompdf->loadHtml($html);

                    // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
                    $dompdf->setPaper('A4', 'portrait');

                    // Render the HTML as PDF
                    $dompdf->render();

                    $pdfName = $intervention->getClient()->getLastName() . '-RAPPORT-' . time() . '.pdf';
                    // Output the generated PDF to Browser (force download)
                    $dompdf->stream($pdfName, [
                        "Attachment" => true,
                    ]);
                    break;
            }
        }

        return $this->render('intervention/show.html.twig', [
            'intervention' => $intervention,
        ]);
    }

    /**
     * @Route("/{id}/report", name="intervention_report", methods={"GET","POST"})
     */
    public function report(Request $request, EntityManagerInterface $em, Intervention $intervention, SoftwareRepository $sr, BookletRepository $br, ActionRepository $ar, SoftwareInterventionReportRepository $sirr, BillingLineRepository $blr, TechnicianRepository $tr): Response
    {
        $em = $em;

        $interventionReport = $intervention->getInterventionReport();
        $step = $interventionReport->getStep();

        if ($request->query->has('step')) {

            $setup = $request->query->get('step');

            switch ($setup) {
                case "next":
                    $interventionReport->setStep($step + 1);
                    $em->persist($interventionReport);
                    $em->flush();

                    return $this->redirectToRoute('intervention_report', [
                        'id' => $intervention->getId(),
                    ]);
                    break;

                case "previous":
                    $interventionReport->setStep($step - 1);
                    $em->persist($interventionReport);
                    $em->flush();

                    return $this->redirectToRoute('intervention_report', [
                        'id' => $intervention->getId(),
                    ]);
                    break;

                case "restart":
                    $interventionReport->setStep(1);
                    $em->persist($interventionReport);
                    $em->flush();

                    return $this->redirectToRoute('intervention_report', [
                        'id' => $intervention->getId(),
                    ]);
                    break;
            }
        }

        $softwares = [];
        $booklets = $br->findAll();
        $actions = $ar->findAll();
        $technicians = $tr->findAll();
        $billingLine = new BillingLine();
        $billingLineForm = $this->createForm(BillingLineType::class, $billingLine);
        $billingLineForm->handleRequest($request);

        switch ($step) {
            case 1:
                $irTechnicians = $interventionReport->getTechnicians();
                foreach ($irTechnicians as $irTechnician) {
                    $interventionReport->removeTechnician($irTechnician);
                }
                $em->flush();

                if ($request->request->has('data')) {

                    if ($request->request->has('technicians')) {
                        $technicians = $request->request->get('technicians');

                        foreach ($technicians as $technician) {
                            $technician = $tr->findOneById($technician);
                            $interventionReport->addTechnician($technician);
                            $em->persist($interventionReport);
                        }
                    }

                    $interventionReport->setStep($step + 1);
                    $em->persist($interventionReport);
                    $em->flush();

                    return $this->redirectToRoute('intervention_report', [
                        'id' => $intervention->getId(),
                    ]);
                }
                break;

            case 2:
                $intervention->getInterventionReport()->setSeverityProblem([]);
                $irSoftwares = $sirr->findAllByReportAndAction($interventionReport->getId(), "Nettoyage");
                foreach ($irSoftwares as $ele) {
                    $em->remove($ele);
                }
                $interventionReport->setInternalAnalysis(null);
                $em->flush();

                $softwares = $sr->findAllByType('Nettoyage');

                if ($request->request->has('data')) {

                    if ($request->request->has('cleaning-software')) {

                        $cleaningSoftwares = $request->request->get('cleaning-software');
                        foreach ($cleaningSoftwares as $softwareId) {

                            $software = $sr->findOneById($softwareId);
                            $softwareOperation = new SoftwareInterventionReport();
                            $softwareOperation->setSoftware($software);
                            $softwareOperation->setInterventionReport($interventionReport);
                            $softwareOperation->setAction('Nettoyage');
                            $em->persist($softwareOperation);
                        }
                    }

                    if ($request->request->has('severity-problem')) {
                        $severityProblems = $request->request->get('severity-problem');
                        $interventionReport->setSeverityProblem($severityProblems);
                    }

                    if ($request->request->has('internal-analysis')) {
                        $internalAnalysis = $request->request->get('internal-analysis');
                        $interventionReport->setInternalAnalysis($internalAnalysis);
                    }

                    $severity = $request->request->get('severity');
                    $interventionReport->setSeverity($severity);
                    $interventionReport->setStep($step + 1);
                    $em->persist($interventionReport);
                    $em->flush();

                    return $this->redirectToRoute('intervention_report', [
                        'id' => $intervention->getId(),
                    ]);
                }
                break;

            case 3:
                $irActions = $interventionReport->getActions();
                foreach ($irActions as $irAction) {
                    $interventionReport->removeAction($irAction);
                }
                $em->flush();

                if ($request->request->has('data')) {

                    if ($request->request->has('actions')) {
                        $actions = $request->request->get('actions');

                        foreach ($actions as $action) {
                            $action = $ar->findOneById($action);
                            $interventionReport->addAction($action);
                            $em->persist($interventionReport);
                        }
                    }

                    $interventionReport->setStep($step + 1);
                    $em->persist($interventionReport);
                    $em->flush();

                    return $this->redirectToRoute('intervention_report', [
                        'id' => $intervention->getId(),
                    ]);
                }
                break;

            case 4:
                $irSoftwares = $sirr->findAllByReportAndAction($interventionReport->getId(), "Installé");
                foreach ($irSoftwares as $ele) {
                    $em->remove($ele);
                }
                $irSoftwares = $sirr->findAllByReportAndAction($interventionReport->getId(), "Mis à jour");
                foreach ($irSoftwares as $ele) {
                    $em->remove($ele);
                }
                $em->flush();

                $softwares = $sr->findAll();

                if ($request->request->has('data')) {

                    $parametersList = $request->request->all();
                    $parametersLength = count($parametersList);

                    $parametersList = array_slice($parametersList, 0, $parametersLength - 1, true);
                    $parametersList = array_slice($parametersList, 1, $parametersLength, true);
                    $parametersLength = count($parametersList);

                    $parametersKeys = array_keys($parametersList);

                    for ($i = 0; $i < $parametersLength; $i++) {
                        $parameter = explode("-", $parametersKeys[$i]);
                        $softwareId = $parameter[1];
                        $action = $parametersList[$parametersKeys[$i]];

                        $software = $sr->findOneById($softwareId);
                        $softwareOperation = new SoftwareInterventionReport();
                        $softwareOperation->setSoftware($software);
                        $softwareOperation->setInterventionReport($interventionReport);
                        $softwareOperation->setAction($action);
                        $em->persist($softwareOperation);
                    }

                    $interventionReport->setStep($step + 1);
                    $em->persist($interventionReport);
                    $em->flush();

                    return $this->redirectToRoute('intervention_report', [
                        'id' => $intervention->getId(),
                    ]);
                }
                break;

            case 5:
                $interventionReport->setWindowsInstall([]);
                $interventionReport->setWindowsVersion(null);
                $em->flush();

                if ($request->request->has('data')) {

                    if ($request->request->has('windows-install')) {
                        $windowsInstalls = $request->request->get('windows-install');
                        $interventionReport->setWindowsInstall($windowsInstalls);
                    }

                    if ($request->request->has('windows-version')) {
                        $windowsVersion = $request->request->get('windows-version');
                        $interventionReport->setWindowsVersion($windowsVersion);
                    }

                    $interventionReport->setStep($step + 1);
                    $em->persist($interventionReport);
                    $em->flush();

                    return $this->redirectToRoute('intervention_report', [
                        'id' => $intervention->getId(),
                    ]);
                }
                break;

            case 6:
                $irBooklets = $interventionReport->getBooklets();
                foreach ($irBooklets as $irBooklet) {
                    $interventionReport->removeBooklet($irBooklet);
                }
                $em->flush();

                if ($request->request->has('data')) {

                    if ($request->request->has('booklets')) {
                        $booklets = $request->request->get('booklets');

                        foreach ($booklets as $booklet) {
                            $booklet = $br->findOneById($booklet);
                            $interventionReport->addBooklet($booklet);
                            $em->persist($interventionReport);
                        }
                    }
                    $interventionReport->setStep($step + 1);
                    $em->persist($interventionReport);
                    $em->flush();

                    return $this->redirectToRoute('intervention_report', [
                        'id' => $intervention->getId(),
                    ]);
                }
                break;

            case 7:
                $interventionReport->setComment(null);

                if ($request->request->has('data')) {

                    if ($request->request->has('comment')) {
                        $comment = $request->request->get('comment');

                        $interventionReport->setComment($comment);
                    }
                    $interventionReport->setStep($step + 1);
                    $em->persist($interventionReport);
                    $em->flush();

                    return $this->redirectToRoute('intervention_report', [
                        'id' => $intervention->getId(),
                    ]);
                }
                break;

            case 8:
                if ($request->request->has('delete-billing-line')) {
                    $billingLineId = $request->request->get('billing-line-id');

                    $billingLine = $blr->findOneById($billingLineId);
                    $intervention->removeBillingLine($billingLine);

                    $totalPrice = $this->atediHelper->strTotalPrice($intervention);
                    $intervention->setTotalPrice($totalPrice);

                    $em->persist($intervention);
                    $em->flush();

                    return $this->redirectToRoute('intervention_report', [
                        'id' => $intervention->getId(),
                    ]);
                }

                if ($billingLineForm->isSubmitted() && $billingLineForm->isValid()) {

                    $em = $em;

                    $billingLine->setIntervention($intervention);
                    $em->persist($billingLine);
                    $em->flush();

                    $totalPrice = $this->atediHelper->strTotalPrice($intervention);
                    $intervention->setTotalPrice($totalPrice);
                    $em->persist($intervention);
                    $em->flush();

                    return $this->redirectToRoute('intervention_report', [
                        'id' => $intervention->getId(),
                    ]);
                }
                break;
        }

        return $this->render('intervention/report.html.twig', [
            'intervention' => $intervention,
            'softwares' => $softwares,
            'booklets' => $booklets,
            'actions' => $actions,
            'technicians' => $technicians,
            'form' => $billingLineForm->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="intervention_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Intervention $intervention): Response
    {
        $form = $this->createForm(InterventionType::class, $intervention);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $totalPrice = $this->atediHelper->strTotalPrice($intervention);

            $intervention->setTotalPrice($totalPrice);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('intervention_show', [
                'id' => $intervention->getId(),
            ]);
        }

        return $this->render('intervention/edit.html.twig', [
            'intervention' => $intervention,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="intervention_delete", methods={"DELETE"})
     */
    public function delete(Request $request, EntityManagerInterface $em, Intervention $intervention, BillingLineRepository $blr): Response
    {
        if ($this->isCsrfTokenValid('delete' . $intervention->getId(), $request->request->get('_token'))) {

            $billingLines = $blr->findAllByIntervention($intervention);

            foreach ($billingLines as $billingLine) {
                $em->remove($billingLine);
            }

            $em = $this->getDoctrine()->getManager();
            $em->remove($intervention);
            $em->flush();
        }

        return $this->redirectToRoute('intervention_index');
    }
}
