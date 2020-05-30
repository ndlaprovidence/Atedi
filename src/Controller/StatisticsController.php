<?php

namespace App\Controller;

use App\Repository\InterventionRepository;
use Ob\HighchartsBundle\Highcharts\Highchart;
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
        // Load interventions by technicians chart
        $ob = new Highchart();
        $ob->chart->renderTo('interventionTechnicians');
        $ob->title->text('Browser market shares at a specific website in 2010');
        $ob->plotOptions->pie(array(
            'allowPointSelect'  => true,
            'cursor'    => 'pointer',
            'dataLabels'    => array('enabled' => false),
            'showInLegend'  => true
        ));
        $data = array(
            array('Firefox', 45.0),
            array('IE', 26.8),
            array('Chrome', 12.8),
            array('Safari', 8.5),
            array('Opera', 6.2),
            array('Others', 0.7),
        );
        $ob->series(array(array('type' => 'pie','name' => 'Browser share', 'data' => $data)));

        return $this->render('statistics/index.html.twig', [
            'chart' => $ob,
        ]);
    }
}