<?php

return [
    /*
     |--------------------------------------------------------------------------
     | Laravel CORS
     |--------------------------------------------------------------------------
     |

     | allowedOrigins, allowedHeaders and allowedMethods can be set to array('*')
     | to accept any value.
     |
     */
    'supportsCredentials' => false,
//    'allowedOrigins' => ['*'],
    'allowedOrigins' => explode(',', env('CORS_ALLOWED_ORIGINS', '')),
    'allowedHeaders' => ['Accept', 'Content-Type', 'Origin'],
    'allowedMethods' => ['*'],
    'exposedHeaders' => [],
    'maxAge' => 0,
    'hosts' => [],
];
