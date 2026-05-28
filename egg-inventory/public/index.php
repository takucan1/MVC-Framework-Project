<?php
require __DIR__ . '/../vendor/autoload.php';

use Core\Http\Request;
use Core\Http\Response;
use Core\Http\Router;  
use Core\View\Engine;
use Core\Application;



$request = new Request();
$response = new Response();
$router = new Router();



require __DIR__ . '/../routes/web.php';



$app = new Application($router, $request, $response);
$app->run();
