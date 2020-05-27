<?php

namespace App\Controller;

use App\Repository\InterventionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/statistics")
 */
class StatisticsController extends AbstractController
{
    /**
     * @Route("/", name="statistics_index", methods={"GET"})
     */
    public function index(InterventionRepository $interventionRepository)
    {
        $interventions = $interventionRepository->findAllOngoing();

        return $this->render('index/index.html.twig', [
            'interventions' => $interventions,
        ]);
    }
}