<?php

declare(strict_types=1);

namespace Core\Container;

use Closure;
use ReflectionClass;
use RuntimeException;

/**
 * Simple dependency injection container.
 * DIP: Controllers and classes depend on abstractions.
 *      The container resolves the concrete implementation at runtime.
 */
class Container
{
    /** @var array<string, Closure|string> */
    private array $bindings = [];

    /**
     * Bind an abstract type to a concrete implementation or factory.
     */
    public function bind(string $abstract, Closure|string $concrete): void
    {
        $this->bindings[$abstract] = $concrete;
    }

    /**
     * Resolve a class or interface, auto-wiring constructor dependencies.
     */
    public function make(string $abstract): object
    {
        $concrete = $this->bindings[$abstract] ?? $abstract;

        if ($concrete instanceof Closure) {
            return $concrete($this);
        }

        return $this->build($concrete);
    }

    /**
     * Instantiate a concrete class, auto-resolving its constructor parameters.
     */
    private function build(string $class): object
    {
        $reflector = new ReflectionClass($class);

        if (!$reflector->isInstantiable()) {
            throw new RuntimeException("Class [{$class}] is not instantiable.");
        }

        $constructor = $reflector->getConstructor();

        if ($constructor === null) {
            return new $class();
        }

        $dependencies = array_map(function ($param) use ($class) {
            $type = $param->getType();

            if ($type === null || $type->isBuiltin()) {
                throw new RuntimeException(
                    "Cannot auto-wire primitive parameter \${$param->getName()} in [{$class}]."
                );
            }

            return $this->make($type->getName());
        }, $constructor->getParameters());

        return $reflector->newInstanceArgs($dependencies);
    }
}
