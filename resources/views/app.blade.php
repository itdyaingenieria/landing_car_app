<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title inertia>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @routes
    @if (app()->environment('local'))
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
    @else
        <!-- Usa los archivos compilados en producción -->
        <link rel="stylesheet" href="{{ asset('build/assets/app.css') }}">
        <script type="module" src="{{ asset('build/assets/app.js') }}"></script>
    @endif
    @inertiaHead
</head>

<body class="font-sans antialiased">
    @inertia
</body>

</html>
