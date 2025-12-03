<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Sistem Monitoring Agenda Persetujuan BMN</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased bg-gray-50">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        <div class="text-center mb-6">
            <a href="/">
                <img src="{{ asset('images/logo-kemenkeu.png') }}" alt="Logo Kemenkeu" class="w-16 h-auto mx-auto">
            </a>
            <h1 class="mt-4 text-xl font-bold text-gray-900">Sistem Monitoring BMN</h1>
            <p class="text-sm text-gray-500">KPKNL Banjarmasin</p>
        </div>

        <div class="w-full sm:max-w-md px-8 py-8 bg-white shadow-lg rounded-2xl border border-gray-100">
            {{ $slot }}
        </div>

        <div class="mt-8 text-center text-xs text-gray-400">
            &copy; 2025 Ihsan Nor Ramadhan (2210010377).
        </div>
    </div>
</body>

</html>