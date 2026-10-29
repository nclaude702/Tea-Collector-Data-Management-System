# Tea Collector Data Management System (TCDMS)

A complete web-based system for managing tea collection activities at SORWATHE Ltd.

## Requirements
- XAMPP (PHP 8.2+, MySQL 8.0, Apache)
- Modern browser (Chrome, Firefox, Edge)

## Installation
1. Install XAMPP and start Apache + MySQL.
2. Copy this folder to `C:\xampp\htdocs\tea-collector-system\`
3. Open http://localhost/phpmyadmin
4. Import `database/tea_collector.sql`
5. Visit http://localhost/tea-collector-system/

## Default Login
- Username: `admin`
- Password: `admin123`

## Folder Structure
- `config/` — Database, constants, session
- `includes/` — Shared layout, helpers
- `modules/` — Features (farmers, collections, payments, etc.)
- `api/` — AJAX endpoints
- `assets/` — CSS, JS, images
- `database/` — SQL schema

## Security Features
- Bcrypt password hashing
- PDO prepared statements (SQL injection protection)
- Session timeout (30 min)
- Role-based access control
- Audit logging

## Support
For issues, contact the system administrator.