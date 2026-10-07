<?php

namespace Tests\Feature;

use App\Models\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_courses_can_be_created_viewed_updated_and_deleted(): void
    {
        $courseData = [
            'name' => 'Full-Stack Web Development',
            'description' => 'Build web applications from end to end.',
            'duration' => 12,
            'fee' => '1250.00',
            'difficulty' => 'Medium',
            'is_active' => '1',
        ];

        $this->get(route('courses.create'))->assertOk();

        $this->post(route('courses.store'), $courseData)
            ->assertRedirect(route('courses.index'))
            ->assertSessionHas('success');

        $course = Course::query()->where('name', $courseData['name'])->firstOrFail();

        $this->assertDatabaseHas('courses', [
            'id' => $course->id,
            'duration' => 12,
            'fee' => '1250.00',
            'difficulty' => 'Medium',
            'is_active' => true,
        ]);

        $this->get(route('courses.index'))->assertOk()->assertSee($courseData['name']);
        $this->get(route('courses.show', $course))->assertOk()->assertSee($courseData['description']);
        $this->get(route('courses.edit', $course))->assertOk();

        $this->put(route('courses.update', $course), [
            ...$courseData,
            'name' => 'Advanced Full-Stack Web Development',
            'is_active' => '0',
        ])
            ->assertRedirect(route('courses.show', $course))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('courses', [
            'id' => $course->id,
            'name' => 'Advanced Full-Stack Web Development',
            'is_active' => false,
        ]);

        $this->delete(route('courses.destroy', $course))
            ->assertRedirect(route('courses.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('courses', ['id' => $course->id]);
    }

    public function test_course_input_is_validated(): void
    {
        $this->from(route('courses.create'))
            ->post(route('courses.store'), [
                'name' => '',
                'duration' => 0,
                'fee' => '-1.239',
                'difficulty' => 'Expert',
                'is_active' => 'unexpected',
            ])
            ->assertRedirect(route('courses.create'))
            ->assertSessionHasErrors(['name', 'duration', 'fee', 'difficulty', 'is_active']);
    }

    public function test_courses_can_be_searched_filtered_and_paginated(): void
    {
        foreach (range(1, 12) as $number) {
            Course::create([
                'name' => "Course {$number}",
                'description' => "Description for course {$number}",
                'duration' => 6,
                'fee' => '500.00',
                'difficulty' => $number === 12 ? 'Hard' : 'Easy',
                'is_active' => $number !== 11,
            ]);
        }

        $this->get(route('courses.index', [
            'q' => 'Course 12',
            'difficulty' => 'Hard',
            'status' => 'active',
        ]))
            ->assertOk()
            ->assertSee('Course 12')
            ->assertDontSee('Course 10</a>');

        $this->get(route('courses.index'))
            ->assertOk()
            ->assertSee('Course 12')
            ->assertDontSee('Course 1</a>');

        $this->get(route('courses.index', ['page' => 2]))
            ->assertOk()
            ->assertSee('Course 1');
    }
}
