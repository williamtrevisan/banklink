<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Default Bank Driver
    |--------------------------------------------------------------------------
    |
    | This option controls the default bank "driver" that will be used when
    | using the Banklink service. You may set this to any of the drivers
    | defined in the "banks" array below.
    |
    */

    'default' => env('BANK_DRIVER', 'itau'),

    /*
    |--------------------------------------------------------------------------
    | Bank Drivers
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many bank drivers as you wish, and you
    | may even configure multiple drivers of the same bank. Defaults have
    | been setup for each driver as an example of the required options.
    |
    */

    'banks' => [
        'itau' => [
            'base_url' => env('BANK_BASE_URL', 'https://internetpf5.itau.com.br'),
            'agency' => env('BANK_AGENCY'),
            'account' => env('BANK_ACCOUNT'),
            'digit' => env('BANK_ACCOUNT_DIGIT'),
            'password' => env('BANK_PASSWORD'),
            'holder' => env('BANK_HOLDER'),
        ],
    ],
];
