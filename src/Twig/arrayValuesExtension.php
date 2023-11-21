<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;


class arrayValuesExtension extends AbstractExtension {

    public function getFunctions(): array
    {
        return [
            new TwigFunction('values', 'array_values'),
        ];
    }
    public function getFilters()
    {
        return array(
            new TwigFilter('values', 'array_values'),
        );
    }
}