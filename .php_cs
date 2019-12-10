<?php

$config = PhpCsFixer\Config::create()
    ->setRiskyAllowed(true)
    ->setUsingCache(false)
    ->setRules([
        '@Symfony' => true,
        'no_superfluous_phpdoc_tags' => []
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->exclude('vendor')
            ->exclude('templates')
            ->exclude('translations')
            ->exclude('docker')
            ->exclude('public')
            ->exclude('bin')
            ->exclude('config')
            ->in(__DIR__)
            ->ignoreVCS(true)
            ->files()
    )
;

return $config;
