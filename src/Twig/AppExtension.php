<?php

namespace App\Twig;

use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;

class AppExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('intervStatus', [$this, 'statusFinder']),
            new TwigFunction('intervTypes', [$this, 'typesFinder']),
        ];
    }

    public function statusFinder($theStatus)
    {
        switch ($theStatus) {
            case 'En attente':
                return 'waiting';
                break;

            case 'En cours':
                return 'active';
                break;

            case 'Terminée':
                return 'finished';
                break;
        }
    }

    public function typesFinder($theTypes)
    {
        if (sizeof($theTypes) == 1) {
            switch ($theTypes[0]) {
                case 'Réparation':
                    return 'bg-red';
                    break;
    
                case 'Formatage':
                    return 'bg-green';
                    break;

                case 'Nettoyage':
                    return 'bg-blue';
                    break;

                default:
                    return 'bg-white';
                    break;
            }
        } else {
            return 'bg-black';
        }
    }
}