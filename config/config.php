<?php

return [
    'app' => [
        'name' => 'PHP API',
        'version' => '1.0.0',
        'environment' => $_ENV['APP_ENV'] ?? 'development',
        'debug' => ($_ENV['APP_DEBUG'] ?? 'true') === 'true',
    ],
    'database' => [
        'host' => $_ENV['DB_HOST'] ?? 'localhost',
        'port' => $_ENV['DB_PORT'] ?? 3306,
        'name' => $_ENV['DB_NAME'] ?? 'php_api',
        'user' => $_ENV['DB_USER'] ?? 'root',
        'password' => $_ENV['DB_PASSWORD'] ?? '',
    ],
    'api' => [
        'base_url' => $_ENV['API_BASE_URL'] ?? 'http://localhost',
        'rate_limit' => $_ENV['API_RATE_LIMIT'] ?? 100,
    ],
];

