<?php 
   
return [
	'navigation' => [
        'admin' => [
            [
                'label'      => 'Tablero',
                'route'      => 'admin/dashboard',
                'action'     => 'index',
                'icon'       => 'tachometer-alt',

                'resource'   => 'Admin\Controller\IndexController',
                'privilege'  => 'index',
            ],
            [
                'label'      => 'Noticias',
                'route'      => 'admin/news',
                'action'     => 'index',
                'icon'       => 'file-alt',

                'resource'   => 'Admin\Controller\NewsController',
                'privilege'  => 'index',
            ],
            [
                'label'      => 'Noticias sindicales',
                'route'      => 'admin/union-news',
                'action'     => 'index',
                'icon'       => 'file-alt',

                'resource'   => 'Admin\Controller\UnionNewsController',
                'privilege'  => 'index',
            ],
            [
                'label'      => 'Afiliados',
                'route'      => 'admin/affiliates',
                'action'     => 'index',
                'icon'       => 'users-cog',

                'resource'   => 'Admin\Controller\AffiliatesController',
                'privilege'  => 'index',
            ],
            [
                'label'      => 'Familiares',
                'route'      => 'admin/affiliates_family',
                'action'     => 'index',
                'icon'       => 'users',

                'resource'   => 'Admin\Controller\AffiliatesFamilyController',
                'privilege'  => 'index',
            ],
            [
                'label'      => 'Prescripciones',
                'route'      => 'admin/affiliates_prescriptions',
                'action'     => 'index',
                'icon'       => 'syringe',

                'resource'   => 'Admin\Controller\AffiliatesPrescriptionsController',
                'privilege'  => 'index',
            ],
            [
                'label'      => 'Autorizaciones',
                'route'      => 'admin/affiliates_authorizations',
                'action'     => 'index',
                'icon'       => 'file',

                'resource'   => 'Admin\Controller\AffiliatesAuthorizationsController',
                'privilege'  => 'index',
            ],
            [
                'label'      => 'Profesionales',
                'route'      => 'admin/professionals',
                'action'     => 'index',
                'icon'       => 'user-tie',

                'resource'   => 'Admin\Controller\ProfessionalsController',
                'privilege'  => 'index',
            ],
            [
                'label'      => 'Historias Clínicas',
                'route'      => 'admin/clinical_histories',
                'action'     => 'index',
                'icon'       => 'pen',

                'resource'   => 'Admin\Controller\ClinicalHistoriesController',
                'privilege'  => 'index',
            ],
            [
                'label'      => 'Vademecum',
                'route'      => 'admin/vademecum',
                'action'     => 'index',
                'icon'       => 'capsules',

                'resource'   => 'Admin\Controller\VademecumController',
                'privilege'  => 'index',
            ],
            [
                'label'      => 'Usuarios',
                'route'      => 'admin/users',
                'action'     => 'index',
                'icon'       => 'users',

                'resource'   => 'Admin\Controller\UsersController',
                'privilege'  => 'index',
            ],
        ],
    ],
];