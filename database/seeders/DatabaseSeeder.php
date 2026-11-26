<?php

namespace Database\Seeders;

use App\Models\Student;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
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
    }
}
