## Customer Service Assets

## Overview

## Table creation
-- =====================================
-- USERS TABLE
-- =====================================
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,        -- store hashed passwords
    category ENUM('employee','customer') NOT NULL,
    email VARCHAR(100),
    phone VARCHAR(20)
);

-- =====================================
-- PRINTERS TABLE
-- =====================================
CREATE TABLE printers (
    printer_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,                  -- links to users
    serial_number VARCHAR(50) NOT NULL UNIQUE,
    description VARCHAR(255),
    location VARCHAR(100),
    purchase_date DATE,
    warranty_expiry DATE,
    status ENUM('active','maintenance','retired') DEFAULT 'active',
    last_service_date DATE,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
        ON DELETE CASCADE
);

-- =====================================
-- PRINTER LOGS TABLE (Optional)
-- Tracks usage or maintenance logs
-- =====================================
CREATE TABLE printer_logs (
    log_id INT AUTO_INCREMENT PRIMARY KEY,
    printer_id INT NOT NULL,
    log_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    pages_printed INT DEFAULT 0,
    issues_reported VARCHAR(255),
    FOREIGN KEY (printer_id) REFERENCES printers(printer_id)
        ON DELETE CASCADE
);

-- =====================================
-- PRINTER SUPPORT TICKETS TABLE (Optional)
-- Tracks issues reported for printers
-- =====================================
CREATE TABLE printer_support (
    ticket_id INT AUTO_INCREMENT PRIMARY KEY,
    printer_id INT NOT NULL,
    user_id INT NOT NULL,
    issue_description TEXT NOT NULL,
    status ENUM('open','in_progress','resolved','closed') DEFAULT 'open',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    resolved_at DATETIME,
    FOREIGN KEY (printer_id) REFERENCES printers(printer_id)
        ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
        ON DELETE CASCADE
);
## Checklist

[x] Users table created with fields: user_id, username, password, category, email, phone
[x]Printers table created with fields: printer_id, user_id, serial_number, description, location, purchase_date, warranty_expiry, status, last_service_date
[x]Foreign key from printers to users added with cascade delete
[x]Printer logs table created (optional) with fields: log_id, printer_id, log_date, pages_printed, issues_reported
[x]Printer support tickets table created (optional) with fields: ticket_id, printer_id, user_id, issue_description, status, created_at, resolved_at
[x]Foreign keys in logs and support tables set with cascade delete
[x]ENUM fields used for category and status to maintain consistent values