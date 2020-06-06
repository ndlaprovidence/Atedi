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
            new TwigFunction('isWindows', [$this, 'windowsFinder']),
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

    public function windowsFinder($theOperatingSystem)
    {
        $isWindows = false;

        if (preg_match('/Windows|windows/', $theOperatingSystem)) {
            $isWindows = true;
        }

        return $isWindows;
    }
}