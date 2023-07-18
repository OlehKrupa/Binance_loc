<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Cryptocurrencies
    |--------------------------------------------------------------------------
    |
    | List of available cryptocurrencies
    |
    */

    'cryptocurrencies' => [
        'AAVE', 'ADA', 'ALGO', 'ATOM', 'AVAX',
        'BCH', 'BSV', 'BTC', 'COMP', 'CRO',
        'DAI', 'DOGE', 'DOT', 'EGLD', 'EOS',
        'ETC', 'ETH', 'FIL', 'LINK', 'LTC',
        'MATIC', 'MKR', 'WBTC',
        'XLM',
    ],

    /*
    |--------------------------------------------------------------------------
    | Maximum Cryptocurrencies
    |--------------------------------------------------------------------------
    |
    | Maximum number of cryptocurrencies that a user can select
    |
    */
   
    'max_cryptocurrencies' => 5,

];
