<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Shiprocket Credentilas
    |--------------------------------------------------------------------------
    |
    | Here you can set the default shiprocket credentilas. However, you can pass the credentials while connecting to shiprocket client
    |
    */

    'credentials' => [
        'email' => env('CARRIER_SHIPROCKET_EMAIL', 'youemail@email.com'),
        'password' => env('CARRIER_SHIPROCKET_PASSWORD', 'secret'),
    ],


    /*
    |--------------------------------------------------------------------------
    | Default output response type
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the output response you need.
    |
    | Supported: "collection" , "object", "array"
    |
    */

    'responseType' => 'collection',
    'channel_id' => env('CARRIER_SHIPROCKET_CHENNEL_ID'),
];
