<x-layouts.app title="Courses">
    <h1>Courses</h1>
    <p><a href="{{ route('courses.create') }}">Create Course</a></p>

    @if ($courses->isEmpty())
        <p>No courses found.</p>
    @else
        <table border="1" cellpadding="6" cellspacing="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Owner</th>
                    <th>Duration</th>
                    <th>Fee</th>
                    <th>Difficulty</th>
                    <th>Active</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($courses as $course)
                    <tr>
                        <td>{{ $course->id }}</td>
                        <td>{{ $course->name }}</td>
                        <td>{{ $course->user?->name ?? 'Unknown' }}</td>
                        <td>{{ $course->duration }} weeks</td>
                        <td>{{ number_format($course->fee, 2) }}</td>
                        <td>{{ $course->difficulty }}</td>
                        <td>{{ $course->is_active ? 'Yes' : 'No' }}</td>
                        <td>
                            @can('manage-course', $course)
                                <a href="{{ route('courses.show', $course) }}">View</a>
                                <a href="{{ route('courses.edit', $course) }}">Edit</a>
                                <x-delete-form :action="route('courses.destroy', $course)" />
                            @else
                                <em style="color: gray;">No access</em>
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</x-layouts.app>
