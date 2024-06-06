<?php

namespace Person;

use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\Factory\InvokableFactory;
use Person\Controller\PersonController;
use Person\Factory\LaminasDbSqlCommandFactory;
use Person\Factory\LaminasDbSqlRepositoryFactory;
use Person\Factory\PersonControllerFactory;

return [
    "service_manager" => [
        "aliases" => [
            Model\PersonRepositoryInterface::class => Model\LaminasDbSqlRepository::class,
            Model\PersonCommandInterface::class => Model\LaminasDbSqlCommand::class,
        ],
        "factories" => [
            Model\LaminasDbSqlRepository::class => LaminasDbSqlRepositoryFactory::class,
            Model\LaminasDbSqlCommand::class => LaminasDbSqlCommandFactory::class,
        ]
    ],
    'controllers' => [
        'factories' => [
            PersonController::class => PersonControllerFactory::class,
        ]
    ],
    'router' => [
        // Open configuration for all possible routes
        'routes' => [
            // Define a new route called "blog"
            'person' => [
                // Define a "literal" route type:
                'type' => Literal::class,
                // Configure the route itself
                'options' => [
                    // Listen to "/blog" as uri:
                    'route' => '/person',
                    // Define default controller and action to be called when
                    // this route is matched
                    'defaults' => [
                        'controller' => Controller\PersonController::class,
                        'action' => 'index',
                    ],
                ],
                "may_terminate" => true,
                'child_routes'  => [
                    "add" => [
                        "type" => Segment::class,
                        "options" => [
                            "route" => "/add",
                            "defaults" => [
                                "action" => "add",
                            ],
                        ]
                    ],
                    "edit" => [
                        "type" => Segment::class,
                        "options" => [
                            "route" => "/:id/edit",
                            "defaults" => [
                                "action" => "edit",
                            ],
                            "constraints" => [
                                "id" => "[1-9]\d*",
                            ]
                        ]
                    ],
                    "delete" => [
                        "type" => Segment::class,
                        "options" => [
                            "route" => "/:id/delete",
                            "defaults" => [
                                "action" => "delete",
                            ],
                            "constraints" => [
                                "id" => "[1-9]\d*",
                            ]
                        ]
                    ],
                ],
            ],
        ],
    ],
    "view_manager" => [
        "template_path_stack" => [
            __DIR__ . '/../view',
        ]
    ]
];
