# Tasks Checklist - Super Admin & Tenant Expenses & Accounting

## Landlord Platform Accounting
- [x] Create PlatformExpense migration
- [x] Create PlatformExpense model
- [x] Create Platform ExpenseController
- [x] Register Expense Routes in web.php
- [x] Create Expense Index View
- [x] Update Super Admin Left Sidebar in Views
- [x] Fix whitespace-nowrap and column formatting f dashboard table cells
- [x] Write Feature Tests f PlatformTest.php
- [x] Run PHPUnit tests locally
- [x] Deploy to cPanel and run landlord migrations

## 2. Platform Central & Tenant Layouts
- [x] Rebrand suspended/error views (suspended.blade.php)
- [x] Update landlord views (dashboard.blade.php, tenants/create.blade.php, platform login)
- [x] Rebrand tenant views (landing.blade.php, formulaire-contrat.blade.php, gestion-agence.blade.php)
- [x] Update settings defaults & placeholders in GestionAgence.php, PDFController.php, and database seedersts locally
- [x] Deploy to cPanel and run landlord migrations

## Tenant Agency Accounting & Cashflow
- [x] Create migration f tenant agency_expenses table
- [x] Create AgencyExpense model at tenant level
- [x] Create tenant-level GestionCharges Livewire component
- [x] Create blade view f tenant-level GestionCharges Livewire component
- [x] Register charges route in tenant routes file
- [x] Add charges/expenses link to tenant navigation menu
- [x] Update tenant AdminDashboard to calculate gross production (inflow +), total expenses (outflow -), and net cashflow balance
- [x] Add test cases f AdministrationTest.php
- [x] Run PHPUnit tests locally
- [x] Deploy to cPanel and run tenant migrations

## Direct Tenant Branch Creation f Super Admin Dashboard [NEW]
- [x] Register direct branch creation/deletion routes f routes/web.php
- [x] Implement storeSuccursale() and destroySuccursale() methods f Platform DashboardController.php
- [x] Redesign Edit Agence view (edit.blade.php) to display the existing branches and a creation form directly f the Super Admin context
- [x] Add unit test cases in PlatformTest.php
- [x] Run PHPUnit tests locally
- [x] Deploy to cPanel
