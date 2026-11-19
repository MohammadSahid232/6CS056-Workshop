<?php

namespace Tests\Feature;

use App\Models\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_course_index_returns_courses_as_json(): void
    {
        Course::query()->create([
            'name' => 'Laravel MVC Mastery',
            'description' => 'Build Laravel applications.',
            'duration' => 6,
            'fee' => 1250,
            'difficulty' => 'Hard',
            'is_active' => true,
        ]);

        $this->getJson('/api/courses')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'Laravel MVC Mastery');
    }

    public function test_valid_payload_creates_course_and_returns_201(): void
    {
        $response = $this->postJson('/api/courses', [
            'name' => 'Web Development Fundamentals',
            'description' => 'Learn modern web development.',
            'duration' => 8,
            'fee' => 890,
            'difficulty' => 'Medium',
            'is_active' => true,
        ]);

        $response
            ->assertCreated()
            ->assertJsonPath('data.name', 'Web Development Fundamentals');

        $this->assertDatabaseHas('courses', [
            'name' => 'Web Development Fundamentals',
            'duration' => 8,
        ]);
    }

    public function test_invalid_course_payload_returns_422(): void
    {
        $this->postJson('/api/courses', [
            'name' => '',
            'duration' => 0,
            'fee' => -1,
            'difficulty' => 'Impossible',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'duration', 'fee', 'difficulty']);

        $this->assertDatabaseCount('courses', 0);
    }

    public function test_course_show_update_and_delete_operations_return_expected_statuses(): void
    {
        $course = Course::query()->create([
            'name' => 'Laravel MVC Mastery',
            'description' => 'Build Laravel applications.',
            'duration' => 6,
            'fee' => 1250,
            'difficulty' => 'Hard',
            'is_active' => true,
        ]);

        $this->getJson("/api/courses/{$course->id}")
            ->assertOk()
            ->assertJsonPath('data.name', 'Laravel MVC Mastery');

        $this->putJson("/api/courses/{$course->id}", [
            'name' => 'Advanced Laravel',
            'description' => 'Build advanced Laravel applications.',
            'duration' => 8,
            'fee' => 1500,
            'difficulty' => 'Hard',
            'is_active' => true,
        ])
            ->assertOk()
            ->assertJsonPath('data.name', 'Advanced Laravel');

        $this->assertDatabaseHas('courses', [
            'id' => $course->id,
            'name' => 'Advanced Laravel',
            'fee' => 1500,
        ]);

        $this->deleteJson("/api/courses/{$course->id}")
            ->assertNoContent();

        $this->assertDatabaseMissing('courses', ['id' => $course->id]);
    }

    public function test_missing_course_returns_404(): void
    {
        $this->getJson('/api/courses/999999')->assertNotFound();
    }
}
