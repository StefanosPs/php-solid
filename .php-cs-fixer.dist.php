<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude('var')
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
        '@Symfony:risky' => true,
        '@PSR12' => true,
        '@PSR12:risky' => true,
        '@PHP83Migration' => true,
        'declare_strict_types' => true
    ])
    ->setFinder($finder)
    ->setRiskyAllowed(true)
;
