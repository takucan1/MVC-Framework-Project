<?php
namespace Core;

use Core\Http\Request;
use Core\Http\Response;
use Core\Http\Router;
use Core\View\Engine;
use App\Models\Egg;

class Application {
    private Router $router;
    private Request $request;
    private Response $response;
    private array $middleware = [];

    public function __construct(Router $router, Request $request, Response $response) {
        $this->router = $router;
        $this->request = $request;
        $this->response = $response;
    }

    public function addMiddleware(object $middleware): void {
        $this->middleware[] = $middleware;
    }

    public function run(): void {
        
        foreach ($this->middleware as $mw) {
            if (!$mw->handle($this->request)) {
                return;
            }
        }

        
        $action = $this->router->resolve($this->request);
        if ($action) {
            [$class, $method] = $action;

            $controller = new $class(new Egg(), new Engine());

            $controller->$method($this->request);
        } else {
            $this->response->send("404 Not Found");
        }
    }
}
