<x-layouts.app title="Student Details">
    <h1>Student Details</h1>

    <div class="detail-card">
        <p><strong>ID:</strong> {{ $student->id }}</p>
        <p><strong>Name:</strong> {{ $student->name }}</p>
        <p><strong>Email:</strong> {{ $student->email }}</p>
        <p><strong>Phone:</strong> {{ $student->phone }}</p>
        <p><strong>Address:</strong> {{ $student->address }}</p>
        <p><strong>Date of Birth:</strong> {{ $student->date_of_birth?->format('d M Y') }}</p>
        <p><strong>Age:</strong> {{ $student->age ?? 'Not available' }}</p>
    </div>

    <div class="form-actions">
        <a href="{{ route('students.edit', $student) }}" class="btn">Edit Student</a>
        <a href="{{ route('students.index') }}" class="link-button">Back to Students</a>
    </div>
</x-layouts.app>
