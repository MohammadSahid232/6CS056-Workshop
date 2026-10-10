<x-layouts.app title="Students">
    <div class="page-heading">
        <h1>Students</h1>
        <a href="{{ route('students.create') }}" class="btn">Create Student</a>
    </div>

    @if ($students->isEmpty())
        <p>No students found.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Age</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($students as $student)
                    <tr>
                        <td>{{ $student->id }}</td>
                        <td>{{ $student->name }}</td>
                        <td>{{ $student->email }}</td>
                        <td>{{ $student->phone }}</td>
                        <td>{{ $student->age ?? 'Not available' }}</td>
                        <td class="actions-cell">
                            <a href="{{ route('students.show', $student) }}" class="table-link">View</a>
                            <a href="{{ route('students.edit', $student) }}" class="table-link">Edit</a>
                            <x-delete-form :action="route('students.destroy', $student)" />
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</x-layouts.app>
