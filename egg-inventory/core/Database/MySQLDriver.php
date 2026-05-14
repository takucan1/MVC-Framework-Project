<?php

declare(strict_types=1);

namespace Core\Database;

use PDO;

/**
 * LSP: Fully substitutable wherever DatabaseDriver is expected.
 * OCP: Added without touching Connection.php.
 */
class MySQLDriver implements DatabaseDriver
{
    public function connect(array $config): PDO
    {
        $host    = $config['host']     ?? '127.0.0.1';
        $port    = $config['port']     ?? 3306;
        $dbname  = $config['database'] ?? '';
        $charset = $config['charset']  ?? 'utf8mb4';

        $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset={$charset}";
        $pdo = new PDO($dsn, $config['username'] ?? '', $config['password'] ?? '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $pdo;
    }
}
