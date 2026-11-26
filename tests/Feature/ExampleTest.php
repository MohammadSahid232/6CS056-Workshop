<?php

namespace Tests\Feature;

use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_home_page_is_publicly_accessible(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Training Institute Management System')
            ->assertSee('Login')
            ->assertSee('Register');
    }

    public function test_student_index_and_create_pages_render_their_headings(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('students.index'))
            ->assertOk()
            ->assertSee('<h1>Student List</h1>', false)
            ->assertSee('No students found.');

        $this->actingAs($user)
            ->get(route('students.create'))
            ->assertOk()
            ->assertSee('<h1>Create Student</h1>', false);
    }

    public function test_student_show_and_edit_pages_render_their_headings(): void
    {
        $user = User::factory()->create();
        $student = Student::query()->create([
            'name' => 'Alex Student',
            'email' => 'alex@example.com',
            'phone' => '555-0100',
            'address' => '123 Main Street',
            'date_of_birth' => '2005-04-15',
        ]);

        $this->actingAs($user)
            ->get(route('students.show', $student))
            ->assertOk()
            ->assertSee('<h1>Student Details</h1>', false)
            ->assertSee('Alex Student')
            ->assertSee('alex@example.com');

        $this->actingAs($user)
            ->get(route('students.edit', $student))
            ->assertOk()
            ->assertSee('<h1>Edit Student</h1>', false)
            ->assertSee('value="Alex Student"', false);
    }

    public function test_student_can_be_created(): void
    {
        $user = User::factory()->create();
        $studentData = [
            'name' => 'Jordan Lee',
            'email' => 'jordan@example.com',
            'phone' => '555-0101',
            'address' => '456 Oak Avenue',
            'date_of_birth' => '2004-03-12',
        ];

        $this->actingAs($user)
            ->post(route('students.store'), $studentData)
            ->assertRedirect();

        $this->assertDatabaseHas('students', [
            ...$studentData,
            'date_of_birth' => '2004-03-12 00:00:00',
        ]);
    }

    public function test_student_creation_requires_valid_student_details(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->from(route('students.create'))
            ->post(route('students.store'), [
                'name' => '',
                'email' => 'not-an-email',
                'phone' => '',
                'address' => '',
                'date_of_birth' => '',
            ])
            ->assertRedirect(route('students.create'))
            ->assertSessionHasErrors([
                'name',
                'email',
                'phone',
                'address',
                'date_of_birth',
            ]);

        $this->assertDatabaseCount('students', 0);
    }

    public function test_student_can_be_updated(): void
    {
        $user = User::factory()->create();
        $student = Student::query()->create([
            'name' => 'Jordan Lee',
            'email' => 'jordan@example.com',
            'phone' => '555-0101',
            'address' => '456 Oak Avenue',
            'date_of_birth' => '2004-03-12',
        ]);
        $updatedData = [
            'name' => 'Jordan Kim',
            'email' => 'jordan.kim@example.com',
            'phone' => '555-0102',
            'address' => '789 Pine Road',
            'date_of_birth' => '2004-04-20',
        ];

        $this->actingAs($user)
            ->put(route('students.update', $student), $updatedData)
            ->assertRedirect(route('students.show', $student));

        $this->assertDatabaseHas('students', [
            'id' => $student->id,
            ...$updatedData,
            'date_of_birth' => '2004-04-20 00:00:00',
        ]);
    }

    public function test_student_can_be_deleted(): void
    {
        $user = User::factory()->create();
        $student = Student::query()->create([
            'name' => 'Jordan Lee',
            'email' => 'jordan@example.com',
            'phone' => '555-0101',
            'address' => '456 Oak Avenue',
            'date_of_birth' => '2004-03-12',
        ]);

        $this->actingAs($user)
            ->delete(route('students.destroy', $student))
            ->assertRedirect(route('students.index'));

        $this->assertDatabaseMissing('students', ['id' => $student->id]);
    }
}
