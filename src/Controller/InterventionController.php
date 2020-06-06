<?php

namespace App\Controller;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\Intervention;
use App\Form\InterventionType;
use App\Entity\InterventionReport;
use App\Repository\ClientRepository;
use App\Repository\SoftwareRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\SoftwareInterventionReport;
use App\Repository\InterventionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/intervention")
 */
class InterventionController extends AbstractController
{
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
    public function new(Request $request, ClientRepository $cr, EntityManagerInterface $em): Response
    {
        $this->em = $em;

        $intervention = new Intervention();
        $interventionReport = new InterventionReport();

        $form = $this->createForm(InterventionType::class, $intervention);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $interventionReport->setStep(1);
            $this->em->persist($interventionReport);
            $this->em->flush();

            $intervention->setInterventionReport($interventionReport);
            $this->em->persist($intervention);
            $this->em->flush();

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
    public function show(Request $request, Intervention $intervention, EntityManagerInterface $em): Response
    {
        $theStatus = $intervention->getStatus();

        if ($request->request->has('status')) {
            $newStatus = $request->request->get('status');

            if ($theStatus != $newStatus) {
                $this->em = $em;

                $intervention->setStatus($newStatus);

                if ( $newStatus == 'Terminée' ) {
                    $intervention->setReturnDate(new \DateTime());
                }
                $this->em->persist($intervention);
                $this->em->flush();
            }
        }

        if ($theStatus == 'En cours' || $theStatus == 'Terminée' ) {
            if ($request->request->has('download')) {
                // Configure Dompdf according to your needs
                $pdfOptions = new Options();
                $pdfOptions->set('defaultFont', 'Arial');

                // Instantiate Dompdf with our options
                $dompdf = new Dompdf($pdfOptions);

                // Retrieve the HTML generated in our twig file
                $html = $this->renderView('intervention/request_pdf.html.twig', [
                    'intervention' => $intervention,
                ]);

                // Load HTML to Dompdf
                $dompdf->loadHtml($html);

                // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
                $dompdf->setPaper('A4', 'portrait');

                // Render the HTML as PDF
                $dompdf->render();

                $pdfName = $intervention->getClient()->getLastName().'-'.time().'.pdf';
                // Output the generated PDF to Browser (force download)
                $dompdf->stream($pdfName, [
                    "Attachment" => true
                ]);
            }
        }

        return $this->render('intervention/show.html.twig', [
            'intervention' => $intervention,
        ]);
    }

    /**
     * @Route("/{id}/report", name="intervention_report", methods={"GET","POST"})
     */
    public function report(Request $request, EntityManagerInterface $em, Intervention $intervention, SoftwareRepository $sr): Response
    {
        $this->em = $em;

        $interventionReport = $intervention->getInterventionReport();
        $step = $interventionReport->getStep();
        $softwares = [];

        switch ($step) {
            case 1:
                $softwares = $sr->findAllByType('Nettoyage');

                if ($request->request->has('action')) {
                    if ($request->request->has('cleaning-software')) {

                        $cleaningSoftwares = $request->request->get('cleaning-software');

                        foreach ( $cleaningSoftwares as $softwareId ) {
                        
                            $software = $sr->findOneById($softwareId);
                            $softwareOperation = new SoftwareInterventionReport();
                            $softwareOperation->setSoftware($software);
                            $softwareOperation->setInterventionReport($interventionReport);
                            $softwareOperation->setAction('Nettoyage');
                            $this->em->persist($softwareOperation);
                        }
                    }
                    
                    $severity = $request->request->get('severity');
                    $interventionReport->setSeverity($severity);
                    $interventionReport->setStep($step+1);
                    $this->em->persist($interventionReport);
                    $this->em->flush();

                    return $this->redirectToRoute('intervention_report', [
                        'id' => $intervention->getId(),
                    ]);
                }
                break;

            case 2:
                $softwares = $sr->findAll();

                if ($request->request->has('action')) {

                    $parametersList = $request->request->all();
                    $parametersLength = count($parametersList);

                    $parametersList = array_slice($parametersList, 0, $parametersLength-1, true);
                    $parametersLength--;

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
                        $this->em->persist($softwareOperation);
                    }

                    $interventionReport->setStep($step+1);
                    $this->em->persist($interventionReport);
                    $this->em->flush();

                    return $this->redirectToRoute('intervention_report', [
                        'id' => $intervention->getId(),
                    ]);
                }
                break;

            case 3:
                if ($request->request->has('action')) {

                    if ($request->request->has('windows-install')) {
                        $windowsInstalls = $request->request->get('windows-install');
                        $interventionReport->setWindowsInstall($windowsInstalls);
                    }

                    $interventionReport->setStep($step+1);
                    $this->em->persist($interventionReport);
                    $this->em->flush();

                    return $this->redirectToRoute('intervention_report', [
                        'id' => $intervention->getId(),
                    ]);
                }
                break;
        }

        return $this->render('intervention/report.html.twig', [
            'intervention' => $intervention,
            'softwares' => $softwares,
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
    public function delete(Request $request, Intervention $intervention): Response
    {
        if ($this->isCsrfTokenValid('delete'.$intervention->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($intervention);
            $entityManager->flush();
        }

        return $this->redirectToRoute('intervention_index');
    }
}
