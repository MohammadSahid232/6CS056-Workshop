@extends('layouts.app')

@section('title', 'Add student')

@section('content')
    <div class="page-heading">
        <div>
            <p class="eyebrow">Students</p>
            <h1>Add a student</h1>
            <p class="page-subtitle">Enter the student's details below.</p>
        </div>
    </div>

    <section class="panel form-panel">
        @include('students._form', ['student' => null])
    </section>
@endsection
