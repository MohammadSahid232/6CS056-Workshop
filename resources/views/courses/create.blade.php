@extends('layouts.app')

@section('title', 'Add course')

@section('content')
    <div class="page-heading">
        <div>
            <p class="eyebrow">Courses</p>
            <h1>Add a course</h1>
            <p class="page-subtitle">Enter the course details below.</p>
        </div>
    </div>

    <section class="panel form-panel">
        @include('courses._form', ['course' => null])
    </section>
@endsection
