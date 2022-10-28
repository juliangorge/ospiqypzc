<?php
return [
    'api-tools-content-negotiation' => [
        'selectors' => [],
    ],
    'db' => [
        'adapters' => [],
    ],
    'api-tools-mvc-auth' => [
        'authentication' => [
            'map' => [
                'API\\V1' => 'basic',
            ],
        ],
    ],
    'laminas-cli' => [
        'commands' => [
            'admin:affiliates_sync' => Admin\Scripts\AffiliatesSync::class,
        ],
    ],
];
