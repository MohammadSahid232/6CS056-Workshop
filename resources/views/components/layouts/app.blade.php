@props(['title' => 'Home'])

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} | Training Institute</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <x-navbar />
    <main class="container">
        <div class="page-tools">
            <button type="button" class="btn btn-secondary back-button" onclick="window.history.back(); return false;">
                Back
            </button>
            <a href="{{ route('home') }}" class="btn btn-secondary home-button">Home</a>
        </div>
        <x-alert />
        {{ $slot }}
    </main>
    <x-footer />
</body>
</html>
