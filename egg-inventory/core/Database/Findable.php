<?php

declare(strict_types=1);

namespace Core\Database;

/**
 * ISP: Read-only repositories implement only this interface.
 * They are never forced to stub save() or delete().
 */
interface Findable
{
    public function find(int|string $id): ?array;
    public function all(): array;
}
