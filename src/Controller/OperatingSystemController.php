<?php

namespace App\Controller;

use App\Entity\OperatingSystem;
use App\Form\OperatingSystemType;
use App\Repository\OperatingSystemRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/operating/system")
 */
class OperatingSystemController extends AbstractController
{
    /**
     * @Route("/", name="operating_system_index", methods={"GET"})
     */
    public function index(OperatingSystemRepository $operatingSystemRepository): Response
    {
        return $this->render('operating_system/index.html.twig', [
            'operating_systems' => $operatingSystemRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="operating_system_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $operatingSystem = new OperatingSystem();
        $form = $this->createForm(OperatingSystemType::class, $operatingSystem);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($operatingSystem);
            $entityManager->flush();

            return $this->redirectToRoute('operating_system_index');
        }

        return $this->render('operating_system/new.html.twig', [
            'operating_system' => $operatingSystem,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="operating_system_show", methods={"GET"})
     */
    public function show(OperatingSystem $operatingSystem): Response
    {
        return $this->render('operating_system/show.html.twig', [
            'operating_system' => $operatingSystem,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="operating_system_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, OperatingSystem $operatingSystem): Response
    {
        $form = $this->createForm(OperatingSystemType::class, $operatingSystem);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('operating_system_index');
        }

        return $this->render('operating_system/edit.html.twig', [
            'operating_system' => $operatingSystem,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="operating_system_delete", methods={"DELETE"})
     */
    public function delete(Request $request, OperatingSystem $operatingSystem): Response
    {
        if ($this->isCsrfTokenValid('delete'.$operatingSystem->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($operatingSystem);
            $entityManager->flush();
        }

        return $this->redirectToRoute('operating_system_index');
    }
}
