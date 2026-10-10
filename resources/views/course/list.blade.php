<x-layouts.app title="Courses">
    <div class="page-heading">
        <h1>Courses</h1>
        <a href="{{ route('courses.create') }}" class="btn">Create Course</a>
    </div>

    @if ($courses->isEmpty())
        <p>No courses found.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
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
                        <td>{{ $course->duration }} weeks</td>
                        <td>{{ number_format($course->fee, 2) }}</td>
                        <td>{{ $course->difficulty }}</td>
                        <td>{{ $course->is_active ? 'Yes' : 'No' }}</td>
                        <td class="actions-cell">
                            <a href="{{ route('courses.show', $course) }}" class="table-link">View</a>
                            <a href="{{ route('courses.edit', $course) }}" class="table-link">Edit</a>
                            <x-delete-form :action="route('courses.destroy', $course)" />
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</x-layouts.app>
