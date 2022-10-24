<?php 

return [
    'acl' => [
        'roles' => [
            'medico_auditor' => NULL,
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
                    'all' => ['gerencia', 'administrador']
                ],
                'Admin\Controller\UnionNewsController' => [
                    'all' => ['gerencia', 'administrador']
                ],
                'Admin\Controller\UsersController' => [
                    'all' => ['gerencia', 'administrador']
                ],
                'Admin\Controller\AffiliatesController' => [
                    'all' => ['medico_auditor', 'gerencia', 'administrador']
                ],
                'Admin\Controller\AffiliatesFamilyController' => [
                    'all' => ['medico_auditor', 'gerencia', 'administrador']
                ],
                'Admin\Controller\AffiliatesAuthorizationsController' => [
                    'all' => ['medico_auditor', 'gerencia', 'administrador']
                ],
                'Admin\Controller\AffiliatesClaimsController' => [
                    'all' => ['gerencia', 'administrador']
                ],
            ],
        ],
    ],
];