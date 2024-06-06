<?php

namespace Blog;

use Blog\Factory\DeleteControllerFactory;
use Blog\Factory\LaminasDbSqlCommandFactory;
use Blog\Factory\LaminasDbSqlRepositoryFactory;
use Blog\Factory\ListControllerFactory;
use Blog\Factory\PostControllerFactory;
use Blog\Factory\WriteControllerFactory;
use Laminas\Form\Factory;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\Factory\InvokableFactory;


return [
    "service_manager" => [
        "aliases" => [
            Model\PostRepositoryInterface::class => Model\LaminasDbSqlRepository::class,
            Model\PostCommandInterface::class => Model\LaminasDbSqlCommand::class
        ],
        'factories' => [
            Model\PostRepository::class => InvokableFactory::class,
            Model\LaminasDbSqlRepository::class => LaminasDbSqlRepositoryFactory::class,
            Model\PostCommand::class => InvokableFactory::class,
            Model\LaminasDbSqlCommand::class => LaminasDbSqlCommandFactory::class,
            ],
    ],
    'controllers' => [
        'factories' => [
            Controller\ListController::class => ListControllerFactory::class,
            Controller\WriteController::class => WriteControllerFactory::class,
            Controller\DeleteController::class => DeleteControllerFactory::class,
            Controller\PostController::class => PostControllerFactory::class
        ],
    ],
    // This lines opens the configuration for the RouteManager
    'router' => [
        // Open configuration for all possible routes
        'routes' => [
            // Define a new route called "blog"
            'blog' => [
                // Define a "literal" route type:
                'type' => Literal::class,
                // Configure the route itself
                'options' => [
                    // Listen to "/blog" as uri:
                    'route' => '/blog',
                    // Define default controller and action to be called when
                    // this route is matched
                    'defaults' => [
                        'controller' => Controller\ListController::class,
                        'action' => 'index',
                    ],
                ],
                "may_terminate" => true,
                'child_routes'  => [
                    'detail' => [
                        'type' => Segment::class,
                        'options' => [
                            'route'    => '/:id',
                            'defaults' => [
                                'action' => 'index',
                                "controller" => Controller\PostController::class
                            ],
                            'constraints' => [
                                'id' => '\d+',
                            ],
                        ],
                    ],
                    'add' => [
                        'type' => Literal::class,
                        'options' => [
                            'route'    => '/add',
                            'defaults' => [
                                'controller' => Controller\WriteController::class,
                                'action'     => 'add',
                            ],
                        ],
                    ],
                    "edit" => [
                        "type" => Segment::class,
                        "options" => [
                            "route" => "/edit/:id",
                            "defaults" => [
                                "controller" => Controller\WriteController::class,
                                "action" => "edit",
                            ],
                            "constraints" => [
                                "id" => "[1-9]\d*",
                            ]
                        ]
                    ],
                    "delete" => [
                        "type" => Segment::class,
                        'options' => [
                            'route' => '/delete/:id',
                            'defaults' => [
                                'controller' => Controller\DeleteController::class,
                                'action'     => 'delete',
                            ],
                            'constraints' => [
                                'id' => '[1-9]\d*',
                            ],
                        ],
                    ]
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
