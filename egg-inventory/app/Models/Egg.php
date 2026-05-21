<?php
namespace App\Models;

use Core\Database\QueryBuilder;

class Egg {
    private QueryBuilder $db;
    private string $table = 'eggs';

    public function __construct() {
        $this->db = new QueryBuilder();
    }

    public function all(): array {
        return $this->db->all($this->table);
    }

    public function find(int $id): ?array {
        return $this->db->find($this->table, $id);
    }

    public function create(array $data): int {
        return $this->db->insert($this->table, $data);
    }

    public function update(int $id, array $data): bool {
        return $this->db->update($this->table, $id, $data);
    }

    public function delete(int $id): bool {
    return $this->db->delete($this->table, $id);
}

}
