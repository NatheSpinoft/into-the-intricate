# Project Daily Log

## Date: 2025-11-01

### Summary of Work

- **14:00 - 14:30**: Implemented delete functionality for invoices with security verification and confirmation dialogs.
- **14:30 - 15:00**: Updated `view_invoices.php` with delete button, success/error message display system, and improved styling.
- **15:00 - 15:30**: Reviewed `payables.php` structure and planned view/delete functionality for bills and expenses.
- **15:30 - 16:30**: Created complete payables viewing system with `view_payables.php`, `delete_payable.php`, and `view_payable_details.php`.
- **16:30 - 17:00**: Fixed database query issues in payables detail view to properly fetch items from `payable_items` table.
- **17:00 - 17:30**: Updated `delete_payable.php` to use transactions for safe deletion across `payables` and `payable_items` tables.
- **17:30 - 18:00**: Developed Python database backup script with automated backup, restore, and cleanup features.
- **18:00 - 18:30**: Configured backup script to read credentials from `config/config.php` for improved security.
- **18:30 - 19:00**: Created comprehensive `.gitignore` file and removed sensitive files from Git tracking before GitHub push.

### Checklist of Features / Tasks

- [x] Configure PDO database connection correctly  
- [x] Fix undefined variables and database selection errors  
- [x] Update `add_invoice.php` with transaction-safe inserts  
- [x] Support multiple invoice items with tax calculations  
- [x] Display current week's timecards dynamically  
- [x] Improve CSS styling for forms and tables  
- [x] Error checking
- [x] Add **Invoice** module 
- [x] Add **Invoice** PDF and delete actions
- [x] Add **Payables** module 
- [x] Add **Payables** view and delete actions  
- [x] Create automated database backup system with Python
- [x] Secure sensitive configuration files with `.gitignore`
- [x] Remove tracked sensitive files from Git repository
- [ ] Add **Reconcile** module  
- [ ] Implement edit functionality for invoices and payables
- [ ] Add search/filter capabilities to view pages

### Notes / Next Steps

- Schedule automated backups using Windows Task Scheduler or Cron
- Implement Reconcile module with reporting features  
- Add edit functionality for invoices and payables
- Implement search and filter options on view pages (by date, vendor/company, amount)
- Consider soft delete functionality (archive instead of permanent deletion)
- Refactor common functions to improve maintainability (e.g., tax calculation, total computation)  
- Add comprehensive input validation and error messages for all user-facing forms  
- Consider adding CSV export functionality for invoices and payables
- Create user documentation for backup system usage

---

## Date: 2025-10-19

### Summary of Work

- **08:00 - 09:00**: Reviewed existing invoice and timecard modules for consistency.  
- **09:15 - 10:00**: Fixed database connection issues in `config.php` (resolved undefined `$db` and PDO DSN).  
- **10:15 - 11:00**: Updated `add_invoice.php` to use PDO transactions and included proper error handling.  
- **11:15 - 12:00**: Tested invoice submission with multiple items and tax calculations; ensured grand total updates correctly.  
- **12:00 - 12:30**: Added `invoices00` table references and adjusted related queries.  
- **13:00 - 14:00**: Implemented timecard week calculation and fetched user entries for current week.  
- **14:15 - 15:00**: Designed table output for timecards with proper formatting and day mapping.  
- **15:15 - 16:00**: Updated CSS styling for invoice and timecard forms for improved UI/UX.  
- **16:15 - 17:00**: Planned addition of new modules (`Payables`, `Reconcile`) and mapped schema requirements.

### Checklist of Features / Tasks

- [x] Configure PDO database connection correctly  
- [x] Fix undefined variables and database selection errors  
- [x] Update `add_invoice.php` with transaction-safe inserts  
- [x] Support multiple invoice items with tax calculations  
- [x] Display current week's timecards dynamically  
- [x] Improve CSS styling for forms and tables  
- [x] Error checking
- [x] Add **Invoice** module 
- [x] Add **Invoice** PDF and delete actions
- [x] Add **Payables** module 
- [x] Add **Payables** view and delete actions  
- [ ] Add **Reconcile** module  
- [x] Backup current database schema before major updates  

### Notes / Next Steps

- Implement Payables module CRUD operations  
- Implement Reconcile module with reporting features  
- Refactor common functions to improve maintainability (e.g., tax calculation, total computation)  
- Add input validation and error messages for all user-facing forms  
- Consider adding export to CSV or PDF for invoices  

---

*This document will be updated daily to reflect progress and pending tasks.*