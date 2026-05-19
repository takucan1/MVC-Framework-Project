<?php
namespace App\Models;

use Core\Database\Connection;

class Egg {
    private Connection $db;

    public function __construct(Connection $db) {
        $this->db = $db;
    }

    public function all(): array {
        return $this->db->read();
    }

    public function find(int $id): ?array {
        $eggs = $this->db->read();
        return $eggs[$id] ?? null;
    }

    public function create(array $data): void {
        $eggs = $this->db->read();
        $data['date'] = date('Y-m-d');
        $eggs[] = $data;
        $this->db->write($eggs);
    }

    public function update(int $id, array $data): void {
        $eggs = $this->db->read();
        $data['date'] = date('Y-m-d');
        $eggs[$id] = $data;
        $this->db->write($eggs);
    }

    public function delete(int $id): void {
        $eggs = $this->db->read();
        unset($eggs[$id]);
        $this->db->write($eggs);
    }
}
