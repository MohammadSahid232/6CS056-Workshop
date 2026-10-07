<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Institute Manager') · {{ config('app.name', 'Institute Manager') }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <header class="site-header">
        <div class="header-inner">
            <a class="brand" href="{{ route('students.index') }}">
                <span class="brand-mark" aria-hidden="true">I</span>
                <span>Institute<span class="brand-light">Manager</span></span>
            </a>
            <nav class="main-nav" aria-label="Main navigation">
                <a @class(['nav-link', 'is-active' => request()->routeIs('students.*')]) href="{{ route('students.index') }}">Students</a>
                <a @class(['nav-link', 'is-active' => request()->routeIs('courses.*')]) href="{{ route('courses.index') }}">Courses</a>
            </nav>
        </div>
    </header>

    <main class="page-shell">
        @if (session('success'))
            <div class="alert alert-success" role="status">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-error" role="alert">
                <strong>Please check the form and correct these errors:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="site-footer">Training Institute · Student and course records</footer>
</body>
</html>
