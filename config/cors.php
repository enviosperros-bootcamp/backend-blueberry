<?php
// config/cors.php

return [
    'paths' => ['api/*'], // Sólo rutas API
    'allowed_methods' => ['*'], // GET, POST, PUT, DELETE, etc.
    'allowed_origins' => [
        'http://localhost:5174',
        'http://localhost:5173',     // Vue en desarrollo
        'https://tu-sitio.com' // Producción
    ],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false, // false para JWT (no cookies)
];