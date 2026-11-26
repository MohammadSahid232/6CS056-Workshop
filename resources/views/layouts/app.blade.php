@props(['title' => 'Home'])

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} | Training Institute</title>
</head>
<body>
    @include('partials.navbar')
    <main>
        @include('components.alert')
        {{ $slot }}
    </main>
    @include('partials.footer')
</body>
</html>
