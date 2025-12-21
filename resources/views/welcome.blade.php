<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Monitoring BMN - KPKNL Banjarmasin</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased font-sans bg-white text-gray-900">
    <div class="min-h-screen flex flex-col items-center justify-center relative overflow-hidden">
        
        <!-- Decorative Circle (Subtle) -->
        <div class="absolute -top-20 -right-20 w-96 h-96 bg-blue-50 rounded-full blur-3xl opacity-50 pointer-events-none"></div>
        <div class="absolute -bottom-20 -left-20 w-96 h-96 bg-yellow-50 rounded-full blur-3xl opacity-50 pointer-events-none"></div>

        <!-- Main Content -->
        <div class="relative z-10 max-w-2xl w-full px-6 text-center">
            <!-- Logo -->
            <div class="mb-8">
                <img src="{{ asset('images/logo-kemenkeu.png') }}" alt="Logo Kemenkeu" class="w-20 h-auto mx-auto">
            </div>

            <!-- Title -->
            <div class="space-y-4">
                <h2 class="text-5xs font-bold tracking-[0.2em] text-kemenkeu-secondary uppercase">Sistem Monitoring</h2>
                <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">
                    Aplikasi Pengelolaan Agenda Persetujuan BMN Seksi Pengelolaan Kekayaan Negara di KPKNL Banjarmasin
                </h1>
                <p class="text-lg text-gray-500 mt-4 font-light">
                    KPKNL Banjarmasin — DJKN Kalimantan Selatan
                </p>
            </div>

            <!-- Action Button -->
            <div class="mt-12">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="inline-flex items-center px-8 py-3 border border-transparent text-base font-medium rounded-full text-white bg-kemenkeu-primary hover:bg-blue-800 md:text-lg transition duration-300 ease-in-out shadow-lg hover:shadow-xl">
                            Dashboard
                            <svg class="ml-2 -mr-1 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center px-8 py-3 border border-transparent text-base font-medium rounded-full text-white bg-kemenkeu-primary hover:bg-blue-800 md:text-lg transition duration-300 ease-in-out shadow-lg hover:shadow-xl group">
                            Masuk Aplikasi
                            <svg class="ml-2 -mr-1 w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    @endauth
                @endif
            </div>
        </div>

        <!-- Footer -->
        <div class="absolute bottom-6 w-full text-center">
            <p class="text-xs text-gray-400">
                &copy; 2025 Ihsan Nor Ramadhan (2210010377).
            </p>
        </div>
    </div>
</body>

</html>