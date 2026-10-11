# 📦 Inventory Management System

A full-featured, responsive web-based **Inventory Management and Stock Tracking System** built with **PHP (OOP)**, **MySQL (MariaDB)**, **Vanilla JavaScript (Fetch API)**, and **Bootstrap 5**.

The system enables businesses to monitor stock levels, manage product records, track suppliers, evaluate inventory valuation, generate monthly summary reports, and safeguard operations through **Role-Based Access Control (Admin vs. Employee)**.

---

## 📑 Table of Contents

- [Overview](#-overview)
- [Key Features](#-key-features)
- [System Architecture & Tech Stack](#-system-architecture--tech-stack)
- [Directory Structure](#-directory-structure)
- [System Prerequisites](#-system-prerequisites)
- [Step-by-Step Setup Guide](#-step-by-step-setup-guide)
  - [1. Place Files in Web Root](#1-place-files-in-web-root)
  - [2. Start Apache and MySQL](#2-start-apache-and-mysql)
  - [3. Create and Import the Database](#3-create-and-import-the-database)
  - [4. Configure Database Connection](#4-configure-database-connection)
  - [5. Run the Application](#5-run-the-application)
- [Default Login Credentials](#-default-login-credentials)
- [API Endpoints Reference](#-api-endpoints-reference)
- [Troubleshooting & Common Issues](#-troubleshooting--common-issues)
- [License & Contribution](#-license--contribution)

---

## 🌟 Overview

This application serves as an end-to-end inventory tracking portal tailored for local server environments (such as **XAMPP** or **Laragon**). It combines an asynchronous single-page interface for day-to-day stock queries with a dedicated admin analytics dashboard and employee account manager.

### Key Highlights:
- **Zero Frontend Framework Dependencies**: Pure Vanilla JavaScript with asynchronous `fetch()` calls for fast, seamless UI updates without full page reloads.
- **Object-Oriented PHP Architecture**: Clean separation of business logic (`classes/`), API routing (`api/`), session security (`config/auth.php`), and data presentation.
- **Enterprise-Grade DB Design**: Relational integrity with foreign key constraints, indexes, and optimized SQL views (`vw_inventory_summary`, `vw_low_stock_products`).

---

## 🚀 Key Features

### 1. 📋 Product Inventory Management (CRUD)
- **Live Inventory Listing**: View all inventory products with category, supplier, stock quantity, threshold, status, and unit price.
- **Add & Edit Products**: Modal-driven creation and update workflows with dynamic category/supplier creation.
- **Delete Products**: Admin-protected safe deletion with confirmation alerts.
- **Status Badges**: Real-time visual flags for **In Stock**, **Low Stock**, and **Out of Stock**.

### 2. 📊 Admin Analytics Dashboard (`admin.php`)
- **KPI Metrics**: Overview cards for Total Products, Total Categories, Total Suppliers, Low Stock items, Out of Stock items, and Total Inventory Valuation.
- **Category Breakdown**: Stock and valuation aggregates grouped per product category.
- **Supplier Performance**: Detailed breakdown of suppliers with active product counts, total stock supplied, and valuation.
- **Restock Recommendations**: Automated calculation of suggested reorder units based on low stock thresholds:
  $$\text{Suggested Reorder} = \max(2 \times \text{Threshold} - \text{Current Stock}, 0)$$

### 3. 📅 Monthly Inventory Reporting
- Filter product additions by specific month and year.
- Aggregate summary metrics for monthly additions and capital value.
- Clean printable format directly from the application modal.

### 4. 👥 Role-Based Access Control (RBAC)
- **Session Authentication**: Secure user sessions with Bcrypt (`PASSWORD_DEFAULT`) password hashing.
- **Role Permissions**:
  | Feature / Action | Admin | Employee |
  | :--- | :---: | :---: |
  | View Inventory List & Badges | ✅ | ✅ |
  | Generate Monthly Report | ✅ | ✅ |
  | Add / Update / Delete Products | ✅ | ❌ |
  | Access Admin Dashboard | ✅ | ❌ |
  | Manage Employee Accounts | ✅ | ❌ |

---

## 🛠 System Architecture & Tech Stack

- **Backend**: PHP 8.x (Object-Oriented, MySQLi with Prepared Statements)
- **Database**: MySQL 5.7+ / MariaDB 10.4+
- **Frontend**:
  - HTML5 & CSS3 (Custom styling with modern typography via *Source Serif 4*)
  - Bootstrap 5.3.3 (Responsive Grid & Modals)
  - Vanilla JavaScript (Async/Await, Fetch API)
- **Web Server**: Apache (via XAMPP, Laragon, WAMP, or standalone Apache)

---

## 📂 Directory Structure

```text
Inventory_XAMPP/
│
├── README.md                                  # Setup guide and documentation
└── Inventory_management_system/
    ├── index.php                              # Main inventory table & employee/admin view
    ├── login.php                              # Authentication page
    ├── logout.php                             # Session termination script
    ├── admin.php                              # Admin analytics dashboard
    ├── manage_users.php                       # Admin employee management portal
    ├── script.js                              # Main app frontend logic (CRUD, report, badges)
    ├── admin.js                               # Admin dashboard data fetching and rendering
    │
    ├── api/                                   # REST-like asynchronous backend endpoints
    │   ├── login.php                          # User login verification
    │   ├── read.php                           # Fetch inventory list
    │   ├── create.php                         # Add new product (Admin only)
    │   ├── update.php                         # Modify product (Admin only)
    │   ├── delete.php                         # Remove product (Admin only)
    │   ├── dashboard.php                      # Admin analytics summary (Admin only)
    │   ├── monthly_report.php                 # Query products by month/year
    │   ├── get_employees.php                  # List employees (Admin only)
    │   ├── add_employee.php                   # Create employee account (Admin only)
    │   └── delete_employee.php                # Delete employee account (Admin only)
    │
    ├── classes/                               # OOP business logic classes
    │   ├── Product.php                        # Product CRUD, category/supplier link, metrics
    │   ├── Supplier.php                       # Supplier stats and aggregation
    │   └── User.php                           # User verification and employee management
    │
    ├── config/                                # Configuration and database files
    │   ├── auth.php                           # Session helpers and access control gates
    │   ├── database.sql                       # Complete MySQL schema and initial seed data
    │   └── db.php                             # MySQLi database connection class
    │
    └── style/                                 # Custom CSS stylesheets
        ├── admin.css                          # Admin dashboard styles
        ├── index.css                          # Main table styling & color palette
        └── login.css                          # Login page styling
```

---

## 💻 System Prerequisites

Before getting started, make sure your development machine has:
1. **XAMPP** (recommended) or **Laragon** with:
   - **PHP 7.4 or PHP 8.x+**
   - **MySQL 5.7+** or **MariaDB 10.4+**
   - **Apache 2.4+**
2. A modern web browser (Google Chrome, Microsoft Edge, Mozilla Firefox, Brave, Safari).
3. **Git** (optional, for cloning the repo).

---

## ⚙️ Step-by-Step Setup Guide

Follow these steps to replicate the system on your computer:

### 1. Place Files in Web Root

Move or clone the project directory into your local web server's public document root directory:

- **For XAMPP on Windows**:
  ```text
  C:\xampp\htdocs\Inventory_XAMPP
  ```
- **For Laragon on Windows**:
  ```text
  C:\laragon\www\Inventory_XAMPP
  ```
- **For Linux (LAMP)**:
  ```text
  /var/www/html/Inventory_XAMPP
  ```

> 💡 **Tip:** You can either keep the folder name `Inventory_XAMPP` or rename it as needed. Ensure the inner folder `Inventory_management_system` remains intact.

---

### 2. Start Apache and MySQL

1. Open your **XAMPP Control Panel** (or Laragon).
2. Start the **Apache** service.
3. Start the **MySQL** service.
4. Verify that both services display a green indicator status.

---

### 3. Create and Import the Database

1. Open your browser and navigate to **phpMyAdmin**:
   ```
   http://localhost/phpmyadmin/
   ```
2. Click **New** on the left sidebar to create a new database.
3. Set the database name to:
   ```text
   inventory_db
   ```
   *(Select `utf8mb4_general_ci` as collation, then click **Create**).*
4. Click on the newly created `inventory_db` database in the left sidebar.
5. Click the **Import** tab in the top navigation bar.
6. Click **Choose File** and select the SQL dump file located at:
   ```text
   Inventory_management_system/config/database.sql
   ```
7. Click **Import** (or **Go**) at the bottom of the page to execute the script.
   
*(Alternatively, you can open `Inventory_management_system/config/database.sql`, copy all text, paste it into the **SQL** tab in phpMyAdmin, and click **Go**).*

This creates:
- `categories` table with default data.
- `suppliers` table with default contact records.
- `products` table with sample product catalog.
- `users` table with the pre-seeded admin account.
- `vw_inventory_summary` and `vw_low_stock_products` database views.

---

### 4. Configure Database Connection

Open `Inventory_management_system/config/db.php` in your code editor:

```php
class Database {
    private $host = "localhost";
    private $port = "3306";         // <-- Check your MySQL port here
    private $user = "root";         // <-- Default MySQL username in XAMPP
    private $password = "";         // <-- Default password is empty in XAMPP
    private $database = "inventory_db";
    ...
```

> ⚠️ **Important Port Check:**
> - Standard XAMPP installs run MySQL on port **`3306`**.
> - If your MySQL is running on a custom port (such as `3307`), update `$port = "3307";` accordingly.
> - If you set a root password in MySQL/MariaDB, set `$password = "your_password";`.

---

### 5. Run the Application

Open your browser and navigate to:

```
http://localhost/Inventory_XAMPP/Inventory_management_system/
```

*(If you moved the contents of `Inventory_management_system` directly to your root, access `http://localhost/`).*

You will be automatically redirected to the **Login** screen (`login.php`).

---

## 🔑 Default Login Credentials

Use the default administrator account to sign in:

| Role | Username | Password | Access Level |
| :--- | :--- | :--- | :--- |
| **Administrator** | `admin` | `admin123` | Full access (CRUD, Admin Dashboard, User Management) |

### Creating Employee Accounts:
1. Log in with the **admin** account.
2. Click **Manage Employees** in the top navigation bar (or navigate to `manage_users.php`).
3. Click **Add Employee**.
4. Enter an employee username and password (minimum 6 characters).
5. Log out and log in with the new employee credentials to test the restricted staff interface.

---

## 📡 API Endpoints Reference

All endpoints return JSON responses in the format: `{ "status": "success"|"error", "message": "..." }`.

| Endpoint | Method | Role Required | Description |
| :--- | :---: | :---: | :--- |
| `api/login.php` | `POST` | Public | Validates user credentials and initializes session. |
| `api/read.php` | `GET` | Authenticated | Retrieves the full list of products with category and supplier info. |
| `api/create.php` | `POST` | Admin | Inserts a new product into the database. |
| `api/update.php` | `POST` | Admin | Updates an existing product record. |
| `api/delete.php` | `POST` | Admin | Deletes a product by `id`. |
| `api/dashboard.php` | `GET` | Admin | Supplies KPI metrics, category breakdown, supplier stats, and restock list. |
| `api/monthly_report.php` | `GET` | Authenticated | Filters added products by `month` and `year` query parameters. |
| `api/get_employees.php` | `GET` | Admin | Retrieves all active employee accounts. |
| `api/add_employee.php` | `POST` | Admin | Registers a new employee account. |
| `api/delete_employee.php` | `POST` | Admin | Removes an employee account by `id`. |

---

## ❓ Troubleshooting & Common Issues

### 1. `Database Connection Failed: Connection refused` or `Error 2003 / 10061`
- **Cause**: MySQL service is not running or the port in `config/db.php` does not match your MySQL server port.
- **Fix**:
  1. Ensure MySQL is started in XAMPP.
  2. Check the port shown under the MySQL row in XAMPP (usually `3306`).
  3. Verify that `private $port` in [db.php](file:///c:/laragon/www/INTECH%203112%20LAB/Inventory_XAMPP/Inventory_management_system/config/db.php) matches that exact port.

### 2. `Unknown database 'inventory_db'`
- **Cause**: The database has not been created yet in phpMyAdmin.
- **Fix**: Open phpMyAdmin (`http://localhost/phpmyadmin/`), click **New**, create a database named `inventory_db`, and import `database.sql`.

### 3. `Table 'inventory_db.users' doesn't exist` or Login Fails Immediately
- **Cause**: Incomplete SQL schema import.
- **Fix**: Re-import [database.sql](file:///c:/laragon/www/INTECH%203112%20LAB/Inventory_XAMPP/Inventory_management_system/config/database.sql) which contains the complete table definition and default admin seed data.

### 4. Redirect Loop or Session Lost
- **Cause**: Browser cookie blockage or PHP session directory write permission error.
- **Fix**: Verify cookies are enabled in your browser and check `session.save_path` in your `php.ini`.

---

## 👥 Collaborators & Contributions

1. Fork or clone this repository.
2. Create a feature branch (`git checkout -b feature/NewFeature`).
3. Commit your changes (`git commit -m 'Add NewFeature'`).
4. Push to your branch (`git push origin feature/NewFeature`).
5. Open a Pull Request for review.
