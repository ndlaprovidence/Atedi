<?php

namespace App\Controller;

use App\Entity\props;
use App\Form\propsType;
use App\Repository\propsRepository;
use App\Repository\InterventionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/props")
 */
class propsController extends AbstractController
{
    /**
     * @Route("/", name="props_index", methods={"GET"})
     */
    public function index(propsRepository $propsRepository): Response
    {
        return $this->render('props/index.html.twig', [
            'props' => $propsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="props_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $props = new props();
        $form = $this->createForm(propsType::class, $props);
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
    public function show(props $props, InterventionRepository $interventionRepository): Response
    {
        $interventions = $interventionRepository->findAllByprops($props->getId());

        return $this->render('props/show.html.twig', [
            'props' => $props,
            'interventions' => $interventions,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="props_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, props $props): Response
    {
        $form = $this->createForm(propsType::class, $props);
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
    public function delete(Request $request, props $props): Response
    {
        if ($this->isCsrfTokenValid('delete'.$props->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($props);
            $entityManager->flush();
        }

        return $this->redirectToRoute('props_index');
    }
}
