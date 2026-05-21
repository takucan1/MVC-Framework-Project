<?php
namespace Core\Database;

use PDO;

class QueryBuilder {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Connection::getInstance();
    }

    public function all(string $table): array {
        $stmt = $this->pdo->query("SELECT * FROM {$table}");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(string $table, int $id): ?array {
        $stmt = $this->pdo->prepare("SELECT * FROM {$table} WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function insert(string $table, array $data): int {
        $columns = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));
        $stmt = $this->pdo->prepare("INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})");
        $stmt->execute($data);
        return (int)$this->pdo->lastInsertId();
    }

    public function update(string $table, int $id, array $data): bool {
    $set = implode(", ", array_map(fn($col) => "{$col} = :{$col}", array_keys($data)));
    $data['id'] = $id;
    $stmt = $this->pdo->prepare("UPDATE {$table} SET {$set} WHERE id = :id");
    return $stmt->execute($data);
}

public function delete(string $table, int $id): bool {
    $stmt = $this->pdo->prepare("DELETE FROM {$table} WHERE id = :id");
    return $stmt->execute(['id' => $id]);
}
}
