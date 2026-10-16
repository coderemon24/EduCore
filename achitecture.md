# Project Architecture & Folder Guidelines

This document outlines the responsibilities of each directory in this Laravel application to maintain clean code separation and architectural consistency.

## 1. app/
Contains the core code of the application.

### `app/Http/`
Handles HTTP request lifecycle, inputs, and responses.
* **`Controllers/`**: Handle incoming HTTP requests, call necessary services/models, and return responses (JSON or Views). Keep controllers thin by delegating complex logic to Services.
* **`Middleware/`**: Filter HTTP requests entering the application (e.g., authentication, role checks, language settings).
* **`Requests/`**: Form Request validation classes. All incoming request data validation rules and custom authorization messages must be placed here, not in controllers.

### `app/Models/`
* Eloquent ORM models representing database tables. 
* Contains table relationships (`hasMany`, `belongsTo`, etc.), scopes, accessors, mutators, and model-specific logic.

### `app/Policies/`
* Authorization classes that determine if a user can perform a given action (e.g., `update`, `delete`) on a specific model instance.

### `app/Services/`
* Custom business logic layer. Complex operations, third-party API integrations (payment gateways, mailers), and multi-step transactions should reside here to keep controllers decoupled and testable.

### `app/Providers/`
* Service providers for bootstrapping application services, binding interfaces to implementations, or registering event listeners.

---

## 2. database/
Contains database migrations, model factories, and seeders.

* **`migrations/`**: Database schema version control. Each file defines changes to tables (creating columns, adding indexes, foreign keys).
* **`factories/`**: Blueprint definitions for generating fake model data, primarily used for testing and database seeding.
* **`seeders/`**: Classes used to populate the database with initial, default, or dummy records (e.g., default admin user, settings).

---

## 3. routes/
Defines all application routes.

* **`web.php`**: Routes intended for the web interface. These are loaded with the `web` middleware group, providing session state, CSRF protection, and cookie encryption.
* **`console.php`**: Definition of custom Closure-based Artisan commands or scheduled background tasks (Task Scheduling).
