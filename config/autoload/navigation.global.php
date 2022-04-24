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
            ],
            [
                'label'      => 'Noticias',
                'route'      => 'admin/news',
                'action'     => 'index',
                'icon'       => 'file-alt',

                'resource'   => 'Admin\Controller\NewsController',
            ],
            [
                'label'      => 'Noticias Sindicales',
                'route'      => 'admin/union_news',
                'action'     => 'index',
                'icon'       => 'file-alt',

                'resource'   => 'Admin\Controller\UnionNewsController',
            ],
            [
                'label'      => 'Afiliados',
                'route'      => 'admin/affiliates',
                'action'     => 'index',
                'icon'       => 'users-cog',

                'resource'   => 'Admin\Controller\AffiliatesController',
            ],
            [
                'label'      => 'Familiares',
                'route'      => 'admin/affiliates_family',
                'action'     => 'index',
                'icon'       => 'users',

                'resource'   => 'Admin\Controller\AffiliatesFamilyController',
            ],
            [
                'label'      => 'Prescripciones',
                'route'      => 'admin/affiliates_prescriptions',
                'action'     => 'index',
                'icon'       => 'syringe',

                'resource'   => 'Admin\Controller\AffiliatesPrescriptionsController',
            ],
            [
                'label'      => 'Autorizaciones',
                'route'      => 'admin/affiliates_authorizations',
                'action'     => 'index',
                'icon'       => 'file',

                'resource'   => 'Admin\Controller\AffiliatesAuthorizationsController',
            ],
            [
                'label'      => 'Profesionales',
                'route'      => 'admin/professionals',
                'action'     => 'index',
                'icon'       => 'user-tie',

                'resource'   => 'Admin\Controller\ProfessionalsController',
            ],
            [
                'label'      => 'Historias Clínicas',
                'route'      => 'admin/clinical_histories',
                'action'     => 'index',
                'icon'       => 'pen',

                'resource'   => 'Admin\Controller\ClinicalHistoriesController',
            ],
            [
                'label'      => 'Vademecum',
                'route'      => 'admin/vademecum',
                'action'     => 'index',
                'icon'       => 'capsules',

                'resource'   => 'Admin\Controller\VademecumController',
            ],
            [
                'label'      => 'Usuarios',
                'route'      => 'admin/users',
                'action'     => 'index',
                'icon'       => 'users',

                'resource'   => 'Admin\Controller\UsersController',
            ],
        ],
    ],
];