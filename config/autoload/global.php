<?php
return [
    'session_config' => [
        'cookie_lifetime' => 21600,
        'gc_maxlifetime' => 5184000,
    ],
    'session_manager' => [
        'validators' => [],
    ],
    'session_storage' => [
        'type' => \Laminas\Session\Storage\SessionArrayStorage::class,
    ],
    'api-tools-content-negotiation' => [
        'selectors' => [],
    ],
    'db' => [
        'adapters' => [
            'dbadapter' => [],
        ],
    ],
    'api-tools-oauth2' => [
        'access_lifetime' => 21600,
        'options' => [
            'refresh_token_lifetime' => 21600,
            'always_issue_new_refresh_token' => true,
        ],
    ],
    'router' => [
        'routes' => [
            'oauth' => [
                'options' => [
                    'spec' => '%oauth%',
                    'regex' => '(?P<oauth>(oauth))',
                ],
                'type' => 'regex',
            ],
        ],
    ],
    'api-tools-mvc-auth' => [
        'authentication' => [
            'map' => [
                'Api\\V1' => 'oauth2',
            ],
        ],
    ],
    'service_manager' => [
        'factories' => [
            \Laminas\Db\Adapter\Adapter::class => \Laminas\Db\Adapter\AdapterServiceFactory::class,
        ],
    ],
];
