<?php 

$menu_default = [
    [
        'label'      => 'Tablero',
        'route'      => 'admin',
        'icon'       => 'mdi mdi-home',
        'resource'   => 'Admin\Controller\IndexController',
    ]
];

$menu_noticias = [
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
];

$menu_afiliados = [
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
];

$menu_app = [
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
        'label'      => 'Turnos Médicos',
        'route'      => 'admin/medical_shifts',
        'action'     => 'index',
        'icon'       => 'mdi mdi-heart-plus-outline',
        'resource'   => 'Admin\Controller\MedicalShiftsController',
    ],
    [
        'label'      => 'Agenda Profesional',
        'route'      => 'admin/professional_calendar',
        'action'     => 'index',
        'icon'       => 'mdi mdi-calendar',
        'resource'   => 'Admin\Controller\ProfessionalCalendarController',
    ],
    [
        'label'      => 'Profesionales',
        'route'      => 'admin/professionals',
        'action'     => 'index',
        'icon'       => 'mdi mdi-doctor',
        'resource'   => 'Admin\Controller\ProfessionalsController',
    ],
    [
        'label'      => 'Especialidades',
        'route'      => 'admin/specialties',
        'action'     => 'index',
        'icon'       => 'mdi mdi-format-list-bulleted-square',
        'resource'   => 'Admin\Controller\SpecialitiesController',
    ],
];

$medico_auditor = array_merge($menu_default, $menu_afiliados, $menu_app);

$administrativo = array_merge($menu_default, $menu_afiliados, $menu_app);

$administrador = array_merge($menu_default, $menu_noticias, $menu_afiliados, $menu_app, [
    [
        'label'      => 'Usuarios',
        'route'      => 'admin/users',
        'action'     => 'index',
        'icon'       => 'mdi mdi-account-multiple',
        'resource'   => 'Admin\Controller\UsersController',
    ]
]);
   
return [
	'navigation' => [
        'guest' => $menu_default,
        'medico_auditor' => $medico_auditor,
        'administrativo' => $administrativo,
        'gerencia' => $administrador,
        'administrador' => $administrador,
    ],
];