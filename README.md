# Egg Inventory MVC Framework Project

A lightweight custom MVC framework built from scratch in PHP, demonstrating core architectural principles with a practical Egg Inventory Management application.

## MVP Application Overview

The Egg Inventory Manager is a simple CRUD application for managing egg inventory. 
Users can:
- View all eggs in inventory
- View individual egg details
- Create new egg entries
- Edit existing egg entries
- Delete egg entries

Each egg entry contains:
- **Type**: The breed or type of egg
- **Quantity**: The number of eggs in stock

This MVP demonstrates the core functionality of the custom MVC framework with a real-world use case.

---

## Framework Design Decisions

### 1. Modular Core Architecture
The framework is organized into distinct layers:
- **Application**: Entry point that manages the request lifecycle
- **Http**: Handles routing, requests, and responses
- **Database**: Query builder and model abstraction
- **View**: Template rendering engine
- **Container**: Dependency injection (foundation for future enhancements)

### 2. Routing System
- Simple pattern-based routing with HTTP method support (GET, POST)
- Routes are registered in `routes/web.php`
- Router resolves URLs to controller actions

### 3. Model-View-Controller Separation
- Models: Handle data persistence and business logic (extends `Model` base class)
- Controllers: Process requests and coordinate models/views
- Views: Simple PHP template files in `app/Views/`

### 4. Database Layer
- Custom `Model` class provides ORM-like functionality
- `QueryBuilder` offers chainable query methods
- `Connection` manages database interactions
- Currently supports MySQL

### 5. Middleware Support
- Framework supports middleware pipeline
- Middleware can intercept requests before controller execution
- Extensible through the `MiddlewareInterface`

### 6. Request/Response Objects
- Clean abstraction for HTTP interactions
- Request extracts input data from `$_GET`, `$_POST`, etc.
- Response handles output and headers

---

## Setup Instructions

### Prerequisites
- PHP 8.3+
- MySQL 5.7+
- Composer

### Installation

1. Navigate to project directory
   ```bash
   cd egg-inventory

2. Install dependencies
   composer install

3. Configure database
    <?php
    'host'     => 'localhost',
    'dbname'   => 'egg_inventory',
    'username' => 'root',
    'password' => 'root',

4. Create database
    USE egg_inventory;

    CREATE TABLE eggs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        type VARCHAR(50) NOT NULL,
        quantity INT NOT NULL,
        date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );

    select * from eggs;

5. Start development server
    
    php -S localhost:8080 -t public

    - Application will be available at http://localhost:8080/

Routes
Method  Route	        Controller Action	    Description
GET	    /	            EggController@index	    Display all eggs (home page)
GET	    /eggs	        EggController@index	    Display all eggs
GET	    /eggs/show	    EggController@show	    Display single egg details (requires id parameter)
GET	    /eggs/create	EggController@create	Show create egg form
POST    /eggs/create	EggController@create	Store new egg in database
GET	    /eggs/edit	    EggController@edit	    Show edit form (requires id parameter)
POST	/eggs/edit	    EggController@edit	    Update egg in database
GET	    /eggs/delete	EggController@delete	Delete egg from database (requires id parameter)

___________________________________________________________________________________________________________

PROJECT FOLDER STRUCTURE

egg-inventory/
├── app/
│   ├── Controllers/       # Request handlers
│   ├── Middleware/        # Request middleware
│   ├── Models/            # Data models
│   └── Views/             # HTML templates
├── config/                # Configuration files
├── core/                  # Framework core
│   ├── Container/         # Dependency injection
│   ├── Database/          # Database layer
│   ├── Http/              # HTTP handling
│   └── View/              # Template engine
├── public/                # Web root (index.php)
├── routes/                # Route definitions
├── vendor/                # Composer dependencies
└── composer.json          # PHP dependencies

