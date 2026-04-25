<?php

namespace App\Database;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $connection = null;

    public static function getConnection(): PDO
    {
        if (self::$connection === null) {
            $config = require __DIR__ . '/../../config/config.php';
            $url = $config['database_url'] ?? getenv('DATABASE_URL') ?: '';

            try {
                if ($url === '') {
                    throw new \Exception('DATABASE_URL is not configured.');
                }

                $db = parse_url($url);
                if ($db === false || !isset($db['host'], $db['path'], $db['user'])) {
                    throw new \Exception('DATABASE_URL format is invalid.');
                }

                $port = $db['port'] ?? 5432;
                $password = $db['pass'] ?? '';
                $dsn = "pgsql:host={$db['host']};port={$port};dbname=" . ltrim($db['path'], '/');

                self::$connection = new PDO(
                    $dsn,
                    $db['user'],
                    $password,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false,
                    ]
                );
            } catch (PDOException $e) {
                throw new \Exception("Database connection failed: " . $e->getMessage());
            }
        }

        return self::$connection;
    }
}

