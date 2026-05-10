# BiblioTech

![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=flat-square&logo=php&logoColor=white)
![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=flat-square&logo=laravel&logoColor=white)
![Livewire](https://img.shields.io/badge/Livewire-3-4E56A6?style=flat-square&logo=livewire&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0%2B-4479A1?style=flat-square&logo=mysql&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-4-38B2AC?style=flat-square&logo=tailwindcss&logoColor=white)
![Vite](https://img.shields.io/badge/Vite-7-646CFF?style=flat-square&logo=vite&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-green?style=flat-square)

<p align="center">
  <a href="https://skillicons.dev">
    <img src="https://skillicons.dev/icons?i=php,laravel,mysql,tailwind,vite,js,git&theme=light" alt="Technologies used in BiblioTech" />
  </a>
</p>

**BiblioTech** is a web application for library management. The system allows librarians to manage books, categories, users, loans, returns, and renewals from a centralized platform built with **Laravel**, **Blade**, **Livewire**, **Tailwind CSS**, and **MySQL**.

The project is designed to digitize common library operations by centralizing the book catalog, available copies, loan tracking, returns, and role-based access in a browser-accessible system.

> Note: The application interface is currently in Spanish, as the project is designed for library management in Spanish-speaking environments.

## Table of Contents

- [Technologies](#technologies)
- [Overview](#overview)
- [Main Features](#main-features)
- [System Roles](#system-roles)
- [Domain Model](#domain-model)
- [Prerequisites](#prerequisites)
- [Installation](#installation)
- [Configuration](#configuration)
- [Database](#database)
- [Running Locally](#running-locally)
- [Test Credentials](#test-credentials)
- [Useful Commands](#useful-commands)
- [Project Structure](#project-structure)
- [Technical Documentation](#technical-documentation)
- [Author](#author)
- [License](#license)

## Technologies

- **PHP 8.2+**
- **Laravel 12**
- **MySQL**
- **Blade**
- **Livewire**
- **Tailwind CSS**
- **Vite**

## Overview

BiblioTech was developed as a web solution to support the daily operation of a library. Its goal is to replace manual or scattered processes with an organized, traceable, and browser-accessible platform.

The application includes authentication, role-based access control, book management, category organization, loan registration, book returns, loan renewals, soft deletes for record traceability, and dashboards adapted to each type of user.

The system follows Laravel's **MVC** architecture, separating business logic into controllers, data persistence into Eloquent models, and presentation into Blade views.

## Main Features

### Authentication and authorization

- User registration and login.
- Session-based authentication with Laravel.
- Route protection for authenticated users.
- Role-based access control.
- Custom `CheckRole` middleware.
- User redirection according to role.
- Unauthorized access view.

### User management

- System user registration.
- Role assignment for different types of users.
- Optional phone field.
- Soft delete support for traceability.
- Helper methods to validate user roles.
- Relationship between users and loans.
- Loan history by user.

### Book management

- Book registration.
- Book information editing.
- Book detail view.
- Soft delete support.
- Restoration of deleted books.
- Unique ISBN validation.
- Management of title, author, publication year, category, available copies, and status.
- Book statuses:
  - Available.
  - Unavailable.
- Paginated book listings.
- Relationship between books and categories.
- Handling of deleted books in loan history.

### Category management

- Category model to classify books.
- Unique category names.
- Optional category description.
- Relationship between categories and books.
- Base structure to organize the library catalog.

### Loan management

- Book loan registration.
- Association between loan, user, and book.
- Availability validation before creating a loan.
- Automatic decrease of available copies when a book is loaned.
- Loan date registration.
- Due date registration.
- Loan statuses:
  - Active.
  - Returned.
  - Overdue.
- Book return registration.
- Automatic increase of available copies when a book is returned.
- Loan renewal for additional days.
- Handling of loans related to soft-deleted books.

### Librarian dashboard

- Personalized dashboard for librarians.
- Total registered books.
- Total loans.
- Active loan count.
- Overdue loan count.
- Available book count.
- Unavailable book count.
- Translated role and status labels for the Spanish interface.
- Quick access to library operation modules.

### Administrative panel

- Base route for the administrator dashboard.
- Base route for user management.
- Base route for system settings.
- Base route for reports.
- Role separation for administrator users.
- Foundation prepared for future administrative features.

### Member panel

- Base route for the member dashboard.
- Base route for personal loan consultation.
- Base route for future reservations.
- Role separation for member users.

### Security and validation

- CSRF protection in forms.
- Password hashing.
- SQL Injection prevention through Eloquent ORM.
- Automatic data escaping in Blade views.
- Authorization middleware by role.
- Required field validation.
- Unique ISBN validation.
- Publication year validation.
- User and book existence validation before creating loans.
- Action restrictions according to user role.

## System Roles

BiblioTech uses roles to separate responsibilities and permissions within the application.

| Role | Description |
| --- | --- |
| `admin` | Has access to administrative routes, settings, users, and reports. |
| `librarian` | Can manage books, loans, returns, renewals, and the operational dashboard. |
| `member` | Can access a personal dashboard, view personal loans, and use member-related features. |

## Domain Model

The system is organized around the main entities of a library management platform.

| Entity | Purpose |
| --- | --- |
| `User` | Represents system users, authentication data, role, and loan relationships. |
| `Book` | Represents books in the library catalog. |
| `Category` | Classifies books by topic, type, or area. |
| `Loan` | Represents the loan of a book to a user. |

### Main relationships

- A `User` may have many `Loan` records.
- A `Book` belongs to a `Category`.
- A `Category` may have many `Book` records.
- A `Book` may be associated with many `Loan` records.
- A `Loan` belongs to a `User`.
- A `Loan` belongs to a `Book`.

## Prerequisites

Before installing the project, make sure you have the following installed:

- PHP 8.2 or higher.
- Composer.
- MySQL 8.0 or higher.
- Node.js 18 or higher.
- npm.
- Git.

Recommended PHP extensions for running Laravel with MySQL:

```ini
extension=bcmath
extension=ctype
extension=curl
extension=fileinfo
extension=json
extension=mbstring
extension=openssl
extension=pdo_mysql
extension=tokenizer
extension=xml
extension=zip
```

## Installation

Clone the repository:

```bash
git clone https://github.com/CristoferGuillen/BiblioTech.git
```

Enter the project folder:

```bash
cd BiblioTech
```

Install PHP dependencies:

```bash
composer install
```

Install JavaScript dependencies:

```bash
npm install
```

Copy the environment file:

```bash
cp .env.example .env
```

On Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

Generate the application key:

```bash
php artisan key:generate
```

## Configuration

Edit the `.env` file and configure the main application values:

```env
APP_NAME=BiblioTech
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000
```

Configure the MySQL connection:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bibliotech
DB_USERNAME=root
DB_PASSWORD=your_password
```

Configure database sessions:

```env
SESSION_DRIVER=database
SESSION_LIFETIME=120
```

Create a database named `bibliotech` before running the migrations.

You can create it from MySQL with:

```sql
CREATE DATABASE bibliotech CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

## Database

Run the migrations:

```bash
php artisan migrate
```

Load the initial data:

```bash
php artisan db:seed
```

You can also reset the database and load seeders in a single command:

```bash
php artisan migrate:fresh --seed
```

> Warning: `migrate:fresh --seed` deletes the existing tables, runs all migrations again, and loads the seed data.

### Main tables

| Table | Description |
| --- | --- |
| `users` | System users, roles, credentials, and soft deletes. |
| `categories` | Categories used to classify books. |
| `books` | Library catalog with ISBN, author, year, copies, and status. |
| `loans` | Loan records, returns, dates, and statuses. |
| `sessions` | Laravel sessions stored in the database. |

## Running Locally

Run the complete development environment with:

```bash
composer run dev
```

You can also run Laravel and Vite separately.

Terminal 1:

```bash
php artisan serve
```

Terminal 2:

```bash
npm run dev
```

Then open the application at:

```text
http://localhost:8000
```

Main routes:

```text
http://localhost:8000
http://localhost:8000/login
http://localhost:8000/register
http://localhost:8000/dashboard
http://localhost:8000/librarian/dashboard
http://localhost:8000/admin/dashboard
http://localhost:8000/member/dashboard
```

## Test Credentials

The main seeder creates a base test user.

| Role | Email | Password |
| --- | --- | --- |
| Member | `test@example.com` | `password` |

> Note: The default role for new users is `member`. To test `admin` or `librarian` routes during development, update the `role` field manually in the database.

Example for administrator access:

```sql
UPDATE users SET role = 'admin' WHERE email = 'test@example.com';
```

Example for librarian access:

```sql
UPDATE users SET role = 'librarian' WHERE email = 'test@example.com';
```

## Useful Commands

Run migrations:

```bash
php artisan migrate
```

Run seeders:

```bash
php artisan db:seed
```

Recreate the database with seed data:

```bash
php artisan migrate:fresh --seed
```

Start the local server:

```bash
php artisan serve
```

Run Vite:

```bash
npm run dev
```

Build assets for production:

```bash
npm run build
```

Run the complete development environment:

```bash
composer run dev
```

Run tests:

```bash
php artisan test
```

Run the Composer test script:

```bash
composer test
```

Run Laravel Pint:

```bash
./vendor/bin/pint
```

## Project Structure

```text
BiblioTech/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   ├── BookController.php
│   │   │   ├── DashboardController.php
│   │   │   ├── LibrarianDashboardController.php
│   │   │   └── LoanController.php
│   │   └── Middleware/
│   │       └── CheckRole.php
│   │
│   ├── Livewire/
│   ├── Models/
│   │   ├── Book.php
│   │   ├── Category.php
│   │   ├── Loan.php
│   │   └── User.php
│   └── Providers/
│
├── database/
│   ├── factories/
│   ├── migrations/
│   │   ├── create_users_table.php
│   │   ├── create_cache_table.php
│   │   ├── create_jobs_table.php
│   │   ├── create_categories_table.php
│   │   ├── create_books_table.php
│   │   ├── create_loans_table.php
│   │   └── create_sessions_table.php
│   └── seeders/
│       └── DatabaseSeeder.php
│
├── public/
├── resources/
│   ├── css/
│   ├── js/
│   └── views/
│       ├── admin/
│       ├── books/
│       ├── components/
│       ├── librarian/
│       ├── livewire/
│       ├── loans/
│       └── member/
│
├── routes/
│   ├── console.php
│   └── web.php
│
├── storage/
├── tests/
├── .env.example
├── artisan
├── composer.json
├── package.json
├── phpunit.xml
├── DIAGRAMAS.md
├── TECHNICAL_DOCUMENTATION.md
└── vite.config.js
```

## Technical Documentation

The repository includes additional technical documentation in:

```text
TECHNICAL_DOCUMENTATION.md
DIAGRAMAS.md
```

These documents describe in greater detail:

- General project architecture.
- Domain model.
- Entity relationships.
- Main system workflows.
- Visual diagrams.
- Technical structure.
- Module organization.
- Security and authorization considerations.

## Author

Developed by **Cristofer Guillen**.

- GitHub: [@CristoferGuillen](https://github.com/CristoferGuillen)
- Repository: [BiblioTech](https://github.com/CristoferGuillen/BiblioTech)

## License

This project is available under the **MIT** license.
