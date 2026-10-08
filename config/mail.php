<?php

return [

    'default' => env('MAIL_MAILER', 'smtp'),

    'mailers' => [

        'smtp' => [
            'transport' => 'smtp',
            'host' => saasEnv('MAIL_HOST', env('MAIL_HOST')),
            'port' => saasEnv('MAIL_PORT', env('MAIL_PORT', 587)),
            'encryption' => saasEnv('MAIL_ENCRYPTION', env('MAIL_ENCRYPTION', 'tls')),
            'username' => saasEnv('MAIL_USERNAME', env('MAIL_USERNAME')),
            'password' => saasEnv('MAIL_PASSWORD', env('MAIL_PASSWORD')),
            'timeout' => null,
            'local_domain' => env('MAIL_EHLO_DOMAIN'),
        ],

        'log' => [
            'transport' => 'log',
            'channel' => env('MAIL_LOG_CHANNEL'),
        ],

        'array' => [
            'transport' => 'array',
        ],
    ],

    'from' => [
        'address' => saasEnv('MAIL_FROM_ADDRESS', env('MAIL_FROM_ADDRESS')),
        'name' => saasEnv('MAIL_FROM_NAME', env('MAIL_FROM_NAME')),
    ],

    'markdown' => [
        'theme' => 'default',

        'paths' => [
            resource_path('views/vendor/mail'),
        ],
    ],

];
