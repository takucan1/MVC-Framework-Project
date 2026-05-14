<?php

declare(strict_types=1);

namespace Core\Database;

use PDO;

/**
 * Holds the active PDO connection.
 * OCP: Never modified when a new DatabaseDriver is introduced.
 * DIP: Depends on the DatabaseDriver abstraction, not a concrete driver.
 */
class Connection
{
    private readonly PDO $pdo;

    public function __construct(
        private readonly DatabaseDriver $driver,
        private readonly array          $config
    ) {
        $this->pdo = $this->driver->connect($this->config);
    }

    public function pdo(): PDO
    {
        return $this->pdo;
    }
}
