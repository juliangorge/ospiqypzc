<?php 

return [
    'acl' => [
        'roles' => [
            'medico_auditor' => NULL,
            'administrativo' => NULL,
            'gerencia' => NULL,
            'administrador' => NULL
        ],
        'resources' => [
            'allow' => [
                'Application\Controller\IndexController' => [
                    'all' => NULL
                ],
                'Admin\Controller\IndexController' => [
                    'all' => NULL
                ],
                'Admin\Controller\NewsController' => [
                    'all' => ['administrativo', 'gerencia', 'administrador']
                ],
                'Admin\Controller\UnionNewsController' => [
                    'all' => ['administrativo', 'gerencia', 'administrador']
                ],
                'Admin\Controller\UsersController' => [
                    'all' => ['gerencia', 'administrador']
                ],
                'Admin\Controller\AffiliatesController' => [
                    'index' => ['administrativo'],
                    'get' => ['administrativo'],
                    'all' => ['medico_auditor', 'gerencia', 'administrador']
                ],
                'Admin\Controller\AffiliatesFamilyController' => [
                    'index' => ['administrativo'],
                    'get' => ['administrativo'],
                    'all' => ['medico_auditor', 'gerencia', 'administrador']
                ],
                'Admin\Controller\AffiliatesAuthorizationsController' => [
                    'index' => ['administrativo'],
                    'get' => ['administrativo'],
                    'view' => ['administrativo'],
                    'all' => ['medico_auditor', 'gerencia', 'administrador']
                ],
                'Admin\Controller\ProfessionalsController' => [
                    'index' => ['administrativo'],
                    'get' => ['administrativo'],
                    'view' => ['administrativo'],
                    'all' => ['gerencia', 'administrador']
                ],
                'Admin\Controller\SpecialtiesController' => [
                    'index' => ['administrativo'],
                    'get' => ['administrativo'],
                    'view' => ['administrativo'],
                    'all' => ['gerencia', 'administrador']
                ],
                'Admin\Controller\AffiliatesClaimsController' => [
                    'index' => ['administrativo'],
                    'get' => ['administrativo'],
                    'view' => ['administrativo'],
                    'all' => ['gerencia', 'administrador']
                ],
                'Admin\Controller\MedicalShiftsController' => [
                    'all' => ['administrativo', 'gerencia', 'administrador']
                ],
                'Admin\Controller\ProfessionalCalendarController' => [
                    'all' => ['administrativo', 'gerencia', 'administrador']
                ],
            ],
        ],
    ],
];