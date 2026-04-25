#!/bin/bash
set -u

echo "Container startup: checking database migrations..."

if [ -z "${DATABASE_URL:-}" ]; then
    echo "DATABASE_URL not set, skipping migrations"
else
    php -r '
    $databaseUrl = getenv("DATABASE_URL");
    $migrationsDir = "/var/www/html/database/migrations";

    if (!is_dir($migrationsDir)) {
        fwrite(STDOUT, "Migrations directory not found, skipping migrations\n");
        exit(0);
    }

    $files = glob($migrationsDir . "/*.sql");
    if (!$files) {
        fwrite(STDOUT, "No migration files found, skipping migrations\n");
        exit(0);
    }
    sort($files);

    $parts = parse_url($databaseUrl);
    if ($parts === false || !isset($parts["host"], $parts["path"], $parts["user"])) {
        fwrite(STDOUT, "Invalid DATABASE_URL format, skipping migrations\n");
        exit(0);
    }

    $host = $parts["host"];
    $port = $parts["port"] ?? 5432;
    $dbName = ltrim($parts["path"], "/");
    $user = $parts["user"];
    $pass = $parts["pass"] ?? "";

    try {
        $pdo = new PDO(
            "pgsql:host={$host};port={$port};dbname={$dbName}",
            $user,
            $pass,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    } catch (Throwable $e) {
        fwrite(STDOUT, "Database connection failed, skipping migrations: " . $e->getMessage() . "\n");
        exit(0);
    }

    foreach ($files as $file) {
        $filename = basename($file);
        fwrite(STDOUT, "Running migration: {$filename}\n");

        $sql = file_get_contents($file);
        if ($sql === false || trim($sql) === "") {
            fwrite(STDOUT, "Skipping empty migration: {$filename}\n");
            continue;
        }

        try {
            $pdo->exec($sql);
        } catch (Throwable $e) {
            fwrite(STDOUT, "Migration failed ({$filename}): " . $e->getMessage() . "\n");
        }
    }
    ';
fi

exec apache2-foreground
