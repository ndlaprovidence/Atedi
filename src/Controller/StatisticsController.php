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
        // Chart
        $series = array(
            array(
                "name" => "Data test",    
                "data" => array(1,2,4,5,6,3,8),
            )
        );

        $ob = new Highchart();
        $ob->chart->renderTo('chart');  // The #id of the div where to render the chart
        $ob->title->text('Chart Title');
        $ob->xAxis->title(array('text'  => "Horizontal axis title"));
        $ob->yAxis->title(array('text'  => "Vertical axis title"));
        $ob->series($series);

        return $this->render('statistics/index.html.twig', [
            'chart' => $ob,
        ]);
    }
}