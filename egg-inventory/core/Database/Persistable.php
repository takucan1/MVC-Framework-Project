<?php

declare(strict_types=1);

namespace Core\Database;

/**
 * ISP: Write-capable repositories implement this.
 * Read-only repositories never implement this interface.
 */
interface Persistable
{
    public function save(array $data): int|string;
    public function update(int|string $id, array $data): bool;
    public function delete(int|string $id): bool;
}
