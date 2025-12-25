<?php

namespace App\Services;

class ResponseService
{
    public function success(mixed $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        echo json_encode([
            'success' => true,
            'data' => $data
        ], JSON_PRETTY_PRINT);
        exit;
    }

    public function error(string $message, int $statusCode = 400, mixed $errors = null): void
    {
        http_response_code($statusCode);
        $response = [
            'success' => false,
            'message' => $message
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        echo json_encode($response, JSON_PRETTY_PRINT);
        exit;
    }
}

