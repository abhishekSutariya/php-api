<?php

namespace App\Controllers;

use App\Database\Database;
use App\Services\ResponseService;
use App\Services\TokenService;
use PDO;

class AuthController
{
    private ResponseService $response;
    private PDO $db;

    public function __construct()
    {
        $this->response = new ResponseService();
        $this->db = Database::getConnection();
    }

    public function login(array $params = []): void
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data) {
            $this->response->error('Invalid request data', 400);
            return;
        }

        $username = $data['userName'] ?? $data['username'] ?? null;
        $password = $data['password'] ?? null;

        if (!$username || !$password) {
            $this->response->error('Username and password are required', 400);
            return;
        }

        // Validate credentials
        if ($username !== 'alex' || $password !== 'admin') {
            $this->response->error('Invalid username or password', 401);
            return;
        }

        // Generate auth token
        $token = TokenService::generateToken();
        $expiryTime = TokenService::getExpiryTime();

        // Store token in database
        try {
            $stmt = $this->db->prepare("
                INSERT INTO auth_tokens (username, token, expires_at, created_at) 
                VALUES (:username, :token, :expires_at, NOW())
                ON CONFLICT (username) 
                DO UPDATE SET token = :token, expires_at = :expires_at, created_at = NOW()
            ");

            $stmt->execute([
                ':username' => $username,
                ':token' => $token,
                ':expires_at' => $expiryTime
            ]);

            $this->response->success([
                'message' => 'Login successful',
                'token' => $token,
                'expires_at' => $expiryTime
            ], 200);

        } catch (\Exception $e) {
            $this->response->error('Failed to generate token: ' . $e->getMessage(), 500);
        }
    }
}

