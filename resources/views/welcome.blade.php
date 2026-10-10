@php
    $studentCount = \App\Models\Student::count();
    $courseCount = \App\Models\Course::count();
    $latestStudents = \App\Models\Student::latest()->take(3)->get();
    $latestCourses = \App\Models\Course::latest()->take(3)->get();
@endphp

<x-layouts.app title="Welcome">
    <div class="welcome-panel hero">
        <span class="eyebrow">Workshop 2 • MVC Refactor</span>
        <h1>Training Institute Management System</h1>
        <p>
            Organized using MVC, named routes, reusable Blade components, and a clean DRY layout for students and courses.
        </p>
        <div class="form-actions center-actions">
            <a href="{{ route('students.index') }}" class="btn">Manage Students</a>
            <a href="{{ route('courses.index') }}" class="btn btn-secondary">Manage Courses</a>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <span class="stat-label">Students</span>
            <strong>{{ $studentCount }}</strong>
        </div>
        <div class="stat-card">
            <span class="stat-label">Courses</span>
            <strong>{{ $courseCount }}</strong>
        </div>
        <div class="stat-card">
            <span class="stat-label">System</span>
            <strong>MVC</strong>
        </div>
    </div>

    <div class="feature-grid">
        <div class="feature-card">
            <h3>Latest Students</h3>
            @if($latestStudents->isEmpty())
                <p>No students available yet.</p>
            @else
                <ul>
                    @foreach($latestStudents as $student)
                        <li>{{ $student->name }} • {{ $student->email }}</li>
                    @endforeach
                </ul>
            @endif
        </div>

        <div class="feature-card">
            <h3>Latest Courses</h3>
            @if($latestCourses->isEmpty())
                <p>No courses available yet.</p>
            @else
                <ul>
                    @foreach($latestCourses as $course)
                        <li>{{ $course->name }} • {{ $course->difficulty }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</x-layouts.app>
