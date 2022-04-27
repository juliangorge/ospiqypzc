<?php

return [
	'acl' => [
		'roles' => [
			/*
			Herencia:
				Guest hereda de NULL
				Administración hereda de Guest
				Gerencia hereda de Administración
				Todo lo que Administración haga, lo puede hacer Gerencia
				* Atención: Administración no es lo mismo que "Admin"
			*/
			'guest' => NULL,
			'sindicato' => NULL,
			'medico' => NULL,
			'administracion' => 'guest',
			'gerencia' => 'administracion',
			'admin' => 'gerencia',
		],
		'resources' => [
			'allow' => [
				'Laminas\ApiTools\Admin\Controller\App' => [
					'all' => ['guest']
				],
				'Admin\Controller\AffiliatesController' => [
					'all' => ['administracion']
				],
				'Admin\Controller\AffiliatesFamilyController' => [
					'all' => ['administracion', 'gerencia']
				],
				'Admin\Controller\AffiliatesPrescriptionsController' => [
					'all' => ['gerencia', 'medico']
				],
				'Admin\Controller\AffiliatesClaimsController' => [
					'all' => ['administracion', 'gerencia']
				],
				'Admin\Controller\IndexController' => [
					'all' => ['administracion']
				],
				'Admin\Controller\NewsController' => [
					'all' => ['gerencia']
				],
				'Admin\Controller\UnionNewsController' => [
					'all' => ['gerencia', 'sindicato']
				],
				'Admin\Controller\ProfessionalsController' => [
					'all' => ['administracion']
				],
				'Admin\Controller\VademecumController' => [
					'all' => ['gerencia', 'medico']
				],
				'Admin\Controller\AffiliatesAuthorizationsController' => [
					'all' => ['gerencia', 'medico']
				],
				'Admin\Controller\UsersController' => [
					'all' => ['gerencia']
				],
			],
		]
	]
];