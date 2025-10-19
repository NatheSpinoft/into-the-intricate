# Project Daily Log

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
- [x] Display current week’s timecards dynamically  
- [x] Improve CSS styling for forms and tables  
- [ ] Add **Payables** module  
- [ ] Add **Reconcile** module  
- [ ] Test invoice and timecard end-to-end workflow  
- [ ] Backup current database schema before major updates  

### Notes / Next Steps

- Implement Payables module CRUD operations  
- Implement Reconcile module with reporting features  
- Refactor common functions to improve maintainability (e.g., tax calculation, total computation)  
- Add input validation and error messages for all user-facing forms  
- Consider adding export to CSV or PDF for invoices  

---

*This document will be updated daily to reflect progress and pending tasks.*
