@extends('layouts.app')

@section('title', 'Courses')

@section('content')
    <div class="page-heading">
        <div>
            <p class="eyebrow">Learning</p>
            <h1>Courses</h1>
            <p class="page-subtitle">Manage the institute's course catalogue.</p>
        </div>
        <a class="button button-primary" href="{{ route('courses.create') }}">Add course</a>
    </div>

    <section class="panel">
        <form class="toolbar filter-toolbar" action="{{ route('courses.index') }}" method="GET">
            <label class="sr-only" for="course-search">Search courses</label>
            <input id="course-search" class="search-input" type="search" name="q" value="{{ $search }}" placeholder="Search course name or description">

            <label class="sr-only" for="difficulty-filter">Filter by difficulty</label>
            <select id="difficulty-filter" name="difficulty">
                <option value="">All difficulties</option>
                @foreach (\App\Models\Course::DIFFICULTIES as $level)
                    <option value="{{ $level }}" @selected($difficulty === $level)>{{ $level }}</option>
                @endforeach
            </select>

            <label class="sr-only" for="status-filter">Filter by status</label>
            <select id="status-filter" name="status">
                <option value="">All statuses</option>
                <option value="active" @selected($status === 'active')>Active</option>
                <option value="inactive" @selected($status === 'inactive')>Inactive</option>
            </select>

            <button class="button button-secondary" type="submit">Apply</button>
            @if ($search !== '' || $difficulty !== '' || $status !== '')
                <a class="text-link" href="{{ route('courses.index') }}">Clear</a>
            @endif
        </form>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th scope="col">Course</th>
                        <th scope="col">Duration</th>
                        <th scope="col">Fee</th>
                        <th scope="col">Difficulty</th>
                        <th scope="col">Status</th>
                        <th scope="col"><span class="sr-only">Actions</span></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($courses as $course)
                        <tr>
                            <td>
                                <a class="primary-cell" href="{{ route('courses.show', $course) }}">{{ $course->name }}</a>
                                <span class="secondary-cell">{{ \Illuminate\Support\Str::limit($course->description ?? 'No description', 72) }}</span>
                            </td>
                            <td>{{ $course->duration }} {{ \Illuminate\Support\Str::plural('week', $course->duration) }}</td>
                            <td>{{ number_format((float) $course->fee, 2) }}</td>
                            <td><span class="badge badge-neutral">{{ $course->difficulty }}</span></td>
                            <td><span @class(['badge', 'badge-success' => $course->is_active, 'badge-muted' => ! $course->is_active])>{{ $course->is_active ? 'Active' : 'Inactive' }}</span></td>
                            <td class="actions-cell">
                                <a class="text-link" href="{{ route('courses.show', $course) }}">View</a>
                                <a class="text-link" href="{{ route('courses.edit', $course) }}">Edit</a>
                                <form action="{{ route('courses.destroy', $course) }}" method="POST" onsubmit="return confirm('Delete this course?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-button text-danger" type="submit">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="empty-state" colspan="6">
                                <strong>{{ $search !== '' || $difficulty !== '' || $status !== '' ? 'No matching courses' : 'No courses yet' }}</strong>
                                <span>{{ $search !== '' || $difficulty !== '' || $status !== '' ? 'Try changing or clearing your filters.' : 'Add your first course to get started.' }}</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($courses->hasPages())
            <div class="pagination-wrap">{{ $courses->links() }}</div>
        @endif
    </section>
@endsection
