Migration 20260223 - Add status fields and change foreign key delete behavior
=================================================

What this migration does
- Adds `tenants.status` (ENUM 'active','inactive') default 'active'
- Adds `payments.status` (ENUM 'active','canceled') default 'active'
- Adds `payments.cancellation_reason` (VARCHAR(255))
- Recreates foreign keys for `payments.tenant_id` and `tenants.apartment_id` with ON DELETE RESTRICT

Important notes
- BACKUP your database before running the SQL.
- This script preserves the existing `amount_remaining` column; the application will stop using it.
- If DROP FOREIGN KEY fails, inspect existing constraint names in `information_schema.KEY_COLUMN_USAGE` and adjust the SQL accordingly.

How to run
1) From your project root, run:

```bash
mysql -u <user> -p bms_db < migrations/20260223_add_status_and_constraints.sql
```

2) Or run the SQL via phpMyAdmin / MySQL Workbench.

After running
- Confirm the new columns exist and that foreign keys were recreated with ON DELETE RESTRICT.
- Tell me when done and I will proceed to refactor models/controllers/views to use the new fields and enforce the payment logic.
