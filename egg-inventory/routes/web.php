<?php
use App\Controllers\EggController;
use Core\Http\Request;

$router->register('GET', '', [EggController::class, 'index']); 
$router->register('GET', 'eggs', [EggController::class, 'index']);
$router->register('GET', 'eggs/show', [EggController::class, 'show']);
$router->register('GET', 'eggs/create', [EggController::class, 'create']);
$router->register('POST', 'eggs/create', [EggController::class, 'create']);
$router->register('GET', 'eggs/edit', [EggController::class, 'edit']);
$router->register('POST', 'eggs/edit', [EggController::class, 'edit']);
$router->register('GET', 'eggs/delete', [EggController::class, 'delete']);
