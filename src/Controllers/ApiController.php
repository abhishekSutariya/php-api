<?php

namespace App\Controllers;

use App\Services\ResponseService;

class ApiController
{
    private ResponseService $response;

    public function __construct()
    {
        $this->response = new ResponseService();
    }

    public function health(array $params = []): void
    {
        $this->response->success([
            'status' => 'ok',
            'message' => 'API is running',
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    }
}

