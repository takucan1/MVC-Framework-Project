<?php

declare(strict_types=1);

namespace Core\Database;

use PDO;

/**
 * OCP: New drivers (MySQL, SQLite) implement this interface.
 *      Connection.php never changes when a new driver is added.
 * LSP: All drivers are fully substitutable — same contract.
 */
interface DatabaseDriver
{
    public function connect(array $config): PDO;
}
