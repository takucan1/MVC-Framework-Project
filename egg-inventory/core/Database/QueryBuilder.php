<?php
namespace Core\Database;

class QueryBuilder {
    private Connection $connection;

    public function __construct(Connection $connection) {
        $this->connection = $connection;
    }

    public function all(): array {
        return $this->connection->read();
    }

    public function find(int $id): ?array {
        $data = $this->connection->read();
        return $data[$id] ?? null;
    }

    public function insert(array $record): void {
        $data = $this->connection->read();
        $data[] = $record;
        $this->connection->write($data);
    }

    public function update(int $id, array $record): void {
        $data = $this->connection->read();
        $data[$id] = $record;
        $this->connection->write($data);
    }

    public function delete(int $id): void {
        $data = $this->connection->read();
        unset($data[$id]);
        $this->connection->write($data);
    }
}
