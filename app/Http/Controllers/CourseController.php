<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function index(Request $request): View
    {
        $courses = $request->user()->role === 'admin'
            ? Course::with('user')->latest()->get()
            : $request->user()->courses()->with('user')->latest()->get();

        return view('course.list', ['courses' => $courses]);
    }

    public function create(): View
    {
        return view('course.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules());
        $course = $request->user()->courses()->create($validated);

        return redirect()
            ->route('courses.index')
            ->with('success', "Course {$course->name} created successfully!");
    }

    public function show(Course $course): View
    {
        Gate::authorize('manage-course', $course);

        return view('course.detail', ['course' => $course]);
    }

    public function edit(Course $course): View
    {
        Gate::authorize('manage-course', $course);

        return view('course.edit', ['course' => $course]);
    }

    public function update(Request $request, Course $course): RedirectResponse
    {
        Gate::authorize('manage-course', $course);

        $course->update($request->validate($this->rules()));

        return redirect()
            ->route('courses.show', $course)
            ->with('success', 'Course updated successfully!');
    }

    public function destroy(Course $course): RedirectResponse
    {
        Gate::authorize('manage-course', $course);

        $course->delete();

        return redirect()
            ->route('courses.index')
            ->with('success', 'Course deleted successfully!');
    }

    private function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'duration' => ['required', 'integer', 'min:1'],
            'fee' => ['required', 'numeric', 'min:0'],
            'difficulty' => ['required', Rule::in(['Easy', 'Medium', 'Hard'])],
            'is_active' => ['boolean'],
        ];
    }
}
