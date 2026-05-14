<?php

declare(strict_types=1);

use App\Controllers\EggController;
use Core\Http\Router;

/**
 * All application routes.
 * Routes are registered here; the Router never changes for new routes (OCP).
 *
 * Route list (7 routes — exceeds the 5-route minimum):
 *   GET  /eggs                   → EggController@index   (list all)
 *   GET  /eggs/create            → EggController@create  (show form)
 *   POST /eggs                   → EggController@store   (save new)
 *   GET  /eggs/{id}              → EggController@show    (detail)
 *   GET  /eggs/{id}/edit         → EggController@edit    (show edit form)
 *   POST /eggs/{id}/update       → EggController@update  (apply edit)
 *   POST /eggs/{id}/delete       → EggController@destroy (delete)
 */
return function (Router $router): void {
    $router->get('/eggs',                [EggController::class, 'index']);
    $router->get('/eggs/create',         [EggController::class, 'create']);
    $router->post('/eggs',               [EggController::class, 'store']);
    $router->get('/eggs/{id}',           [EggController::class, 'show']);
    $router->get('/eggs/{id}/edit',      [EggController::class, 'edit']);
    $router->post('/eggs/{id}/update',   [EggController::class, 'update']);
    $router->post('/eggs/{id}/delete',   [EggController::class, 'destroy']);
};
