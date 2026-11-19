<?php

namespace Tests\Feature;

use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_index_returns_students_as_json(): void
    {
        Student::query()->create([
            'name' => 'Alex Student',
            'email' => 'alex@example.com',
            'phone' => '555-0100',
            'address' => '123 Main Street',
            'date_of_birth' => '2005-04-15',
        ]);

        $this->getJson('/api/students')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'Alex Student')
            ->assertJsonPath('data.0.email', 'alex@example.com');
    }

    public function test_valid_payload_creates_student_and_returns_201(): void
    {
        $response = $this->postJson('/api/students', [
            'name' => 'Jordan Lee',
            'email' => 'jordan@example.com',
            'phone' => '555-0101',
            'address' => '456 Oak Avenue',
            'date_of_birth' => '2004-03-12',
        ]);

        $response
            ->assertCreated()
            ->assertJsonPath('data.name', 'Jordan Lee')
            ->assertJsonPath('data.email', 'jordan@example.com');

        $this->assertDatabaseHas('students', [
            'email' => 'jordan@example.com',
            'phone' => '555-0101',
        ]);
    }

    public function test_duplicate_email_returns_422_and_does_not_create_student(): void
    {
        Student::query()->create([
            'name' => 'Existing Student',
            'email' => 'duplicate@example.com',
            'phone' => '555-0100',
            'address' => '123 Main Street',
            'date_of_birth' => '2005-04-15',
        ]);

        $this->postJson('/api/students', [
            'name' => 'Duplicate Student',
            'email' => 'duplicate@example.com',
            'phone' => '555-0101',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('email');

        $this->assertDatabaseCount('students', 1);
    }

    public function test_student_show_update_and_delete_operations_return_expected_statuses(): void
    {
        $student = Student::query()->create([
            'name' => 'Jordan Lee',
            'email' => 'jordan@example.com',
            'phone' => '555-0101',
            'address' => '456 Oak Avenue',
            'date_of_birth' => '2004-03-12',
        ]);

        $this->getJson("/api/students/{$student->id}")
            ->assertOk()
            ->assertJsonPath('data.email', 'jordan@example.com');

        $this->putJson("/api/students/{$student->id}", [
            'name' => 'Jordan Kim',
            'email' => 'jordan.kim@example.com',
            'phone' => '555-0102',
            'address' => '789 Pine Road',
            'date_of_birth' => '2004-04-20',
        ])
            ->assertOk()
            ->assertJsonPath('data.name', 'Jordan Kim');

        $this->assertDatabaseHas('students', [
            'id' => $student->id,
            'email' => 'jordan.kim@example.com',
        ]);

        $this->deleteJson("/api/students/{$student->id}")
            ->assertNoContent();

        $this->assertDatabaseMissing('students', ['id' => $student->id]);
    }

    public function test_missing_student_returns_404(): void
    {
        $this->getJson('/api/students/999999')->assertNotFound();
    }
}
