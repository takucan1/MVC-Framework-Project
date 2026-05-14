<?php

declare(strict_types=1);

namespace Core\Database;

/**
 * Base model that bridges QueryBuilder with the Findable + Persistable interfaces.
 * SRP: Provides data-access behaviour. Never renders HTML.
 */
abstract class Model implements Findable, Persistable
{
    protected string $table       = '';
    protected string $primaryKey  = 'id';

    public function __construct(protected readonly QueryBuilder $query) {}

    public function all(): array
    {
        return $this->query->table($this->table)->get();
    }

    public function find(int|string $id): ?array
    {
        return $this->query
            ->table($this->table)
            ->where($this->primaryKey, $id)
            ->first();
    }

    public function save(array $data): int|string
    {
        return $this->query->table($this->table)->insert($data);
    }

    public function update(int|string $id, array $data): bool
    {
        $affected = $this->query
            ->table($this->table)
            ->where($this->primaryKey, $id)
            ->update($data);
        return $affected > 0;
    }

    public function delete(int|string $id): bool
    {
        $affected = $this->query
            ->table($this->table)
            ->where($this->primaryKey, $id)
            ->delete();
        return $affected > 0;
    }
}
