# Training Institute Manager — Workshop 1

A Laravel application for maintaining student and course records. This workshop implements the foundation described in the Week 1 handout: database-backed CRUD, validation, Blade forms, and HTTP routes. Students and courses are intentionally independent resources in this workshop; their many-to-many relationship, no-reload/AJAX updates, authentication, REST API, and device API features are deferred to later workshops.

## Requirements

- PHP 8.3 or later
- Composer
- MySQL 8 or a compatible MySQL server for the handout's setup

The app can also run against SQLite for local smoke tests. Feature tests use an in-memory SQLite database.

## Set up the project

From the project root:

```sh
composer install
cp .env.example .env
php artisan key:generate
```

Create the MySQL database (the handout calls it `WorkshopDB`):

```sql
CREATE DATABASE WorkshopDB CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Update the database values in `.env` to match your local MySQL installation:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=WorkshopDB
DB_USERNAME=root
DB_PASSWORD=
```

Use your own database username and password if they differ. Then run the migrations and start Laravel:

```sh
php artisan config:clear
php artisan migrate
php artisan serve
```

Open [http://127.0.0.1:8000](http://127.0.0.1:8000). The root address redirects to the students list. The courses list is available at `/courses`.

For SQLite instead, set `DB_CONNECTION=sqlite` in `.env` and ensure `database/database.sqlite` exists before running `php artisan migrate`.

## Features

- Student CRUD: name, unique email, phone, address, and date of birth
- Course CRUD: name, description, duration in weeks, decimal fee, difficulty, and active status
- Server-side form validation, validation feedback, CSRF protection, and safe update handling for unique student emails
- Server-submitted search and pagination for students; server-submitted search, difficulty/status filters, and pagination for courses
- Responsive Blade pages with create, list, detail, edit, and delete flows

## Tests

```sh
php artisan test
```
