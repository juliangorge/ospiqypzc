<?php 
   
return [
	'navigation' => [
        'admin' => [
            [
                'label'      => 'Tablero',
                'route'      => 'admin',
                'icon'       => 'mdi mdi-home',
                'resource'   => 'Admin\Controller\IndexController',
            ],
            [
                'label'      => 'Noticias',
                'route'      => 'admin/news',
                'action'     => 'index',
                'icon'       => 'mdi mdi-newspaper-variant-multiple',
                'resource'   => 'Admin\Controller\NewsController',
            ],
            [
                'label'      => 'Noticias Sindicales',
                'route'      => 'admin/union_news',
                'action'     => 'index',
                'icon'       => 'mdi mdi-newspaper-variant-multiple-outline',
                'resource'   => 'Admin\Controller\NewsController',
            ],
            [
                'label'      => 'Afiliados',
                'route'      => 'admin/affiliates',
                'action'     => 'index',
                'icon'       => 'mdi mdi-account-hard-hat',
                'resource'   => 'Admin\Controller\AffiliatesController',
            ],
            [
                'label'      => 'Familiares',
                'route'      => 'admin/affiliates_family',
                'action'     => 'index',
                'icon'       => 'mdi mdi-human-male-female-child',
                'resource'   => 'Admin\Controller\AffiliatesFamilyController',
            ],
            [
                'label'      => 'Reclamos',
                'route'      => 'admin/affiliates_claims',
                'action'     => 'index',
                'icon'       => 'mdi mdi-book-cancel',
                'resource'   => 'Admin\Controller\AffiliatesClaimsController',
            ],
            [
                'label'      => 'Autorizaciones',
                'route'      => 'admin/affiliates_authorizations',
                'action'     => 'index',
                'icon'       => 'mdi mdi-book-check',
                'resource'   => 'Admin\Controller\AffiliatesAuthorizationsController',
            ],
            [
                'label'      => 'Usuarios',
                'route'      => 'admin/users',
                'action'     => 'index',
                'icon'       => 'mdi mdi-account-multiple',
                'resource'   => 'Admin\Controller\UsersController',
            ],
        ],
    ],
];