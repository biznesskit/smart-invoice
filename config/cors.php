<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    */

    'paths' => ['*'],

    'allowed_methods' => ['*'],

    'allowed_origins' => ['*'],

    'allowed_origins_patterns' => [
        '*192.168.100.104*', // mushroom IP
        '*192.168.88.250*', //antony IP
        '*192.168.53.147*', //antony hotspot
        
        '*bizkitpos.com*',
        '*bizkit-web.vercel.app*',
        
    ],

    'allowed_headers' => ['tenant_id', '*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,
];
