<?php
require __DIR__ . '/../vendor/autoload.php';

use Core\Http\Request;
use Core\Http\Response;
use Core\Http\Router;  
use Core\View\Engine;
use App\Models\Egg;
use App\Controllers\EggController;

$request = new Request();
$response = new Response();
$router = new Router();


require __DIR__ . '/../routes/web.php';


$action = $router->resolve($request);

if ($action) {
    [$class, $method] = $action;

    
    $controller = new $class(new Egg(), new Engine());

    $controller->$method($request);
} else {
    $response->send("404 Not Found");
}
