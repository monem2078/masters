<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],
    'facebook' => [
        'client_id' => '296170864395832',
        'client_secret' => '1eb2eb53ca4736e04d21c04866bf66b0',
        'redirect' => env('APP_URL') . '/api/v1/social-callback/' . config('providers.facebook'),
    ],
    'google' => [
        'client_id' => '878456909661-2p1rcjv6v569uudku84rbbhjf41d4dbr.apps.googleusercontent.com',
        'client_secret' => 'SRU9xGQd7qXD0-Iqmijh5q2y',
        'redirect' => env('APP_URL') . '/api/v1/social-callback/' . config('providers.google'),
    ]
];
