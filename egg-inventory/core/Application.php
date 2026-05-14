<?php

declare(strict_types=1);

namespace Core;

use Core\Container\Container;
use Core\Http\Dispatcher;
use Core\Http\Request;
use Core\Http\Response;
use Core\Http\Router;

/**
 * Application kernel — bootstraps and runs the request lifecycle.
 * SRP: Orchestrates the framework components; delegates all real work to them.
 */
class Application
{
    private readonly Router     $router;
    private readonly Dispatcher $dispatcher;

    public function __construct(public readonly Container $container)
    {
        $this->router     = new Router();
        $this->dispatcher = new Dispatcher($container);
    }

    public function router(): Router
    {
        return $this->router;
    }

    public function bind(string $abstract, \Closure|string $concrete): void
    {
        $this->container->bind($abstract, $concrete);
    }

    public function run(): void
    {
        $request = new Request();
        $matched = $this->router->resolve($request);

        if ($matched === null) {
            (Response::html('<h1>404 — Page Not Found</h1>', 404))->send();
            return;
        }

        $request->setRouteParams($matched['params']);

        $response = $this->dispatcher->dispatch($matched['action'], $request);
        $response->send();
    }
}
