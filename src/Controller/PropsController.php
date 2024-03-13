<?php

namespace App\Controller;

use App\Entity\Props;
use App\Form\PropsType;
use App\Repository\PropsRepository;
use App\Repository\InterventionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/props')]
class PropsController extends AbstractController
{
    /**
     * @Route("/", name="props_index", methods={"GET"})
     */
    public function index(PropsRepository $propsRepository): Response
    {
        return $this->render('props/index.html.twig', [
            'propss' => $propsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="props_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $props = new Props();
        $form = $this->createForm(PropsType::class, $props);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($props);
            $entityManager->flush();

            if ( $request->query->has('s') == 'intervention') {
                return $this->redirectToRoute('intervention_new');
            }
            
            return $this->redirectToRoute('props_show', [
                'id' => $props->getId(),
            ]);
        }

        return $this->render('props/new.html.twig', [
            'props' => $props,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="props_show", methods={"GET"})
     */
    public function show(Props $props, InterventionRepository $interventionRepository): Response
    {
        $interventions = $interventionRepository->findAllByProps($props->getId());

        return $this->render('props/show.html.twig', [
            'props' => $props,
            'interventions' => $interventions,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="props_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Props $props): Response
    {
        $form = $this->createForm(PropsType::class, $props);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('props_show', [
                'id' => $props->getId(),
            ]);
        }

        return $this->render('props/edit.html.twig', [
            'props' => $props,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="props_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Props $props): Response
    {
        if ($this->isCsrfTokenValid('delete'.$props->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($props);
            $entityManager->flush();
        }

        return $this->redirectToRoute('props_index');
    }
}
