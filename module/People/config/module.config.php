<?php

namespace People;

use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\AbstractFactory\ReflectionBasedAbstractFactory;
use People\Model\PersonTableFactory;


return [
    'controllers' => [
        'factories' => [
            Controller\PeopleController::class => ReflectionBasedAbstractFactory::class
        ],
    ],
    // The following section is new and should be added to your file:
    'router' => [
        'routes' => [
            'people' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/people[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\PeopleController::class,
                        'action' => 'index',
                    ],
                ],
            ],
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            'album' => __DIR__ . '/../view',
        ],
    ],
    'service_manager' => [
        'factories' => [
            Model\PersonTable::class => PersonTableFactory::class,
        ],
    ]
];