<?php

declare(strict_types=1);

namespace Core\Http;

/**
 * Resolves URIs to controller actions.
 * SRP: Only routes — does not dispatch, does not render.
 * OCP: New routes are registered; this class never changes to add new routes.
 */
class Router
{
    private array $routes = [];

    public function register(string $method, string $uri, array $action): void
    {
        $this->routes[] = [
            'method' => strtoupper($method),
            'uri'    => $uri,
            'action' => $action,
        ];
    }

    /** Convenience helpers */
    public function get(string $uri, array $action): void
    {
        $this->register('GET', $uri, $action);
    }

    public function post(string $uri, array $action): void
    {
        $this->register('POST', $uri, $action);
    }

    /**
     * Resolve the incoming request to a matching route action.
     * Returns ['action' => [...], 'params' => [...]] or null on no match.
     */
    public function resolve(Request $request): ?array
    {
        foreach ($this->routes as $route) {
            if ($route['method'] !== $request->method()) {
                continue;
            }

            $params = $this->matchUri($route['uri'], $request->uri());
            if ($params !== null) {
                return ['action' => $route['action'], 'params' => $params];
            }
        }
        return null;
    }

    /**
     * Match a route pattern (e.g. /eggs/{id}) against a real URI.
     * Returns an array of named params on match, null otherwise.
     */
    private function matchUri(string $pattern, string $uri): ?array
    {
        // Escape and replace {param} with named capture groups
        $regex  = preg_replace('/\{([a-zA-Z_]+)\}/', '(?P<$1>[^/]+)', $pattern);
        $regex  = '#^' . $regex . '$#';

        if (preg_match($regex, $uri, $matches)) {
            // Keep only named (string-keyed) captures
            return array_filter(
                $matches,
                fn($key) => is_string($key),
                ARRAY_FILTER_USE_KEY
            );
        }
        return null;
    }
}
