<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Router\Router;
use App\Controllers\ApiController;
use App\Controllers\AuthController;

// Set headers for JSON API
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Initialize router
$router = new Router();

// Define routes
$router->get('/', function (): void {
    echo json_encode([
        'status' => 'API is running'
    ]);
});
$router->get('/api/health', [ApiController::class, 'health']);
$router->post('/api/login', [AuthController::class, 'login']);

// Dispatch the request
try {
    $router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => true,
        'message' => $e->getMessage()
    ]);
}

