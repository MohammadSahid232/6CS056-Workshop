@extends('layouts.app')

@section('title', 'Edit course')

@section('content')
    <div class="page-heading">
        <div>
            <p class="eyebrow">Courses / #{{ $course->id }}</p>
            <h1>Edit course</h1>
            <p class="page-subtitle">Update {{ $course->name }}'s details.</p>
        </div>
    </div>

    <section class="panel form-panel">
        @include('courses._form', ['course' => $course])
    </section>
@endsection
