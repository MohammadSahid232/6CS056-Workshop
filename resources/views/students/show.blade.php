@extends('layouts.app')

@section('title', $student->name)

@section('content')
    <div class="page-heading">
        <div>
            <p class="eyebrow"><a href="{{ route('students.index') }}">Students</a> / #{{ $student->id }}</p>
            <h1>{{ $student->name }}</h1>
            <p class="page-subtitle">Student record</p>
        </div>
        <div class="heading-actions">
            <a class="button button-secondary" href="{{ route('students.edit', $student) }}">Edit student</a>
            <form action="{{ route('students.destroy', $student) }}" method="POST" onsubmit="return confirm('Delete this student?')">
                @csrf
                @method('DELETE')
                <button class="button button-danger" type="submit">Delete</button>
            </form>
        </div>
    </div>

    <section class="panel detail-panel">
        <dl class="detail-grid">
            <div><dt>Email address</dt><dd><a href="mailto:{{ $student->email }}">{{ $student->email }}</a></dd></div>
            <div><dt>Phone number</dt><dd><a href="tel:{{ $student->phone }}">{{ $student->phone }}</a></dd></div>
            <div><dt>Date of birth</dt><dd>{{ $student->date_of_birth?->format('F j, Y') ?? 'Not provided' }}</dd></div>
            <div><dt>Address</dt><dd>{!! $student->address ? nl2br(e($student->address)) : 'Not provided' !!}</dd></div>
            <div><dt>Created</dt><dd>{{ $student->created_at->format('F j, Y, g:i a') }}</dd></div>
            <div><dt>Last updated</dt><dd>{{ $student->updated_at->format('F j, Y, g:i a') }}</dd></div>
        </dl>
    </section>
@endsection
