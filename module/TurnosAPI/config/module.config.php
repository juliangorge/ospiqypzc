<?php
namespace TurnosAPI;

return [
	'router' => [
	    'routes' => [
	        'turnos-api' => [
	            'type' => 'Literal',
	            'options' => [
	                'route' => '/shifts',
	                'defaults' => [
	                	'controller' => Controller\IndexController::class,
	                    'action' => 'index',
	                ],
	            ],
	            'may_terminate' => true,
	            'child_routes' => [
	                'medical_centers' => [
	                    'type' => 'Segment',
	                    'options' => [
	                        'route' => '/medical_centers[/:id]',
	                        'defaults' => [
	                            'controller' => Controller\MedicalCenterController::class,
	                            'action' => 'index'
	                        ],
	                        'constraints' => [
	                            'id' => '[0-9]+',
	                        ],
	                    ],
	                    'may_terminate' => true,
	                    'child_routes' => [
	                        'specialties' => [
	                            'type' => 'Segment',
	                            'options' => [
	                                'route' => '/specialties',
	                                'defaults' => [
	                                    'action' => 'getSpecialties',
	                                ],
	                            ],
	                        ],
	                    ],
	                ],
	                'professionals' => [
	                    'type' => 'Segment',
	                    'options' => [
	                        'route' => '/professionals[/:id]',
	                        'defaults' => [
	                            'controller' => Controller\ProfessionalController::class,
	                            'action' => 'index'
	                        ],
	                        'constraints' => [
	                            'id' => '[0-9]+',
	                        ],
	                    ],
	                    'may_terminate' => true,
	                    'child_routes' => [
	                        'specialties' => [
	                            'type' => 'Segment',
	                            'options' => [
	                                'route' => '/specialties',
	                                'defaults' => [
	                                    'action' => 'getSpecialties',
	                                ],
	                            ],
	                        ],
	                        'getDaysByMedicalCenter' => [
	                            'type' => 'Segment',
	                            'options' => [
	                                'route' => '/medical_center/:medical_center/days',
	                                'defaults' => [
	                                    'action' => 'getDaysByMedicalCenter',
	                                ],
			                        'constraints' => [
			                            'medical_center' => '[0-9]+',
			                        ],
	                            ],
	                        ],
	                        'getTimeByDayAndMedicalCenter' => [
	                            'type' => 'Segment',
	                            'options' => [
	                                'route' => '/medical_center/:medical_center/days/:day/times',
	                                'defaults' => [
	                                    'action' => 'getTimeByDayAndMedicalCenter',
	                                ],
	                            ],
	                        ],
	                    ],
	                ],
	                'specialties' => [
	                    'type' => 'Segment',
	                    'options' => [
	                        'route' => '/specialties[/:id]',
	                        'defaults' => [
	                            'controller' => Controller\SpecialtyController::class,
	                            'action' => 'index'
	                        ],
	                        'constraints' => [
	                            'id' => '[0-9]+',
	                        ],
	                    ],
	                    'may_terminate' => true,
	                    'child_routes' => [
	                        'professionals' => [
	                            'type' => 'Segment',
	                            'options' => [
	                                'route' => '/professionals',
	                                'defaults' => [
	                                    'action' => 'getProfessionals',
	                                ],
	                            ],
	                        ],
	                    ],
	                ],
	                'medical_shifts' => [
	                    'type' => 'Segment',
	                    'options' => [
	                        'route' => '/medical_shifts[/:id]',
	                        'defaults' => [
	                            'controller' => Controller\MedicalShiftController::class,
	                            'action' => 'index'
	                        ],
	                        'constraints' => [
	                            'id' => '[0-9]+',
	                        ],
	                    ],
	                ],
	                'medical_shifts_by_professional' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/medical_shifts/professional_dni/:dni',
                            'defaults' => [
                            	'controller' => Controller\MedicalShiftController::class,
                                'action' => 'getByProfessionalDni',
                            ],
                        ],
                    ],
	                'medical_shifts_by_dni' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/medical_shifts/dni/:dni',
                            'defaults' => [
                            	'controller' => Controller\MedicalShiftController::class,
                                'action' => 'getByDni',
                            ],
                        ],
                    ],
	            ],
	        ],
	    ],
	],
	'controllers' => [
		'factories' => [
            Controller\MedicalCenterController::class => Controller\Factory\ControllerFactory::class,
            Controller\ProfessionalController::class => Controller\Factory\ControllerFactory::class,
            Controller\SpecialtyController::class => Controller\Factory\ControllerFactory::class,
            Controller\MedicalShiftController::class => Controller\Factory\ControllerFactory::class,
	    ],
	],
	'service_manager' => [
	    'factories' => [
	    	Auth\AuthAdapter::class => Auth\Factory\AuthAdapterFactory::class,
	    ],
	],
	'middleware_pipeline' => [
	    'api_authentication' => [
	        'middleware' => Middleware\AuthenticationMiddleware::class,
	        'priority' => 100,
	        'options' => [
	            'auth_adapter' => Auth\AuthAdapter::class,
	            'auth_error_message' => 'Unauthorized',
	        ],
	    ],
	],
];