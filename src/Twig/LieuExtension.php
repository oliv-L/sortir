<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class LieuExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('filter_name', [$this, 'doSomething']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('creee', [$this, 'creee']),
            new TwigFunction('ouverte', [$this, 'ouverte']),
            new TwigFunction('enCours', [$this, 'enCours']),
            new TwigFunction('cloturee', [$this, 'cloturee']),
            new TwigFunction('finie', [$this, 'finie']),
            new TwigFunction('annulee', [$this, 'annulee']),
            new TwigFunction('archivee', [$this, 'archivee']),

        ];
    }
    public static function creee(){
        return 'créée';
    }
    public static function ouverte(){
        return 'ouverte';
    }
    public static function enCours(){
        return 'en cours';
    }
    public static function cloturee(){
        return 'cloturée';
    }
    public static function finie(){
        return 'passée';
    }
    public static function annulee(){
        return 'annulée';
    }
    public static function archivee(){
        return 'archivée';
    }
    public function doSomething($value)
    {
        // ...
    }
}
