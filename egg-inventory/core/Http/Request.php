<?php

declare(strict_types=1);

namespace Core\Http;

/**
 * Wraps HTTP request data.
 * SRP: Only responsible for reading and providing HTTP input.
 */
class Request
{
    private readonly string $method;
    private readonly string $uri;
    private readonly array  $queryParams;
    private readonly array  $bodyParams;
    private array           $routeParams = [];

    public function __construct()
    {
        $this->method      = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
        $this->uri         = $this->parseUri();
        $this->queryParams = $_GET ?? [];
        $this->bodyParams  = $_POST ?? [];
    }

    private function parseUri(): string
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $pos = strpos($uri, '?');
        return $pos !== false ? substr($uri, 0, $pos) : $uri;
    }

    public function method(): string
    {
        return $this->method;
    }

    public function uri(): string
    {
        return $this->uri;
    }

    public function input(string $key, mixed $default = null): mixed
    {
        return $this->bodyParams[$key] ?? $this->queryParams[$key] ?? $default;
    }

    public function all(): array
    {
        return array_merge($this->queryParams, $this->bodyParams);
    }

    public function routeParam(string $key, mixed $default = null): mixed
    {
        return $this->routeParams[$key] ?? $default;
    }

    public function setRouteParams(array $params): void
    {
        $this->routeParams = $params;
    }

    public function isPost(): bool
    {
        return $this->method === 'POST';
    }
}
