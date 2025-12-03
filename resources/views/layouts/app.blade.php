<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Monitoring BMN - KPKNL Banjarmasin</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen flex flex-col bg-gradient-to-br from-kemenkeu-primary to-blue-900">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white shadow">
                <div class="max-w-full mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer class="bg-kemenkeu-primary text-white mt-auto">
            <div class="max-w-full mx-auto py-8 px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Alamat -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4 text-kemenkeu-secondary">KPKNL Banjarmasin</h3>
                        <p class="text-sm leading-relaxed">
                            Jl. Pramuka No. 7<br>
                            Banjarmasin, Kalimantan Selatan 70249
                        </p>
                    </div>

                    <!-- Kontak -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4 text-kemenkeu-secondary">Hubungi Kami</h3>
                        <ul class="text-sm space-y-2">
                            <li>Telepon: (0511) 4281286 / 4281287</li>
                            <li>WA Layanan: 0811-5167-100</li>
                            <li>Email: kpknlbanjarmasin@kemenkeu.go.id</li>
                        </ul>
                    </div>

                    <!-- Social Media / Copyright -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4 text-kemenkeu-secondary">Media Sosial</h3>
                        <div class="flex space-x-4 mb-4">
                            <!-- Icons can be added here later if needed -->
                            <a href="#" class="text-white hover:text-kemenkeu-secondary transition">Facebook</a>
                            <a href="#" class="text-white hover:text-kemenkeu-secondary transition">Instagram</a>
                            <a href="#" class="text-white hover:text-kemenkeu-secondary transition">Twitter</a>
                            <a href="#" class="text-white hover:text-kemenkeu-secondary transition">YouTube</a>
                        </div>
                        <p class="text-xs text-gray-300 mt-4">
                            &copy; 2025 Ihsan Nor Ramadhan (2210010377).
                        </p>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <x-toast />
    <x-loading-spinner />
</body>

</html>