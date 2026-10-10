<x-layouts.app title="Edit Course">
    <h1>Edit Course</h1>

    <form action="{{ route('courses.update', $course) }}" method="POST">
        @csrf
        @method('PUT')
        @include('course._form', ['course' => $course])

        <div class="form-actions">
            <button type="submit" class="btn">Update Course</button>
            <a href="{{ route('courses.show', $course) }}" class="link-button">Cancel</a>
        </div>
    </form>
</x-layouts.app>
