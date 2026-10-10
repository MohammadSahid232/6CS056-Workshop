<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        if (Student::count() === 0) {
            Student::create([
                'name' => 'Alice Johnson',
                'email' => 'alice@example.com',
                'phone' => '555-0101',
                'address' => '12 Maple Street',
                'date_of_birth' => '2005-05-15',
            ]);

            Student::create([
                'name' => 'David Wilson',
                'email' => 'david@example.com',
                'phone' => '555-0102',
                'address' => '44 Oak Avenue',
                'date_of_birth' => '2006-09-22',
            ]);
        }

        if (Course::count() === 0) {
            Course::create([
                'name' => 'Web Development Fundamentals',
                'description' => 'Learn how to build modern web applications using HTML, CSS and PHP.',
                'duration' => 8,
                'fee' => 890.00,
                'difficulty' => 'Medium',
                'is_active' => true,
            ]);

            Course::create([
                'name' => 'Laravel MVC Mastery',
                'description' => 'Build full-stack applications with Laravel controllers, routes and Blade templates.',
                'duration' => 6,
                'fee' => 1250.00,
                'difficulty' => 'Hard',
                'is_active' => true,
            ]);
        }
    }
}
