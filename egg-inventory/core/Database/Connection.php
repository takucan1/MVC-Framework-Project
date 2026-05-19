<?php
namespace Core\Database;

class Connection {
    private string $file;

    public function __construct(string $file) {
        $this->file = $file;
        if (!file_exists($file)) {
            file_put_contents($file, json_encode([]));
        }
    }

    public function read(): array {
        return json_decode(file_get_contents($this->file), true);
    }

    public function write(array $data): void {
        file_put_contents($this->file, json_encode($data, JSON_PRETTY_PRINT));
    }
}
