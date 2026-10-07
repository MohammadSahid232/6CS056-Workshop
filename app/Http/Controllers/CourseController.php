<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:255'],
            'difficulty' => ['nullable', Rule::in(Course::DIFFICULTIES)],
            'status' => ['nullable', Rule::in(['active', 'inactive'])],
        ]);

        $search = trim($filters['q'] ?? '');

        $courses = Course::query()
            ->when($search !== '', function (Builder $query) use ($search): void {
                $query->where(function (Builder $query) use ($search): void {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when(
                $filters['difficulty'] ?? null,
                fn (Builder $query, string $difficulty) => $query->where('difficulty', $difficulty),
            )
            ->when(
                $filters['status'] ?? null,
                fn (Builder $query, string $status) => $query->where('is_active', $status === 'active'),
            )
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('courses.index', [
            'courses' => $courses,
            'search' => $search,
            'difficulty' => $filters['difficulty'] ?? '',
            'status' => $filters['status'] ?? '',
        ]);
    }

    public function create(): View
    {
        return view('courses.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules());

        $course = Course::create($validated);

        return redirect()
            ->route('courses.index')
            ->with('success', "{$course->name} was added successfully.");
    }

    public function show(Course $course): View
    {
        return view('courses.show', compact('course'));
    }

    public function edit(Course $course): View
    {
        return view('courses.edit', compact('course'));
    }

    public function update(Request $request, Course $course): RedirectResponse
    {
        $course->update($request->validate($this->rules()));

        return redirect()
            ->route('courses.show', $course)
            ->with('success', 'Course details were updated successfully.');
    }

    public function destroy(Course $course): RedirectResponse
    {
        $course->delete();

        return redirect()
            ->route('courses.index')
            ->with('success', 'Course was deleted successfully.');
    }

    /**
     * @return array<string, array<int, string>>
     */
    private function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'duration' => ['required', 'integer', 'min:1'],
            'fee' => ['required', 'numeric', 'min:0', 'decimal:0,2'],
            'difficulty' => ['required', Rule::in(Course::DIFFICULTIES)],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
