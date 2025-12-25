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

    public function getUsers(array $params = []): void
    {
        // Example data - replace with database query
        $users = [
            ['id' => 1, 'name' => 'John Doe', 'email' => 'john@example.com'],
            ['id' => 2, 'name' => 'Jane Smith', 'email' => 'jane@example.com'],
        ];

        $this->response->success($users);
    }

    public function getUser(array $params): void
    {
        $id = $params['id'] ?? null;

        if (!$id) {
            $this->response->error('User ID is required', 400);
            return;
        }

        // Example data - replace with database query
        $user = [
            'id' => (int)$id,
            'name' => 'John Doe',
            'email' => 'john@example.com'
        ];

        $this->response->success($user);
    }

    public function createUser(array $params = []): void
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data || !isset($data['name']) || !isset($data['email'])) {
            $this->response->error('Name and email are required', 400);
            return;
        }

        // Example - replace with database insert
        $user = [
            'id' => rand(100, 999),
            'name' => $data['name'],
            'email' => $data['email']
        ];

        $this->response->success($user, 201);
    }

    public function updateUser(array $params): void
    {
        $id = $params['id'] ?? null;

        if (!$id) {
            $this->response->error('User ID is required', 400);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        // Example - replace with database update
        $user = [
            'id' => (int)$id,
            'name' => $data['name'] ?? 'Updated Name',
            'email' => $data['email'] ?? 'updated@example.com'
        ];

        $this->response->success($user);
    }

    public function deleteUser(array $params): void
    {
        $id = $params['id'] ?? null;

        if (!$id) {
            $this->response->error('User ID is required', 400);
            return;
        }

        // Example - replace with database delete
        $this->response->success([
            'message' => 'User deleted successfully',
            'id' => (int)$id
        ]);
    }
}

