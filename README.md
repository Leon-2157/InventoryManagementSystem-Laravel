# Inventory Management System

A web-based inventory and stock management application built with Laravel 13, Eloquent ORM, and Bootstrap 5.

## Overview

Inventory Management System facilitates end-to-end tracking of items, suppliers, categories, and stock movements. Designed for retail and warehouse operations, the platform enforces strict business rules including concurrency-safe stock decrement, non-negative inventory levels, and role-based operational permissions.

## Key Features

- **Product & Category Catalog**: Full catalog management with search, category filtering, column sorting, and paginated listing.
- **Supplier & Supply Relationship**: Manage supplier directories and associate multiple suppliers per product with customized supply pricing.
- **Stock Movement Ledger**: Concurrency-protected transaction processing for Stock In and Stock Out operations with complete audit history.
- **Low Stock Monitoring**: Automated visual alerts and indicators for products reaching or falling below safety threshold levels.
- **Role-Based Access Control**: Separate privileges for Administrator (full system management, user management) and Staff (inventory viewing and stock recording).

## Prerequisites

- PHP >= 8.3 with PDO, OpenSSL, and Mbstring extensions
- Composer >= 2.0
- MySQL >= 8.0 or MariaDB >= 10.5

## Quick Start

1. **Clone and Install Dependencies**
   ```bash
   composer install
   ```

2. **Configure Environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   Set your database credentials inside `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=inventory
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

3. **Migrate and Seed Database**
   ```bash
   php artisan migrate:fresh --seed
   ```

4. **Run the Application**
   - **Option A (PHP Built-in Server)**:
     ```bash
     php artisan serve
     ```
     Access the system at `http://127.0.0.1:8000`.

   - **Option B (Docker Compose / Podman)**:
     ```bash
     docker compose up -d
     # or using podman:
     podman compose up -d
     ```
     Access the system at `http://localhost:8080`.

## Default Credentials

| Role | Username | Password |
|---|---|---|
| Administrator | `admin` | `admin123` |
| Staff | `staff` | `staff123` |

## Running Tests

Execute PHPUnit feature test suite:
```bash
php artisan test
```
