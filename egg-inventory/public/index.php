<?php

declare(strict_types=1);

/**
 * Front Controller — the single entry point for all HTTP requests.
 * PSR-4 constraint: this is the ONLY file permitted to use require/include.
 */

require __DIR__ . '/../vendor/autoload.php';

use App\Controllers\EggController;
use App\Middleware\Validator;
use App\Models\EggRepository;
use App\Models\EggRepositoryInterface;
use Core\Application;
use Core\Container\Container;
use Core\Database\Connection;
use Core\Database\DatabaseDriver;
use Core\Database\MySQLDriver;
use Core\Database\QueryBuilder;
use Core\Database\SQLiteDriver;
use Core\View\Engine;

// ── Bootstrap ─────────────────────────────────────────────────────────────────

$dbConfig  = require __DIR__ . '/../config/database.php';
$appConfig = require __DIR__ . '/../config/app.php';

$container = new Container();
$app       = new Application($container);

// ── DI Bindings (Dependency Inversion Principle) ──────────────────────────────

// Bind DatabaseDriver → SQLiteDriver (swap to MySQLDriver with one line change)
$app->bind(DatabaseDriver::class, SQLiteDriver::class);

// Bind Connection — uses the driver abstraction
$app->bind(Connection::class, fn(Container $c) =>
    new Connection($c->make(DatabaseDriver::class), $dbConfig)
);

// Bind QueryBuilder
$app->bind(QueryBuilder::class, fn(Container $c) =>
    new QueryBuilder($c->make(Connection::class)->pdo())
);

// DIP key binding: controllers depend on EggRepositoryInterface, not EggRepository
$app->bind(EggRepositoryInterface::class, fn(Container $c) =>
    new EggRepository($c->make(Connection::class))
);

// Bind View Engine with views path from config
$app->bind(Engine::class, fn() =>
    new Engine($appConfig['views'])
);

// Bind Validator (no dependencies)
$app->bind(Validator::class, fn() => new Validator());

// Bind EggController — receives interface, not concrete class
$app->bind(EggController::class, fn(Container $c) =>
    new EggController(
        $c->make(EggRepositoryInterface::class),
        $c->make(Engine::class),
        $c->make(Validator::class)
    )
);

// ── Routes ────────────────────────────────────────────────────────────────────

$registerRoutes = require __DIR__ . '/../routes/web.php';
$registerRoutes($app->router());

// ── Redirect / to /eggs ───────────────────────────────────────────────────────

$app->router()->get('/', [EggController::class, 'index']);

// ── Run ───────────────────────────────────────────────────────────────────────

$app->run();
