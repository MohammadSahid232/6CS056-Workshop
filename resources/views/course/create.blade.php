<x-layouts.app title="Create Course">
    <h1>Create Course</h1>

    <form action="{{ route('courses.store') }}" method="POST">
        @csrf
        @include('course._form', ['course' => new \App\Models\Course(['is_active' => true])])

        <div class="form-actions">
            <button type="submit" class="btn">Create Course</button>
            <a href="{{ route('courses.index') }}" class="link-button">Cancel</a>
        </div>
    </form>
</x-layouts.app>
