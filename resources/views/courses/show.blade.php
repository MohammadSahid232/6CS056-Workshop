@extends('layouts.app')

@section('title', $course->name)

@section('content')
    <div class="page-heading">
        <div>
            <p class="eyebrow"><a href="{{ route('courses.index') }}">Courses</a> / #{{ $course->id }}</p>
            <h1>{{ $course->name }}</h1>
            <p class="page-subtitle">Course record</p>
        </div>
        <div class="heading-actions">
            <a class="button button-secondary" href="{{ route('courses.edit', $course) }}">Edit course</a>
            <form action="{{ route('courses.destroy', $course) }}" method="POST" onsubmit="return confirm('Delete this course?')">
                @csrf
                @method('DELETE')
                <button class="button button-danger" type="submit">Delete</button>
            </form>
        </div>
    </div>

    <section class="panel detail-panel">
        <dl class="detail-grid">
            <div><dt>Description</dt><dd>{!! $course->description ? nl2br(e($course->description)) : 'Not provided' !!}</dd></div>
            <div><dt>Duration</dt><dd>{{ $course->duration }} {{ \Illuminate\Support\Str::plural('week', $course->duration) }}</dd></div>
            <div><dt>Fee</dt><dd>{{ number_format((float) $course->fee, 2) }}</dd></div>
            <div><dt>Difficulty</dt><dd><span class="badge badge-neutral">{{ $course->difficulty }}</span></dd></div>
            <div><dt>Status</dt><dd><span @class(['badge', 'badge-success' => $course->is_active, 'badge-muted' => ! $course->is_active])>{{ $course->is_active ? 'Active' : 'Inactive' }}</span></dd></div>
            <div><dt>Created</dt><dd>{{ $course->created_at->format('F j, Y, g:i a') }}</dd></div>
            <div><dt>Last updated</dt><dd>{{ $course->updated_at->format('F j, Y, g:i a') }}</dd></div>
        </dl>
    </section>
@endsection
