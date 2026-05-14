<?php

declare(strict_types=1);

namespace Core\Database;

use PDO;

/**
 * LSP: Fully substitutable for any DatabaseDriver expectation.
 * OCP: Adding this driver required zero changes to Connection.php.
 */
class SQLiteDriver implements DatabaseDriver
{
    public function connect(array $config): PDO
    {
        $path = $config['database'] ?? ':memory:';
        $pdo  = new PDO("sqlite:{$path}");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $pdo;
    }
}
