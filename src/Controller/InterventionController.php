<?php

namespace App\Controller;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\Intervention;
use App\Form\InterventionType;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
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

        $form = $this->createForm(InterventionType::class, $intervention);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($intervention);
            $this->em->flush();

            return $this->redirectToRoute('intervention_show', ['id' => $intervention->getId()]);
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
        if ($request->request->has('status')) {
            $this->em = $em;

            $newStatus = $request->request->get('status');
            
            $intervention->setStatus($newStatus);
            $this->em->persist($intervention);
            $this->em->flush();
        }

        if ($request->request->has('download')) {
            // Configure Dompdf according to your needs
            $pdfOptions = new Options();
            $pdfOptions->set('defaultFont', 'Arial');

            // Instantiate Dompdf with our options
            $dompdf = new Dompdf($pdfOptions);

            // Retrieve the HTML generated in our twig file
            $html = $this->renderView('intervention/pdf.html.twig', [
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

        return $this->render('intervention/show.html.twig', [
            'intervention' => $intervention,
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

            return $this->redirectToRoute('intervention_show', ['id' => $intervention->getId()]);
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
