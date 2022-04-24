<?php
use Laminas\Session\Storage\SessionArrayStorage;
use Laminas\Session\Validator\RemoteAddr;

#$host = (strpos($_SERVER['SERVER_NAME'], 'ar') !== false ? 'localhost' : 'localhost');
#$database = (strpos($_SERVER['SERVER_NAME'], 'ar') !== false ? 'dbz6bhgjy09xfd' : 'shiftdigital');

return [
    'session_config' => [
        'cookie_lifetime' => 2*60*60*3*1,
        'gc_maxlifetime'  => 2*60*60*24*30
    ],
    'session_manager' => [
        'validators' => [
            //RemoteAddr::class
        ]
    ],
    'session_storage' => [
        'type' => SessionArrayStorage::class
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
        'access_lifetime' => 21600, // 6 hours
        'options' => [
            'refresh_token_lifetime' => 21600, // 6 hours
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
            'Laminas\Db\Adapter\Adapter' => 'Laminas\Db\Adapter\AdapterServiceFactory'
        ],
    ],
];
