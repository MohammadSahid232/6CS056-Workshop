<?php

namespace Tests\Feature;

use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_students_can_be_created_viewed_updated_and_deleted(): void
    {
        $studentData = [
            'name' => 'Amina Rahman',
            'email' => 'amina@example.com',
            'phone' => '+60123456789',
            'address' => 'Kuala Lumpur',
            'date_of_birth' => '2002-02-12',
        ];

        $this->get(route('students.create'))->assertOk();

        $this->post(route('students.store'), $studentData)
            ->assertRedirect(route('students.index'))
            ->assertSessionHas('success');

        $student = Student::query()->where('email', $studentData['email'])->firstOrFail();

        $this->assertDatabaseHas('students', [
            'id' => $student->id,
            'name' => 'Amina Rahman',
        ]);
        $this->assertSame('2002-02-12', $student->fresh()->date_of_birth->toDateString());

        $this->get(route('students.index'))->assertOk()->assertSee('Amina Rahman');
        $this->get(route('students.show', $student))->assertOk()->assertSee('Kuala Lumpur');
        $this->get(route('students.edit', $student))->assertOk();

        $this->put(route('students.update', $student), [
            ...$studentData,
            'name' => 'Amina R.',
        ])
            ->assertRedirect(route('students.show', $student))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('students', [
            'id' => $student->id,
            'name' => 'Amina R.',
            'email' => $studentData['email'],
        ]);

        $this->delete(route('students.destroy', $student))
            ->assertRedirect(route('students.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('students', ['id' => $student->id]);
    }

    public function test_student_input_is_validated_and_email_must_be_unique(): void
    {
        Student::create([
            'name' => 'Existing Student',
            'email' => 'existing@example.com',
            'phone' => '555-0100',
        ]);

        $this->from(route('students.create'))
            ->post(route('students.store'), [
                'name' => '',
                'email' => 'not-an-email',
                'phone' => '',
                'date_of_birth' => 'not-a-date',
            ])
            ->assertRedirect(route('students.create'))
            ->assertSessionHasErrors(['name', 'email', 'phone', 'date_of_birth']);

        $this->from(route('students.create'))
            ->post(route('students.store'), [
                'name' => 'Another Student',
                'email' => 'existing@example.com',
                'phone' => '555-0101',
            ])
            ->assertRedirect(route('students.create'))
            ->assertSessionHasErrors('email');
    }

    public function test_student_search_and_pagination_work(): void
    {
        foreach (range(1, 12) as $number) {
            Student::create([
                'name' => "Student {$number}",
                'email' => "student{$number}@example.com",
                'phone' => "555-{$number}",
            ]);
        }

        $this->get(route('students.index'))
            ->assertOk()
            ->assertSee('Student 12')
            ->assertDontSee('Student 1</a>');

        $this->get(route('students.index', ['page' => 2]))
            ->assertOk()
            ->assertSee('Student 1');

        $this->get(route('students.index', ['q' => 'student5']))
            ->assertOk()
            ->assertSee('Student 5')
            ->assertDontSee('Student 4</a>');
    }
}
