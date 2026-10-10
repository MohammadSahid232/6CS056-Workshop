<x-layouts.app title="Edit Student">
    <h1>Edit Student</h1>

    <form action="{{ route('students.update', $student) }}" method="POST">
        @csrf
        @method('PUT')

        <x-students.form :student="$student" />

        <div class="form-actions">
            <button type="submit" class="btn">Update Student</button>
            <a href="{{ route('students.show', $student) }}" class="link-button">Cancel</a>
        </div>
    </form>
</x-layouts.app>
