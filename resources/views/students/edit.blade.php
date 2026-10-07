@extends('layouts.app')

@section('title', 'Edit student')

@section('content')
    <div class="page-heading">
        <div>
            <p class="eyebrow">Students / #{{ $student->id }}</p>
            <h1>Edit student</h1>
            <p class="page-subtitle">Update {{ $student->name }}'s information.</p>
        </div>
    </div>

    <section class="panel form-panel">
        @include('students._form', ['student' => $student])
    </section>
@endsection
