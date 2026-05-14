<?php

declare(strict_types=1);

namespace Core\Http;

use Core\Container\Container;

/**
 * Invokes controller actions resolved by the Router.
 * SRP: Only dispatches — separate from routing and rendering.
 */
class Dispatcher
{
    public function __construct(private readonly Container $container) {}

    public function dispatch(array $action, Request $request): Response
    {
        [$controllerClass, $method] = $action;

        /** @var object $controller */
        $controller = $this->container->make($controllerClass);

        return $controller->$method($request);
    }
}
