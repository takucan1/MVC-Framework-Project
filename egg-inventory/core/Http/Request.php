<?php
namespace Core\Http;

class Request {
    public function method(): string {
        return $_SERVER['REQUEST_METHOD'];
    }
    public function uri(): string {
        return trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
    }
    public function input(string $key): ?string {
        return $_POST[$key] ?? $_GET[$key] ?? null;
    }
}
