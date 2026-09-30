<?php

return [
    // Set NOCAPTCHA_ENABLED=false on servers that cannot reach www.google.com
    // (server-side verification would otherwise time out and crash the request).
    'enabled' => env('NOCAPTCHA_ENABLED', true),
    'secret' => env('NOCAPTCHA_SECRET'),
    'sitekey' => env('NOCAPTCHA_SITEKEY'),
    'options' => [
        'timeout' => 30,
    ],
];
