# SOLID Design Principles — Justification Document
**Project:** PHP MVC Framework — Egg Inventory  
**Student:** SP ELEC 2A · Advanced Web Development  
**Academic Year:** 2025–2026

---

## S — Single Responsibility Principle

> *One class, one reason to change.*

Every class in this framework was designed to own exactly one concern.

**`Core\Http\Router`** (`core/Http/Router.php`)  
Registers route patterns and resolves incoming URI + method combinations to a `[controller, action]` pair. It does **not** instantiate controllers, render views, or touch the database. If routing logic changes, this is the only file that changes.

**`Core\Http\Dispatcher`** (`core/Http/Dispatcher.php`)  
Receives the resolved action from the Router and invokes the controller method. It is a separate class from Router because dispatching (instantiating and calling controllers) is a distinct responsibility from matching URLs.

**`Core\Http\Request`** (`core/Http/Request.php`)  
Wraps all incoming HTTP data (`$_GET`, `$_POST`, `$_SERVER`). It never validates, never writes a response, and never touches the database.

**`Core\Http\Response`** (`core/Http/Response.php`)  
Responsible only for assembling and sending the HTTP response (status code, headers, body). It does not read input or build content.

**`Core\View\Engine`** (`core/View/Engine.php`)  
Locates and renders PHP template files. It never queries the database or processes form input.

**`App\Controllers\EggController`** (`app/Controllers/EggController.php`)  
Handles HTTP in/out: reads from `Request`, delegates persistence to the repository, delegates rendering to the view engine. It never contains SQL or raw HTML strings.

**`App\Models\EggRepository`** (`app/Models/EggRepository.php`)  
Handles all data-access for the `eggs` table. It never constructs HTTP responses or renders views.

---

## O — Open / Closed Principle

> *Open for extension, closed for modification.*

**`Core\Database\DatabaseDriver`** interface (`core/Database/DatabaseDriver.php`)  
Defines a single method: `connect(array $config): PDO`. New database engines are added by **creating a new class**, not by changing existing code.

**`Core\Database\SQLiteDriver`** and **`Core\Database\MySQLDriver`**  
Both implement `DatabaseDriver` without touching `Connection.php`. To switch from SQLite to MySQL, one line changes in `public/index.php`:

```php
// Before
$app->bind(DatabaseDriver::class, SQLiteDriver::class);

// After (zero changes elsewhere)
$app->bind(DatabaseDriver::class, MySQLDriver::class);
```

`Connection.php` itself is **never modified** to support a new driver — it is closed for modification.

**`Core\Http\Router`**  
Routes are registered from `routes/web.php`; the Router class never changes when new routes are added.

---

## L — Liskov Substitution Principle

> *Subtypes must be fully substitutable for their base type.*

**`MySQLDriver` and `SQLiteDriver`** both implement `DatabaseDriver::connect(array $config): PDO`.  
They share the same signature, return the same type (`PDO`), and honour the same contract. Any code that receives a `DatabaseDriver` can use either driver interchangeably — no surprising side effects, no method stubbing.

**`EggRepository`** implements `EggRepositoryInterface` (which extends `Findable` and `Persistable`).  
All methods honour their declared signatures. A read-only repository could implement only `Findable` without being forced to provide `save()` or `delete()`.

---

## I — Interface Segregation Principle

> *No client should be forced to depend on methods it does not use.*

Instead of one fat `RepositoryInterface` with every possible method, the framework defines two thin interfaces:

**`Core\Database\Findable`** (`core/Database/Findable.php`)
```php
interface Findable {
    public function find(int|string $id): ?array;
    public function all(): array;
}
```

**`Core\Database\Persistable`** (`core/Database/Persistable.php`)
```php
interface Persistable {
    public function save(array $data): int|string;
    public function update(int|string $id, array $data): bool;
    public function delete(int|string $id): bool;
}
```

A future **read-only analytics repository** could implement only `Findable` — it is never forced to stub `save()` or `delete()`.

`EggRepositoryInterface` composes both for the full CRUD use case:
```php
interface EggRepositoryInterface extends Findable, Persistable { ... }
```

---

## D — Dependency Inversion Principle

> *High-level modules should depend on abstractions, not concretions.*

**`App\Controllers\EggController`** declares its dependency as `EggRepositoryInterface` — **never** as `EggRepository`, `MySQLEggRepository`, or any concrete class:

```php
public function __construct(
    private readonly EggRepositoryInterface $eggs,  // abstraction
    private readonly Engine $view,
    private readonly Validator $validator
) {}
```

The concrete binding happens once, in `public/index.php`:

```php
$app->bind(EggRepositoryInterface::class, fn($c) =>
    new EggRepository($c->make(Connection::class))
);
```

**`Core\Container\Container`** (`core/Container/Container.php`) provides the DI container that performs constructor auto-wiring. High-level modules (`EggController`) stay decoupled from infrastructure (`EggRepository`, `Connection`, `SQLiteDriver`). Swapping the data store requires only one binding change — no controller code changes.

**`Core\Database\Connection`** depends on `DatabaseDriver` (the abstraction), not `SQLiteDriver` or `MySQLDriver` (the concretions).

---

## Summary Table

| Principle | Class(es) | How Applied |
|-----------|-----------|-------------|
| **S** | `Router`, `Dispatcher`, `Request`, `Response`, `Engine`, `EggController`, `EggRepository` | Each class has one, well-defined reason to change |
| **O** | `DatabaseDriver`, `SQLiteDriver`, `MySQLDriver`, `Connection`, `Router` | New drivers/routes added without modifying existing classes |
| **L** | `SQLiteDriver`, `MySQLDriver`, `EggRepository` | All implementations are fully substitutable for their interfaces |
| **I** | `Findable`, `Persistable`, `EggRepositoryInterface` | Interfaces split by read/write concern; no forced stubbing |
| **D** | `EggController`, `Connection`, `Container` | All high-level classes depend on abstractions; container wires concretions at runtime |
