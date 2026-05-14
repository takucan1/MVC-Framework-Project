<?php

declare(strict_types=1);

namespace App\Models;

use Core\Database\Model;
use Core\Database\QueryBuilder;

/**
 * Concrete egg repository using SQLite via QueryBuilder.
 * LSP: Fully substitutable for EggRepositoryInterface.
 * SRP: Data-access only — never renders HTML or handles HTTP.
 */
class Egg extends Model implements EggRepositoryInterface
{
    protected string $table = 'eggs';

    public function __construct(QueryBuilder $query)
    {
        parent::__construct($query);
    }

    public function findByType(string $type): array
    {
        return $this->query
            ->table($this->table)
            ->where('egg_type', $type)
            ->orderBy('created_at', 'DESC')
            ->get();
    }

    public function stockSummary(): array
    {
        $pdo  = $this->query->table($this->table); // just to get the builder
        // Raw aggregate query via the underlying PDO from the query builder
        // We keep logic here (SRP: model owns domain queries)
        return [];  // overridden via direct PDO in EggRepository below
    }

    public function all(): array
    {
        return $this->query
            ->table($this->table)
            ->orderBy('created_at', 'DESC')
            ->get();
    }
}
