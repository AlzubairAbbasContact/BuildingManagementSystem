# Building Management System (BMS)

Lightweight PHP MVC application for managing apartments, tenants and payments.

Overview
- Language: PHP 8.x
- Architecture: Simple MVC (app/Controllers, app/Models, views/)
- DB: MySQL / MariaDB (see `bms_db.sql`)

Features
- Apartment management (vacant/occupied)
- Tenant management (add, terminate contract)
- Payments management (add, cancel with reason)
- Soft-delete semantics: tenants/payments/apartments preserved (status fields)
- Dynamic financial calculations: no stored "remaining" values

Quick start
1. Copy the repository to your webserver (e.g., XAMPP htdocs).
2. Create a MySQL database (e.g., `bms_db`) and import `bms_db.sql`.
3. Run migration: `migrations/20260223_add_status_and_constraints.sql` to add `status` columns and adjust FKs.
4. Configure DB in `app/Config/config.php` (DB credentials).
5. Point your webserver document root to `public/` and open the app in your browser.

Key files and structure
- `app/Models` — DB models (Apartment, Tenant, Payment, User)
- `app/Controllers` — Controllers that handle requests and business logic
- `views/` — HTML/PHP views and layout
- `public/` — public entry (`index.php`), assets
- `migrations/` — migration SQL files

Important behaviors
- Payments: remaining = tenant.rent_amount - SUM(active payments)
- Payments canceled set `payments.status = 'canceled'` and optional `cancellation_reason`
- Tenant termination: `tenants.status = 'inactive'`; apartment set to `vacant`
- Apartments: delete action sets `status = 'vacant'` (no hard delete)

Testing checklist
- See `PROJECT_DOCUMENTATION_FULL.md` for a full manual test plan.

Support / development notes
- Changes made on 2026-02-23: migration, soft-delete behaviors, payment logic refactor, UI additions for cancellation reason. See `CHANGELOG_CHANGES_REPORT.txt`.
