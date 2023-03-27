<?php
/*
/medical_centers[/:medical_center_id]
/medical_centers/:medical_center_id
/medical_centers/:medical_center_id/specialties[/:specialty_id]
/medical_centers/:medical_center_id/specialties/:specialty_id/professionals
/medical_centers/:medical_center_id/professionals[/:professional_id]
/medical_centers/:medical_center_id/professionals/:professional_id/days
/medical_centers/:medical_center_id/professionals/:professional_id/days/:day/times

/medical_shifts[/:medical_shift_id]
/medical_shifts/professional_dni/:professional_dni
/medical_shifts/dni/:dni
*/

namespace TurnosAPI;

return [
	'router' => [
	    'routes' => [
	        'turnos-api' => [
	            'type' => 'Literal',
	            'options' => [
	                'route' => '/shifts/v1',
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
		                    'route' => '/medical_centers[/:medical_center_id]',
		                    'constraints' => [
		                        'medical_center_id' => '[0-9]+',
		                    ],
		                    'defaults' => [
		                        'controller' => Controller\MedicalCentersController::class,
		                        'action' => 'index',
		                    ],
		                ],
		            ],
		            'medical_centers_specialties' => [
		                'type' => 'Segment',
		                'options' => [
		                    'route' => '/medical_centers/:medical_center_id/specialties[/:specialty_id]',
		                    'constraints' => [
		                        'medical_center_id' => '[0-9]+',
		                        'specialty_id' => '[0-9]+',
		                    ],
		                    'defaults' => [
		                        'controller' => Controller\SpecialtiesController::class,
		                        'action' => 'index',
		                    ],
		                ],
		            ],
		            'medical_centers_professionals_by_specialty' => [
		                'type' => 'Segment',
		                'options' => [
		                    'route' => '/medical_centers/:medical_center_id/specialties/:specialty_id/professionals',
		                    'constraints' => [
		                        'medical_center_id' => '[0-9]+',
		                        'specialty_id' => '[0-9]+',
		                    ],
		                    'defaults' => [
		                        'controller' => Controller\MedicalCentersController::class,
		                        'action' => 'getProfessionalsBySpecialty',
		                    ],
		                ],
		            ],
		            'medical_centers_professional_days' => [
		                'type' => 'Segment',
		                'options' => [
		                    'route' => '/medical_centers/:medical_center_id/professionals/:professional_id/days',
		                    'constraints' => [
		                        'medical_center_id' => '[0-9]+',
		                        'professional_id' => '[0-9]+',
		                    ],
		                    'defaults' => [
		                        'controller' => Controller\MedicalCentersController::class,
		                        'action' => 'getDaysByProfessional',
		                    ],
		                ],
		            ],
		            'medical_centers_professional_times' => [
		                'type' => 'Segment',
		                'options' => [
		                    'route' => '/medical_centers/:medical_center_id/professionals/:professional_id/days/:day/times',
		                    'constraints' => [
		                        'medical_center_id' => '[0-9]+',
		                        'professional_id' => '[0-9]+',
		                    ],
		                    'defaults' => [
		                        'controller' => Controller\MedicalCentersController::class,
		                        'action' => 'getTimesByProfessional',
		                    ],
		                ],
		            ],
		            'professionals' => [
		                'type' => 'Segment',
		                'options' => [
		                    'route' => '/professionals[/:professional_id]',
		                    'constraints' => [
		                        'professional_id' => '[0-9]+',
		                    ],
		                    'defaults' => [
		                        'controller' => Controller\ProfessionalsController::class,
		                        'action' => 'index',
		                    ],
		                ],
		            ],
		            'medical_shifts' => [
                		'type' => 'Segment',
                		'options' => [
                    		'route' => '/medical_shifts[/:medical_shift_id]',
                    		'constraints' => [
                        		'medical_shift_id' => '[0-9]+',
                    		],
		                    'defaults' => [
		                        'controller' => Controller\MedicalShiftsController::class,
		                        'action' => 'index',
		                    ],
                		],
            		],
		            'medical_shifts_professional_dni' => [
                		'type' => 'Segment',
                		'options' => [
                    		'route' => '/medical_shifts/professional_dni[/:professional_dni]',
                    		'constraints' => [
                        		'professional_dni' => '[0-9]+',
                    		],
		                    'defaults' => [
		                        'controller' => Controller\MedicalShiftsController::class,
		                        'action' => 'getByProfessionalDni',
		                    ],
                		],
            		],
		            'medical_shifts_dni' => [
                		'type' => 'Segment',
                		'options' => [
                    		'route' => '/medical_shifts/dni[/:dni]',
                    		'constraints' => [
                        		'dni' => '[0-9]+',
                    		],
		                    'defaults' => [
		                        'controller' => Controller\MedicalShiftsController::class,
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
			Controller\IndexController::class => Controller\Factory\ControllerFactory::class,
            Controller\MedicalCentersController::class => Controller\Factory\ControllerFactory::class,
            Controller\ProfessionalsController::class => Controller\Factory\ControllerFactory::class,
            Controller\SpecialtiesController::class => Controller\Factory\ControllerFactory::class,
            Controller\MedicalShiftsController::class => Controller\Factory\ControllerFactory::class,
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