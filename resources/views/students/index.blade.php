@extends('layouts.app')

@section('title', 'Students')

@section('content')
    <div class="page-heading">
        <div>
            <p class="eyebrow">People</p>
            <h1>Students</h1>
            <p class="page-subtitle">Manage student contact and personal information.</p>
        </div>
        <a class="button button-primary" href="{{ route('students.create') }}">Add student</a>
    </div>

    <section class="panel">
        <form class="toolbar" action="{{ route('students.index') }}" method="GET">
            <label class="sr-only" for="student-search">Search students</label>
            <input id="student-search" class="search-input" type="search" name="q" value="{{ $search }}" placeholder="Search name, email, or phone">
            <button class="button button-secondary" type="submit">Search</button>
            @if ($search !== '')
                <a class="text-link" href="{{ route('students.index') }}">Clear</a>
            @endif
        </form>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th scope="col">Student</th>
                        <th scope="col">Email</th>
                        <th scope="col">Phone</th>
                        <th scope="col">Date of birth</th>
                        <th scope="col"><span class="sr-only">Actions</span></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($students as $student)
                        <tr>
                            <td>
                                <a class="primary-cell" href="{{ route('students.show', $student) }}">{{ $student->name }}</a>
                                <span class="secondary-cell">Student #{{ $student->id }}</span>
                            </td>
                            <td>{{ $student->email }}</td>
                            <td>{{ $student->phone }}</td>
                            <td>{{ $student->date_of_birth?->format('M j, Y') ?? '—' }}</td>
                            <td class="actions-cell">
                                <a class="text-link" href="{{ route('students.show', $student) }}">View</a>
                                <a class="text-link" href="{{ route('students.edit', $student) }}">Edit</a>
                                <form action="{{ route('students.destroy', $student) }}" method="POST" onsubmit="return confirm('Delete this student?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-button text-danger" type="submit">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="empty-state" colspan="5">
                                <strong>{{ $search !== '' ? 'No matching students' : 'No students yet' }}</strong>
                                <span>{{ $search !== '' ? 'Try a different search.' : 'Add your first student to get started.' }}</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($students->hasPages())
            <div class="pagination-wrap">{{ $students->links() }}</div>
        @endif
    </section>
@endsection
