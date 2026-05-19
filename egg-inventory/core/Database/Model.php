<?php
namespace Core\Database;

abstract class Model {
    protected QueryBuilder $qb;

    public function __construct(QueryBuilder $qb) {
        $this->qb = $qb;
    }

    public function all(): array {
        return $this->qb->all();
    }

    public function find(int $id): ?array {
        return $this->qb->find($id);
    }

    public function create(array $data): void {
        $this->qb->insert($data);
    }

    public function update(int $id, array $data): void {
        $this->qb->update($id, $data);
    }

    public function delete(int $id): void {
        $this->qb->delete($id);
    }
}
