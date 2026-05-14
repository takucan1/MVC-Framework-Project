# EggTrack — PHP MVC Framework · Egg Inventory

**SP ELEC 2A — Advanced Web Development · Final Project**  
PHP 8.3+ · PSR-4 · SOLID · SQLite · No external frameworks

---

## Setup Instructions

### Requirements
- PHP 8.3 or higher (with `pdo_sqlite` extension)
- Composer

### Install

```bash
git clone <your-repo-url> egg-inventory
cd egg-inventory
composer install
```

### Database Setup

Run the migration script to create the `eggs` table and seed sample data:

```bash
php migrate.php
```

### Start the Development Server

```bash
php -S localhost:8000 -t public/
```

Visit **http://localhost:8000/eggs**

### Apache / Nginx

Set the document root to `public/`. The included `.htaccess` handles URL rewriting for Apache. For Nginx, add a `try_files` directive pointing to `index.php`.

---

## Route List

| Method | URI                    | Controller Action          | Description            |
|--------|------------------------|---------------------------|------------------------|
| GET    | `/eggs`                | `EggController@index`     | List all batches + stock summary |
| GET    | `/eggs/create`         | `EggController@create`    | Show create form        |
| POST   | `/eggs`                | `EggController@store`     | Store new batch         |
| GET    | `/eggs/{id}`           | `EggController@show`      | Show single batch       |
| GET    | `/eggs/{id}/edit`      | `EggController@edit`      | Show edit form          |
| POST   | `/eggs/{id}/update`    | `EggController@update`    | Apply edits             |
| POST   | `/eggs/{id}/delete`    | `EggController@destroy`   | Delete batch            |

**7 routes total** — exceeds the 5-route minimum requirement.

---

## MVP Application

**EggTrack** is a full CRUD inventory system for three egg types: Quail, White, and Brown eggs.

### Features
- Dashboard with live stock summary (total quantity per egg type, batch count)
- Full CRUD: create, read, update, delete egg batches
- Input validation with inline error display (type, quantity, unit price required)
- Total batch value calculated from quantity × unit price
- Notes field for supplier/origin information
- Date tracking per batch

### Egg Types Supported
| Type  | Badge Colour | Description |
|-------|-------------|-------------|
| Quail | Warm brown  | Small speckled eggs |
| White | Light grey  | Commercial white eggs |
| Brown | Deep brown  | Heritage/free-range brown eggs |

---

## Framework Design Decisions

### Front Controller (`public/index.php`)
All HTTP requests are routed through a single entry point. This is the **only** file that uses `require` — all other classes are resolved via Composer's PSR-4 autoloader.

### Two-Phase Routing
Routing and dispatching are intentionally split into two classes:
- **`Router`** — only matches URIs to actions (SRP)
- **`Dispatcher`** — only invokes controller methods (SRP)

### DI Container
`Core\Container\Container` performs reflection-based constructor auto-wiring. Abstractions are bound to concretions once in `public/index.php` — no service discovery magic, no configuration files, just explicit bindings.

### Database Driver Pattern
The `DatabaseDriver` interface + `SQLiteDriver`/`MySQLDriver` implementations demonstrate OCP and LSP. Switching databases is a single `bind()` change.

### Interface Segregation in Repositories
`Findable` (read) and `Persistable` (write) are separate interfaces. `EggRepositoryInterface` composes both for full CRUD. A future read-only analytics repository would implement only `Findable`.

### No Manual `require` for Classes
Zero manual `require` or `include` statements for class files anywhere in the codebase. All classes are loaded via Composer's PSR-4 autoloader.

---

## PSR-4 Namespace Map

```json
"autoload": {
    "psr-4": {
        "Core\\": "core/",
        "App\\":  "app/"
    }
}
```

| Namespace | Directory | Purpose |
|-----------|-----------|---------|
| `Core\`   | `core/`   | Framework engine (Http, Database, View, Container) |
| `App\`    | `app/`    | Application layer (Controllers, Models, Views, Middleware) |

---

## Project Structure

```
egg-inventory/
├── app/
│   ├── Controllers/
│   │   └── EggController.php
│   ├── Middleware/
│   │   └── Validator.php
│   ├── Models/
│   │   ├── Egg.php
│   │   ├── EggRepository.php
│   │   └── EggRepositoryInterface.php
│   └── Views/
│       ├── eggs/
│       │   ├── index.php
│       │   ├── create.php
│       │   ├── show.php
│       │   └── edit.php
│       └── layouts/
│           ├── app.php
│           └── wrap.php
├── core/
│   ├── Http/
│   │   ├── Request.php
│   │   ├── Response.php
│   │   ├── Router.php
│   │   └── Dispatcher.php
│   ├── Database/
│   │   ├── DatabaseDriver.php  ← interface
│   │   ├── SQLiteDriver.php
│   │   ├── MySQLDriver.php
│   │   ├── Connection.php
│   │   ├── QueryBuilder.php
│   │   ├── Model.php
│   │   ├── Findable.php        ← ISP interface
│   │   └── Persistable.php     ← ISP interface
│   ├── View/
│   │   └── Engine.php
│   ├── Container/
│   │   └── Container.php       ← DI container
│   └── Application.php
├── config/
│   ├── app.php
│   └── database.php
├── public/
│   ├── index.php               ← only file with require
│   └── .htaccess
├── routes/
│   └── web.php
├── storage/
│   └── eggs.sqlite
├── composer.json
├── migrate.php
├── SOLID_JUSTIFICATION.md
└── README.md
```
