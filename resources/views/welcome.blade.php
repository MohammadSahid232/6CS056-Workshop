<x-layouts.app title="Welcome">
    <h1>Training Institute Management System</h1>
    <p>
        Manage students and courses securely with role-based access control, course ownership, and Laravel Gates.
    </p>

    @auth
        <p>
            <a href="{{ route('courses.index') }}">Manage Courses</a> |
            <a href="{{ route('students.index') }}">Manage Students</a>
        </p>

        @php
            $user = auth()->user();
            $studentCount = \App\Models\Student::count();
            $courseQuery = $user->role === 'admin' ? \App\Models\Course::query() : $user->courses();
            $courseCount = (clone $courseQuery)->count();
            $latestStudents = \App\Models\Student::latest()->take(3)->get();
            $latestCourses = $courseQuery->latest()->take(3)->get();
        @endphp

        <ul>
            <li><strong>Students:</strong> {{ $studentCount }}</li>
            <li><strong>{{ $user->role === 'admin' ? 'All Courses' : 'My Courses' }}:</strong> {{ $courseCount }}</li>
            <li><strong>Signed In As:</strong> {{ ucfirst($user->role) }}</li>
        </ul>

        <h3>Latest Students</h3>
        @if ($latestStudents->isEmpty())
            <p>No students available yet.</p>
        @else
            <ul>
                @foreach ($latestStudents as $student)
                    <li>{{ $student->name }} • {{ $student->email }}</li>
                @endforeach
            </ul>
        @endif

        <h3>Latest Courses</h3>
        @if ($latestCourses->isEmpty())
            <p>No courses available yet.</p>
        @else
            <ul>
                @foreach ($latestCourses as $course)
                    <li>{{ $course->name }} • {{ $course->difficulty }}</li>
                @endforeach
            </ul>
        @endif
    @endauth

    @guest
        <p>
            <a href="{{ route('login') }}">Login</a> |
            <a href="{{ route('register') }}">Register</a>
        </p>
    @endguest
</x-layouts.app>
