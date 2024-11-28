<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie', 'auth/*','http://localhost:5173/'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['http://localhost:5173'], 
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,
];