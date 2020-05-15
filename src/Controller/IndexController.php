<?php

namespace App\Controller;

use App\Repository\InterventionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/")
 */
class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(InterventionRepository $interventionRepository)
    {
        return $this->render('index/index.html.twig', [
            'interventions' => $interventionRepository->findAll(),
        ]);
    }
}