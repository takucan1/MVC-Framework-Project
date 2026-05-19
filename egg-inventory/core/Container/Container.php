<?php
namespace Core;

class Container {
    private array $bindings = [];

    public function bind(string $abstract, string $concrete): void {
        $this->bindings[$abstract] = $concrete;
    }

    public function resolve(string $abstract): object {
        $concrete = $this->bindings[$abstract] ?? $abstract;
        return new $concrete();
    }
}
