# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## 🚀 Project Overview

**PERPUSQU** is a professional library management system built with Laravel 13 using a **Domain-Driven Modular Monolith** architecture. The application provides comprehensive tools for managing physical collections, digital repositories, member services, and public discovery.

## 📋 Quick Start Commands

### Setup
```bash
composer run setup
```
Installs dependencies, generates app key, runs migrations, and builds assets.

### Development
```bash
composer run dev
```
Starts the full development stack (Server on :8000, Queue listener, Pail logs, Vite dev server) with concurrently.

### Testing
```bash
composer run test
```
Runs the full test suite (Unit and Feature tests).

```bash
php artisan test tests/Unit
php artisan test tests/Feature
php artisan test tests/Unit/SomeTest.php --filter=testMethod
```

### Linting
```bash
./vendor/bin/pint
```
Code style formatting with Laravel Pint (PSR-12).

### Database
```bash
php artisan migrate
php artisan migrate:rollback
php artisan tinker
```

## 🏗️ Architecture: Domain-Driven Modular Monolith

Each module in `app/Modules/` is a self-contained domain with the same structure:
```
ModuleName/
├── Http/              # Controllers and requests/responses
├── Models/            # Eloquent models
├── Policies/          # Authorization policies
├── Services/          # Business logic
├── Queries/           # Query builders (if used)
├── DTOs/              # Data Transfer Objects (if used)
├── Jobs/              # Queued jobs (if used)
├── Support/           # Helpers, traits, utilities
└── routes/            # Module-specific routes (web.php, api.php)
```

### Module Auto-Loading
Routes are auto-loaded via `ModuleRouteServiceProvider` — place `routes/web.php` in each module and it's automatically registered with the `web` middleware group.

### Modules in the System
- **Identity**: Authentication & RBAC (Spatie Permission)
- **Catalog**: Bibliographic records & metadata
- **Collection**: Physical item tracking
- **Circulation**: Loans, returns, fines
- **Member**: Patron/member management
- **Opac**: Public discovery portal
- **DigitalRepository**: Digital assets & OCR
- **Core**: Dashboard & system settings
- **MasterData**: Reference data (Authors, Publishers, etc.)
- **Audit**: Activity logging (Spatie Activity Log)
- **Profile**: User profile management
- **Reporting**: Analytics & reporting

## 🗄️ Database & Eloquent

- Uses Eloquent ORM with relationships
- Migrations in `database/migrations/`
- Seeders in `database/seeders/`
- Test database uses SQLite in-memory (`:memory:`)
- Models leverage Eloquent's conventions (e.g., `BibliographicRecord` table is `bibliographic_records`)

## 🔐 Authorization & Security

- **Spatie Laravel Permission** for granular RBAC (roles & permissions)
- Authorization policies in each module's `Policies/` directory
- Check permissions in controllers with `authorize()` or `policy()->method()`

## 📊 Activity Logging & Auditing

- **Spatie Activity Log** tracks changes to auditable models
- Activity records stored in `activity_log` table
- Module `Audit/` provides queries and services for audit trails

## 🎨 Frontend Stack

- **Tailwind CSS 4** for styling
- **Vite 6** for asset bundling
- Build: `npm run build`
- Dev: `npm run dev` (included in `composer run dev`)
- Frontend code in `resources/` (Blade views, CSS, JS)

## 🔄 Background Jobs & Queues

- **Laravel Horizon** for queue monitoring and job management
- Job classes in module `Jobs/` subdirectories (e.g., `DigitalRepository/Jobs/`)
- Dev queue listener included in `composer run dev`
- Test environment uses `sync` queue (jobs run immediately)

## 🧪 Testing

### Structure
- `tests/Unit/` — Unit tests (isolated, no DB)
- `tests/Feature/` — Feature/integration tests (full app stack)
- Test base class: `Tests/TestCase.php`

### Running Tests
```bash
composer run test                          # Full suite
php artisan test tests/Unit                # Unit only
php artisan test tests/Feature             # Feature only
php artisan test tests/Feature/SomeTest    # Single file
php artisan test --filter=methodName       # Specific test method
```

### Test Configuration
- SQLite in-memory database
- Sync queue (jobs run immediately)
- Array cache
- Test env vars in `phpunit.xml`

## 🔍 Code Patterns & Conventions

### Service Classes
Business logic lives in `Services/` with public methods. Example:
```php
// app/Modules/Catalog/Services/BibliographicRecordService.php
class BibliographicRecordService {
    public function create(array $data): BibliographicRecord { }
    public function update(BibliographicRecord $record, array $data): BibliographicRecord { }
}
```

### Query Builders (Queries/)
Encapsulate complex queries for filtering, eager-loading, etc. Useful for OPAC search logic or report generation.

### Data Transfer Objects (DTOs/)
Used in `Circulation/` and `DigitalRepository/` to pass typed data between layers. Example: `CheckoutDTO`, `DigitalAssetDTO`.

### Controllers
Keep controllers thin — delegate to services. Controllers handle routing/request validation, services handle domain logic.

### Policies
Authorization logic in `Policies/` — use `Gate` or `authorize()` to enforce in controllers/requests.

## 📦 Key Dependencies

- **Laravel 13**: Latest framework
- **Laravel Horizon**: Queue monitoring UI
- **Spatie Laravel Permission**: Role & permission management
- **Spatie Activity Log**: Audit trail tracking
- **Tailwind CSS 4 & Vite 6**: Frontend build stack
- **PHPUnit 12.5**: Testing framework

## 🛠️ Development Tips

1. **Add a new module**: Create directory in `app/Modules/{ModuleName}/` with subdirectories for Http, Models, Services, etc. Add routes file to wire up auto-loading.

2. **Add migrations**: `php artisan make:migration create_table_name` or edit existing migrations in `database/migrations/`.

3. **Test a feature**: Write in `tests/Feature/`, use `artisan test` to run. Tests auto-refresh in-memory SQLite DB per test.

4. **Check permissions**: Use `$user->hasPermissionTo('permission-name')` or the `@permission('name')` Blade directive.

5. **Activity logging**: If a model should be audited, add `use Loggable;` trait and configure in the model.

6. **Background jobs**: Dispatch with `Job::dispatch()`, define in module's `Jobs/` directory. Horizon UI at `/horizon` shows queue status.

## 📄 File Organization Reminders

- **Routes**: Module routes auto-load from `app/Modules/{ModuleName}/routes/web.php`
- **Models**: In each module's `Models/` directory
- **Controllers**: In each module's `Http/Controllers/` directory  
- **Requests**: In each module's `Http/Requests/` directory
- **Views**: Likely in `resources/views/`, organized by module
- **Config**: Global app config in `config/`
