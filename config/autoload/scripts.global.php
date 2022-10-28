<?php

return [
    'service_manager' => [
        'factories' => [
            Admin\Scripts\AffiliatesSync::class => Admin\Scripts\Factory\CronScriptsFactory::class
        ],
    ],
];