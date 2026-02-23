PROJECT DOCUMENTATION (DETAILED)
================================
This document contains a detailed technical description of the Building Management System (BMS) codebase. It is intended for developers who will maintain or extend the project.

1) Project Overview
- Purpose: Manage apartments, tenants, and payments with audit/history preserved.
- Tech stack: PHP (8.x), MySQL/MariaDB, simple custom MVC structure.

2) Directory map and responsibilities
- /app
  - /Controllers: controllers handle HTTP requests, validate input, call models, and render views.
    * `ApartmentsController.php` — list/add/mark vacant (soft). Prevents marking vacant when active tenants exist.
    * `TenantsController.php` — list/add/terminate contract (soft). Validates apartment availability.
    * `PaymentsController.php` — list/add/cancel payments. Enforces dynamic remaining calculation and overpayment prevention.
    * `DashboardController.php` — collects stats using models.

  - /Models: data access layer wrapping DB operations (`app/Core/Model` provides DB wrapper).
    * `Apartment.php` — CRUD-ish methods for apartments. `deleteApartment()` sets `status='vacant'`.
    * `Tenant.php` — tenant operations. New helpers: `getActiveTenants()`, `terminateTenant()`, `countActiveByApartment()`.
    * `Payment.php` — payment operations. New behaviors: `addPayment()` does not store amount_remaining; `cancelPayment()` stores `cancellation_reason`; `sumActivePaymentsByTenant()`.

- /views: front-end templates (header/footer/sidebar) and module-specific views in subfolders.

- /public: public index and assets.

- /migrations: SQL scripts for DB schema updates (e.g., adding `status` columns and changing FK delete behavior).

3) Database schema (high-level)
- tables: apartments, tenants, payments, users
- Important columns:
  - apartments.status ENUM('vacant','occupied')
  - tenants.status ENUM('active','inactive')
  - payments.status ENUM('active','canceled')
  - payments.cancellation_reason VARCHAR(255)
  - tenants.rent_amount DECIMAL(10,2)

4) Key business rules (enforced in code)
- Remaining always computed: `remaining = tenant.rent_amount - SUM(amount_paid WHERE status='active')`.
- Overpayment is rejected server-side.
- Payments for inactive tenants are rejected.
- Cancelling a payment sets `payments.status='canceled'` (keeps record).
- Terminating a tenant sets `tenants.status='inactive'`; apartment set to `vacant`.
- Apartments cannot be marked vacant if they have active tenants.

5) File-level map (important functions)
- `app/Models/Payment.php`
  - `addPayment($data)` — inserts `tenant_id, amount_paid, payment_date, notes, status`.
  - `cancelPayment($id, $reason)` — updates `status='canceled', cancellation_reason`.
  - `sumActivePaymentsByTenant($tenant_id)` — returns numeric sum.
  - `totalRevenue()` — sums payments where `status='active'`.

- `app/Models/Tenant.php`
  - `getActiveTenants()` — return tenants where `status='active'` joined with apartment data.
  - `terminateTenant($id)` — updates tenant to `inactive`.
  - `countActiveByApartment($apartment_id)` — returns count of active tenants for apartment.

- `app/Controllers/PaymentsController.php`
  - `add()` — validate, compute remaining, prevent overpayment, insert payment, flash messages and redirect.
  - `index()` — attach dynamic `remaining` to each payment row for display.
  - `cancel($id)` — GET shows cancel form (collect reason); POST performs cancel.

6) UI & Views notes
- Payment add page shows validation inline and remains on the same form if validation fails. Uses the existing layout to preserve styling.
- Payment cancel flows through `views/payments/cancel.php` to collect reason.

7) Runtime behaviors and edge cases
- Race condition: concurrent payment submissions may theoretically overpay if both pass validation simultaneously. Recommended mitigation: wrap remaining computation + insert in a DB transaction with row-level locking (`SELECT ... FOR UPDATE`) or use an upsert pattern.
- Referential integrity: migration changes FKs to `ON DELETE RESTRICT`. If CASCADE remains, run migration again and verify.

8) How to add a new feature (developer guide)
- Follow MVC pattern: add model method(s) in `app/Models`, add controller action in `app/Controllers`, create views under `views/<module>`, add route in Router (if applicable).
- Use `App\Core\Csrf::field()` in forms and `App\Core\Csrf::verify()` in POST handlers.
- Use `Session::flash()` for site-wide notifications displayed in layout.

9) Testing checklist (manual)
- See `CHANGELOG_CHANGES_REPORT.txt` and README for step-by-step test scenarios.

10) Contacts and notes
- Developer notes: major refactor on 2026-02-23. For migrations and DB backups, always export a SQL dump before running changes.

-- Arabic section (النسخة العربية مفصّلة):

مقدمة
هذا المستند مخصّص للمطورين؛ يشرح بنية المشروع، قواعد العمل، وخرائط الملفات لتسهيل الصيانة والتطوير.

هيكل المجلدات
- `app/Controllers`: يتحكم في طلبات HTTP.
- `app/Models`: تنفيذ عمليات DB.
- `views/`: قوالب العرض.

قواعد العمل الأساسية
- لا يتم حذف الدفعات أو المستأجرين أو الشقق مادياً؛ تُستخدم أعمدة `status` لحفظ السجلات.
- المبلغ المتبقي يحسب دائماً ديناميكياً.

دليل سريع للملفات المهمة
- `app/Controllers/PaymentsController.php`: عمليات الدفع، التحقق من عدم تجاوز المبلغ، وإلغاء الدفعات مع سبب.
- `app/Models/Payment.php`: عمليات القراءة والكتابة للدفعات، جمع المدفوعات النشطة، وحفظ سبب الإلغاء.

اختبار يدوي موصى به
- إضافة مستأجر، تسجيل دفعات، اختبار حالات الرفض عند محاولة الدفع المفرط.

إن احتجت، أستطيع توليد ملف diffs مفصل لكل ملف تم تغييره، أو إضافة حماية التزامن على مسار تسجيل الدفعات.
