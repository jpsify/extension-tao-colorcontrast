<?php
/**
 * Copyright (c) 2018 (original work) Open Assessment Technologies SA;
 */

use oat\taoColorContrast\scripts\install\RegisterItemThemes;
use oat\taoColorContrast\scripts\install\RegisterTestRunnerPlugins;
use oat\taoColorContrast\scripts\update\Updater;

return [
    'name' => 'taoColorContrast',
    'label' => 'taoColorContrast',
    'description' => 'TAO Premium feature: Color Contrast',
    'license' => 'Proprietary',
    'author' => 'Open Assessment Technologies SA',
    'managementRole' => 'http://www.tao.lu/Ontologies/generis.rdf#taoColorContrastManager',
    'acl' => [
        ['grant', 'http://www.tao.lu/Ontologies/generis.rdf#taoColorContrastManager', ['ext' => 'taoColorContrast']],
    ],
    'install' => [
        'php' => [
            RegisterTestRunnerPlugins::class,
            RegisterItemThemes::class,
        ]
    ],
    'update' => Updater::class,
    'uninstall' => [],
    'routes' => [
        '/taoColorContrast' => 'oat\\taoColorContrast\\controller'
    ],
    'constants' => [
        # views directory
        "DIR_VIEWS" => __DIR__ . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR,

        #BASE URL (usually the domain root)
        'BASE_URL' => ROOT_URL . 'taoColorContrast/',
    ],
    'extra' => [
        'structures' => __DIR__ . DIRECTORY_SEPARATOR . 'controller' . DIRECTORY_SEPARATOR . 'structures.xml',
    ]
];
