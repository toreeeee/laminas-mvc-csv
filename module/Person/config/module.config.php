<?php

namespace Person;

use Album\Controller\Person\src\Model\PersonTableFactory;
use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\AbstractFactory\ReflectionBasedAbstractFactory;


return [
    'controllers' => [
        'factories' => [
            \Album\Controller\Person\src\Controller\PersonController::class => ReflectionBasedAbstractFactory::class
        ],
    ],
    // The following section is new and should be added to your file:
    'router' => [
        'routes' => [
            'person' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/person[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => \Album\Controller\Person\src\Controller\PersonController::class,
                        'action' => 'index',
                    ],
                ],
            ],
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            'person' => __DIR__ . '/../view',
        ],
    ],
    'service_manager' => [
        'factories' => [
            \Album\Controller\Person\src\Model\PersonTable::class => PersonTableFactory::class,
        ],
    ]
];