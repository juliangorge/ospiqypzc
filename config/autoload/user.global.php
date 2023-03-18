<?php

/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

declare(strict_types=1);

return [
    'name' => 'OSPIQyPZC',
    'analytics' => '',
    'version' => '3.2.1',
    'facebookAppId' => '',
    'authModule' => [
        'userEntity' => 'Admin\Entity\User',
        'allowRegister' => false,
        'logoWidth' => 'calc(100%/2)',
        'showFooter' => true,
        'showTitle' => false,
        'favicon' => 'favicon.svg'
    ],
    'reCAPTCHA_public_key' => '6Le6z5EiAAAAAOEa4ecYbeB54JhZTUt37XD3czHN',
];
