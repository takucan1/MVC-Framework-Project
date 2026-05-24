<?php
namespace Core\Database;

use PDO;
use PDOException;

class Connection {
    private static ?PDO $pdo = null;

    public static function getInstance(): PDO {
        if (self::$pdo === null) {
            $config = require __DIR__ . '/../../config/database.php';
            try {
                self::$pdo = new PDO(
                    "mysql:host={$config['host']};dbname={$config['dbname']}",
                    $config['username'],
                    $config['password']
                );
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Database connection failed: " . $e->getMessage());
            }
        }
        return self::$pdo;
    }
}
