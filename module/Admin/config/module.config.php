<?php 

declare(strict_types=1);

namespace Admin;

use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'admin' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/admin',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'=> [
                    'dashboard' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/dashboard[/:action[/:id]]',
                            'defaults' => [
                                'controller'    => Controller\IndexController::class,
                                'action'        => 'index',
                            ],
                        ],
                    ],
                    'news' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/news[/:action[/:id]]',
                            'defaults' => [
                                'controller'    => Controller\NewsController::class,
                                'action'        => 'index',
                            ],
                        ],
                    ],
                    'union_news' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/union_news[/:action[/:id]]',
                            'defaults' => [
                                'controller'    => Controller\UnionNewsController::class,
                                'action'        => 'index',
                            ],
                        ],
                    ],
                    'users' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/users[/:action[/:id]]',
                            'defaults' => [
                                'controller'    => Controller\UsersController::class,
                                'action'        => 'index',
                            ],
                        ],
                    ],
                    'affiliates' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/affiliates[/:action[/:id]]',
                            'defaults' => [
                                'controller'    => Controller\AffiliatesController::class,
                                'action'        => 'index',
                            ],
                        ],
                    ],
                    'affiliates_family' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/affiliates_family[/:action[/:id]]',
                            'defaults' => [
                                'controller'    => Controller\AffiliatesFamilyController::class,
                                'action'        => 'index',
                            ],
                        ],
                    ],
                    'affiliates_authorizations' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/affiliates_authorizations[/:action[/:id]]',
                            'defaults' => [
                                'controller'    => Controller\AffiliatesAuthorizationsController::class,
                                'action'        => 'index',
                            ],
                        ],
                    ],
                    'affiliates_claims' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/affiliates_claims[/:action[/:id]]',
                            'defaults' => [
                                'controller'    => Controller\AffiliatesClaimsController::class,
                                'action'        => 'index',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class    => Controller\Factory\ControllerFactory::class,
            Controller\NewsController::class     => Controller\Factory\ControllerFactory::class,
            Controller\UnionNewsController::class     => Controller\Factory\ControllerFactory::class,
            Controller\UsersController::class    => Controller\Factory\ControllerFactory::class,
            Controller\AffiliatesController::class    => Controller\Factory\ControllerFactory::class,
            Controller\AffiliatesFamilyController::class    => Controller\Factory\ControllerFactory::class,
            Controller\AffiliatesAuthorizationsController::class    => Controller\Factory\ControllerFactory::class,
            Controller\AffiliatesClaimsController::class    => Controller\Factory\ControllerFactory::class,
        ],
    ],
    'controller_plugins' => [
        'factories' => [
            Plugin\AppPlugin::class => Plugin\Factory\AppPluginFactory::class,
        ],
        'aliases' => [
            'appPlugin' => Plugin\AppPlugin::class
        ]
    ],
    'access_filter' => [
        'options' => [
            'mode' => 'restrictive'
        ],
        'controllers' => [
            //@ autorizados, * todos
            Controller\AffiliatesController::class => [
                ['actions' => ['importFromCron'], 'allow' => '*'],
            ],
        ]
    ],
    'doctrine' => [
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => \Doctrine\ORM\Mapping\Driver\AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [ __DIR__ . '/../src/Entity']
            ],
            'orm_default' => [
                'drivers' => [
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                ]
            ]
        ]
    ],
    'view_helper_config' => [
        'flashmessenger' => [
            'message_open_format' => '<div%s>',
            'message_close_string' => '&nbsp;<small><button type="button" class="btn-close btn-sm" data-bs-dismiss="alert" aria-label="Close"></button></small></div>',
            'message_separator_string' => '</p><p>',
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/admin'           => __DIR__ . '/../view/layout/admin.phtml',
            'admin/error'            => __DIR__ . '/../view/layout/error.phtml',
            'admin/index/index'		 => __DIR__ . '/../view/admin/index/index.phtml',
            'error/404'              => __DIR__ . '/../view/error/404.phtml',
            'error/index'            => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];