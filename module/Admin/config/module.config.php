<?php
namespace Admin;

use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;

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
                'child_routes'=>[
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
                    'union-news' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/union-news[/:action[/:id]]',
                            'defaults' => [
                                'controller'    => Controller\UnionNewsController::class,
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
                    'affiliates_prescriptions' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/affiliates_prescriptions[/:action[/:id]]',
                            'defaults' => [
                                'controller'    => Controller\AffiliatesPrescriptionsController::class,
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
                    'professionals' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/professionals[/:action[/:id]]',
                            'defaults' => [
                                'controller'    => Controller\ProfessionalsController::class,
                                'action'        => 'index',
                            ],
                        ],
                    ],
                    'clinical_histories' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/clinical_histories[/:action[/:id]]',
                            'defaults' => [
                                'controller'    => Controller\ClinicalHistoriesController::class,
                                'action'        => 'index',
                            ],
                        ],
                    ],
                    'vademecum' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/vademecum[/:action[/:id]]',
                            'defaults' => [
                                'controller'    => Controller\VademecumController::class,
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
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => Controller\Factory\ControllerFactory::class,
            Controller\NewsController::class => Controller\Factory\ControllerFactory::class,
            Controller\UnionNewsController::class => Controller\Factory\ControllerFactory::class,
            Controller\AffiliatesController::class => Controller\Factory\ControllerFactory::class,
            Controller\AffiliatesFamilyController::class => Controller\Factory\ControllerFactory::class,
            Controller\AffiliatesPrescriptionsController::class => Controller\Factory\ControllerFactory::class,
            Controller\AffiliatesAuthorizationsController::class => Controller\Factory\ControllerFactory::class,
            Controller\ProfessionalsController::class => Controller\Factory\ControllerFactory::class,
            Controller\ClinicalHistoriesController::class => Controller\Factory\ControllerFactory::class,
            Controller\VademecumController::class => Controller\Factory\ControllerFactory::class,
            Controller\UsersController::class => Controller\Factory\ControllerFactory::class,
        ],
    ],
    'controller_plugins' => [
        'factories' => [
            Controller\Plugin\AppPlugin::class => Controller\Plugin\Factory\AppPluginFactory::class,
        ],
        'aliases' => [
            'AppPlugin' => Controller\Plugin\Factory\AppPluginFactory::class,
        ]
    ],
    'access_filter' => [
        'options' => [
            'mode' => 'restrictive'
        ],
        'controllers' => [
            //@ autorizados, * todos
            /*Controller\IndexController::class => [
                ['actions' => '*', 'allow' => '@'],
            ],*/
            Controller\AffiliatesController::class => [
                ['actions' => 'import', 'allow' => '*'],
            ],
        ]
    ],
    'doctrine' => [
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => AnnotationDriver::class,
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
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'admin/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'admin/index/index'      => __DIR__ . '/../view/admin/index/index.phtml',
            'error/404'              => __DIR__ . '/../view/error/404.phtml',
            'error/index'            => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
        'strategies' => [
            'ViewJsonStrategy',
        ],
    ],
    'view_helper_config' => [
        'flashmessenger' => [
            'message_open_format'      => '<div%s><p class="mb-0">',
            'message_close_string'     => '</p></div>',
            'message_separator_string' => '</p><p>',
        ],
    ],
];
