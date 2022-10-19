<?php 

return [
    'acl' => [
        'roles' => [
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
                    'all' => NULL
                ],
                'Admin\Controller\UnionNewsController' => [
                    'all' => NULL
                ],
                'Admin\Controller\UsersController' => [
                    'all' => NULL
                ],
                'Admin\Controller\AffiliatesController' => [
                    'all' => NULL
                ],
                'Admin\Controller\AffiliatesFamilyController' => [
                    'all' => NULL
                ],
                'Admin\Controller\AffiliatesAuthorizationsController' => [
                    'all' => NULL
                ],
                'Admin\Controller\AffiliatesClaimsController' => [
                    'all' => NULL
                ],
            ],
        ],
    ],
];