<x-app-layout>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                <!-- Card 1: Laporan Agenda Masuk -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center mb-4">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Agenda Masuk</h3>
                    </div>
                    <p class="text-gray-600 text-sm mb-6">Laporan daftar agenda yang masuk dalam periode tertentu,
                        difilter berdasarkan status dan jenis pengelolaan.</p>
                    <a href="{{ route('reports.daftar-agenda') }}"
                        class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">
                        Generate Laporan
                    </a>
                </div>

                <!-- Card 2: Kinerja Satker -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center mb-4">
                        <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Kinerja Satker</h3>
                    </div>
                    <p class="text-gray-600 text-sm mb-6">Rekapitulasi jumlah agenda per Satker, termasuk status
                        penyelesaian dan ketepatan waktu (SLA).</p>
                    <a href="{{ route('reports.performance-satker') }}"
                        class="block w-full text-center bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">
                        Generate Laporan
                    </a>
                </div>

                <!-- Card 3: Rekap Jenis Pengelolaan -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center mb-4">
                        <div class="p-3 rounded-full bg-purple-100 text-purple-600 mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Jenis Pengelolaan</h3>
                    </div>
                    <p class="text-gray-600 text-sm mb-6">Ringkasan eksekutif bulanan/periodik dengan KPI utama, tren,
                        dan isu penting.</p>
                    <a href="{{ route('reports.summary-bulanan') }}"
                        class="block w-full text-center bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">
                        Generate Laporan
                    </a>
                </div>

                <!-- Card 4: Laporan SLA (Keterlambatan) -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center mb-4">
                        <div class="p-3 rounded-full bg-red-100 text-red-600 mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Monitoring SLA</h3>
                    </div>
                    <p class="text-gray-600 text-sm mb-6">Daftar agenda yang melewati batas waktu (Overdue) dan analisis
                        rata-rata durasi penyelesaian.</p>
                    <a href="{{ route('reports.durasi-proses') }}"
                        class="block w-full text-center bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">
                        Generate Laporan
                    </a>
                </div>

                <!-- Card 5: Log Aktivitas User -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center mb-4">
                        <div class="p-3 rounded-full bg-yellow-100 text-yellow-600 mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Aktivitas Staff</h3>
                    </div>
                    <p class="text-gray-600 text-sm mb-6">Laporan kinerja staff (PIC) dalam menangani agenda, jumlah
                        yang diselesaikan, dan beban kerja.</p>
                    <button
                        class="w-full bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">
                        Generate Laporan
                    </button>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>