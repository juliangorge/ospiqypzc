<?php

return [
    'laminas-cli' => [
        'commands' => [
            'admin:affiliates_sync' => Admin\Scripts\AffiliatesSync::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            Admin\Scripts\AffiliatesSync::class => Admin\Scripts\Factory\CronScriptsFactory::class
        ],
    ],
];