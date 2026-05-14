<?php

declare(strict_types=1);

namespace App\Models;

use Core\Database\Connection;
use Core\Database\QueryBuilder;
use PDO;

/**
 * Full egg repository with aggregate queries.
 * DIP: Bound to EggRepositoryInterface in bootstrap — controller never knows this class.
 */
class EggRepository implements EggRepositoryInterface
{
    private readonly QueryBuilder $qb;
    private readonly PDO          $pdo;
    private string                $table = 'eggs';

    public function __construct(Connection $connection)
    {
        $this->pdo = $connection->pdo();
        $this->qb  = new QueryBuilder($this->pdo);
    }

    public function all(): array
    {
        return $this->qb->table($this->table)->orderBy('created_at', 'DESC')->get();
    }

    public function find(int|string $id): ?array
    {
        return $this->qb->table($this->table)->where('id', $id)->first();
    }

    public function findByType(string $type): array
    {
        return $this->qb->table($this->table)->where('egg_type', $type)->orderBy('created_at', 'DESC')->get();
    }

    public function save(array $data): int|string
    {
        return $this->qb->table($this->table)->insert($data);
    }

    public function update(int|string $id, array $data): bool
    {
        $affected = $this->qb->table($this->table)->where('id', $id)->update($data);
        return $affected > 0;
    }

    public function delete(int|string $id): bool
    {
        $affected = $this->qb->table($this->table)->where('id', $id)->delete();
        return $affected > 0;
    }

    public function stockSummary(): array
    {
        $stmt = $this->pdo->query(
            "SELECT egg_type, SUM(quantity) as total_quantity, COUNT(*) as batch_count
             FROM {$this->table}
             GROUP BY egg_type
             ORDER BY egg_type"
        );
        return $stmt->fetchAll();
    }
}
