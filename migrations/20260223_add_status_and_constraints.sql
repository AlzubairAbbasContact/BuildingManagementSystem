-- Migration: Add status columns and change FK on-delete to RESTRICT
-- IMPORTANT: BACKUP your database before running this script.
-- Run via: mysql -u root -p bms_db < 20260223_add_status_and_constraints.sql

START TRANSACTION;

-- 1) Add status to tenants
ALTER TABLE `tenants`
  ADD COLUMN `status` ENUM('active','inactive') NOT NULL DEFAULT 'active';

-- 2) Add status and cancellation_reason to payments
ALTER TABLE `payments`
  ADD COLUMN `status` ENUM('active','canceled') NOT NULL DEFAULT 'active',
  ADD COLUMN `cancellation_reason` VARCHAR(255) DEFAULT NULL;

-- 3) Change foreign keys to ON DELETE RESTRICT
-- Note: This assumes the existing constraint names are payments_ibfk_1 and tenants_ibfk_1
-- If your constraint names differ, adjust the DROP FOREIGN KEY lines accordingly.

ALTER TABLE `payments` DROP FOREIGN KEY `payments_ibfk_1`;
ALTER TABLE `payments` ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`tenant_id`) REFERENCES `tenants`(`id`) ON DELETE RESTRICT;

ALTER TABLE `tenants` DROP FOREIGN KEY `tenants_ibfk_1`;
ALTER TABLE `tenants` ADD CONSTRAINT `tenants_ibfk_1` FOREIGN KEY (`apartment_id`) REFERENCES `apartments`(`id`) ON DELETE RESTRICT;

COMMIT;

-- NOTES:
-- 1) This migration keeps the existing `amount_remaining` column intact (per your request).
-- 2) After running this, update application code to stop using `amount_remaining` and to respect new status fields.
-- 3) If any DROP FOREIGN KEY fails because the constraint name differs, check the constraint name with:
--    SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE
--    WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='payments' AND COLUMN_NAME='tenant_id';
