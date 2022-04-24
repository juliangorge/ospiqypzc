<?php
namespace Auth;

use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Laminas\Session\Storage\SessionArrayStorage;
use Laminas\Session\Validator\RemoteAddr;

return [
    'session_config' => [
        'cookie_lifetime' => 2*60*60*3*1,
        'gc_maxlifetime'  => 2*60*60*24*30
    ],
    'session_manager' => [
        'validators' => [
            //RemoteAddr::class
        ]
    ],
    'session_storage' => [
        'type' => SessionArrayStorage::class
    ],
    'router' => [
        'routes' => [
            'login' => [
                'type' => 'Literal',
                'options' => [
                    'route'    => '/login',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'login',
                    ],
                ],
            ],
            'logout' => [
                'type' => 'Literal',
                'options' => [
                    'route'    => '/logout',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'logout',
                    ],
                ],
            ],
            /*
            'register' => [
                'type' => 'Literal',
                'options' => [
                    'route'    => '/register',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'register',
                    ],
                ],
            ],
            'reset-password' => [
                'type' => 'Literal',
                'options' => [
                    'route'    => '/reset-password',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'resetPassword',
                    ],
                ],
            ],
            'set-password' => [
                'type' => 'Literal',
                'options' => [
                    'route'    => '/set-password',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'setPassword',
                    ],
                ],
            ],
            */
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => Controller\Factory\ControllerFactory::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            \Laminas\Authentication\AuthenticationService::class => Service\Factory\AuthenticationServiceFactory::class,
            Service\AuthAdapter::class => Service\Factory\AuthAdapterFactory::class,
            Service\AuthManager::class => Service\Factory\AuthManagerFactory::class,
            Service\UserManager::class => Service\Factory\UserManagerFactory::class,
        ],
    ],
    'access_filter' => [
        'options' => [
            'mode' => 'restrictive'
        ],
        'controllers' => [
            Controller\IndexController::class => [
                ['actions' => '*', 'allow' => '*']
            ],
        ]
    ],
    'doctrine' => [
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src/Entity']
            ],
            'orm_default' => [
                'drivers' => [
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                ]
            ]
        ]
    ],
    'view_manager' => [
        'template_map' => [
            'auth/layout' => __DIR__ . '/../view/layout/layout.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
        'strategies' => [
            'ViewJsonStrategy',
        ],
    ],
];
