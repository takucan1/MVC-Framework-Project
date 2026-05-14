<?php

declare(strict_types=1);

namespace Core\Database;

use PDO;
use PDOStatement;

/**
 * Fluent query builder.
 * SRP: Builds and executes SQL. Does not model domain objects.
 */
class QueryBuilder
{
    private string $table     = '';
    private array  $wheres    = [];
    private array  $bindings  = [];
    private ?int   $limitVal  = null;
    private string $orderCol  = '';
    private string $orderDir  = 'ASC';

    public function __construct(private readonly PDO $pdo) {}

    public function table(string $table): static
    {
        $clone        = clone $this;
        $clone->table = $table;
        return $clone;
    }

    public function where(string $column, mixed $value): static
    {
        $clone             = clone $this;
        $placeholder       = ':w_' . count($clone->wheres);
        $clone->wheres[]   = "{$column} = {$placeholder}";
        $clone->bindings[$placeholder] = $value;
        return $clone;
    }

    public function orderBy(string $column, string $direction = 'ASC'): static
    {
        $clone           = clone $this;
        $clone->orderCol = $column;
        $clone->orderDir = strtoupper($direction);
        return $clone;
    }

    public function limit(int $count): static
    {
        $clone           = clone $this;
        $clone->limitVal = $count;
        return $clone;
    }

    public function get(): array
    {
        $sql  = "SELECT * FROM {$this->table}";
        $sql .= $this->buildWhere();
        if ($this->orderCol) {
            $sql .= " ORDER BY {$this->orderCol} {$this->orderDir}";
        }
        if ($this->limitVal !== null) {
            $sql .= " LIMIT {$this->limitVal}";
        }
        $stmt = $this->execute($sql, $this->bindings);
        return $stmt->fetchAll();
    }

    public function first(): ?array
    {
        $rows = $this->limit(1)->get();
        return $rows[0] ?? null;
    }

    public function insert(array $data): int|string
    {
        $cols    = implode(', ', array_keys($data));
        $places  = implode(', ', array_map(fn($k) => ":{$k}", array_keys($data)));
        $sql     = "INSERT INTO {$this->table} ({$cols}) VALUES ({$places})";
        $this->execute($sql, $data);
        return $this->pdo->lastInsertId();
    }

    public function update(array $data): int
    {
        $sets = implode(', ', array_map(fn($k) => "{$k} = :set_{$k}", array_keys($data)));
        $sql  = "UPDATE {$this->table} SET {$sets}" . $this->buildWhere();

        $bindings = [];
        foreach ($data as $k => $v) {
            $bindings["set_{$k}"] = $v;
        }
        $bindings = array_merge($bindings, $this->stripColons($this->bindings));

        $stmt = $this->execute($sql, $bindings);
        return $stmt->rowCount();
    }

    public function delete(): int
    {
        $sql  = "DELETE FROM {$this->table}" . $this->buildWhere();
        $stmt = $this->execute($sql, $this->stripColons($this->bindings));
        return $stmt->rowCount();
    }

    private function buildWhere(): string
    {
        if (empty($this->wheres)) {
            return '';
        }
        return ' WHERE ' . implode(' AND ', $this->wheres);
    }

    private function execute(string $sql, array $bindings): PDOStatement
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($bindings);
        return $stmt;
    }

    /** Remove leading colon from binding keys for named params. */
    private function stripColons(array $bindings): array
    {
        $clean = [];
        foreach ($bindings as $k => $v) {
            $clean[ltrim($k, ':')] = $v;
        }
        return $clean;
    }
}
