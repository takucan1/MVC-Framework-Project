<?php
namespace Core\Http;

use Core\Http\Request;

class Router {
    private array $routes = [];

    public function register(string $method, string $uri, array $action): void {
        $this->routes[$method][$uri] = $action;
    }

    public function resolve(Request $request): array {
        $method = $request->method();
        $uri = $request->uri();
        return $this->routes[$method][$uri] ?? [];
    }
}
