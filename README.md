# Training Institute Management System

A Laravel application for managing students and courses. This project applies the Workshop 2 concepts of MVC, resource controllers and routes, route model binding, named routes, reusable Blade components, layouts, partials, and the DRY principle.

## Features

- Create, list, view, edit, and delete students and courses
- Validate submitted form data and show field-level validation errors
- Prevent duplicate student email addresses
- Use resource routes, named routes, and implicit route model binding
- Share a site layout, navigation bar, footer, alerts, form fields, and delete forms
- Share form markup between each resource's create and edit pages
- Display a welcome dashboard with record counts and recently added records
- Seed example student and course records
- Provide Back and Home navigation on application pages

## Requirements

- PHP 8.3 or later
- Composer
- SQLite (or a database configured in Laravel)

## Setup

From the project directory:

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
php artisan serve
```

Open the URL printed by `php artisan serve` (by default, <http://127.0.0.1:8000>).

The default `.env.example` uses SQLite. If you use another database, update the `DB_*` settings in `.env` and ensure that database exists before running migrations.

To add the sample records later, run:

```bash
php artisan db:seed
```

The seeder adds sample students and courses only when their respective tables are empty. Its example user is also created by the standard application seeder.

## Pages and routes

| Page | URL |
| --- | --- |
| Student list | `/students` |
| Create student | `/students/create` |
| Course list | `/courses` |
| Create course | `/courses/create` |
| Welcome dashboard | `/welcome` |

The root URL `/` redirects to the student list to preserve the existing application behavior. The shared navbar links to both resource lists. Back uses browser history; Home opens the welcome dashboard.

Resource routes are registered in `routes/web.php`:

```php
Route::resource('students', StudentController::class);
Route::resource('courses', CourseController::class);
```

Inspect all registered routes with:

```bash
php artisan route:list --except-vendor
```

## Project structure

```text
app/
  Http/Controllers/      StudentController and CourseController
  Models/                Student and Course
database/
  migrations/            Student, course, and Laravel support tables
  seeders/                Sample student and course records
public/css/app.css        Application styles
resources/views/
  components/             Layout, navigation, alerts, and form components
  course/                  Course list, form, create, edit, and detail views
  student/                 Student list, form, create, edit, and detail views
routes/web.php             Welcome and resource routes
```

## Testing

Run the automated test suite with:

```bash
php artisan test
```

## Workshop 2 concepts

- **Model:** Owns application data and persistence.
- **View:** Renders HTML using Blade templates and components.
- **Controller:** Handles resource requests, validation, and model operations.
- **Route:** Maps a URL and HTTP method to a controller action.
- **DRY:** Shared page chrome, inputs, delete forms, and create/edit fields live in reusable templates instead of being duplicated.
