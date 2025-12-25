<x-app-layout>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Filter Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('reports.summary-bulanan') }}" method="GET"
                        class="grid grid-cols-1 md:grid-cols-3 gap-4">

                        <!-- Date Range -->
                        <div>
                            <x-input-label for="start_date" :value="__('Dari Tanggal')" />
                            <x-text-input id="start_date" class="block mt-1 w-full" type="date" name="start_date"
                                :value="request('start_date')" />
                        </div>
                        <div>
                            <x-input-label for="end_date" :value="__('Sampai Tanggal')" />
                            <x-text-input id="end_date" class="block mt-1 w-full" type="date" name="end_date"
                                :value="request('end_date')" />
                        </div>

                        <!-- Buttons -->
                        <div class="flex items-end space-x-2">
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Filter / Preview
                            </button>
                            <a href="{{ route('reports.summary-bulanan.pdf', request()->all()) }}" target="_blank"
                                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                Export PDF
                            </a>
                            <a href="{{ route('reports.summary-bulanan.excel', request()->all()) }}"
                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Export Excel
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- KPI Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <!-- Total Agenda -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-500 text-sm uppercase font-bold">Total Agenda</div>
                    <div class="text-3xl font-bold text-gray-800 mt-2">{{ $kpi['total'] }}</div>
                    <div class="text-sm mt-2 {{ $trends['total'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ $trends['total'] >= 0 ? '↑' : '↓' }} {{ abs($trends['total']) }}% vs last period
                    </div>
                </div>

                <!-- Completed -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-500 text-sm uppercase font-bold">Completed</div>
                    <div class="text-3xl font-bold text-green-600 mt-2">{{ $kpi['completed'] }}</div>
                    <div class="text-sm mt-2 {{ $trends['completed'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ $trends['completed'] >= 0 ? '↑' : '↓' }} {{ abs($trends['completed']) }}% vs last period
                    </div>
                </div>

                <!-- Pending -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-500 text-sm uppercase font-bold">Pending</div>
                    <div class="text-3xl font-bold text-yellow-600 mt-2">{{ $kpi['pending'] }}</div>
                    <div class="text-sm mt-2 text-gray-500">
                        Active workloads
                    </div>
                </div>

                <!-- Avg Duration -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-500 text-sm uppercase font-bold">Avg Duration</div>
                    <div class="text-3xl font-bold text-blue-600 mt-2">{{ round($kpi['avg_duration'], 1) }} <span
                            class="text-sm text-gray-500">days</span></div>
                    <div class="text-sm mt-2 {{ $trends['avg_duration'] <= 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ $trends['avg_duration'] > 0 ? '↑' : '↓' }} {{ abs($trends['avg_duration']) }}% vs last period
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Trend Chart -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Trend 6 Bulan Terakhir</h3>
                    <div class="h-64">
                        <canvas id="trendChart"></canvas>
                    </div>
                </div>

                <!-- Pie Chart -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Distribusi Status (Periode Ini)</h3>
                    <div class="h-64">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Bottom Row: Top Satker & Bottlenecks -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Top 5 Satker -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4 text-blue-800">Top 5 Most Active Satker</h3>
                    <ul class="space-y-3">
                        @forelse($topSatkers as $index => $item)
                            <li class="flex items-center justify-between border-b pb-2">
                                <span class="flex items-center">
                                    <span
                                        class="w-6 h-6 rounded-full bg-blue-100 text-blue-800 flex items-center justify-center text-xs font-bold mr-3">
                                        {{ $index + 1 }}
                                    </span>
                                    {{ $item->satker->nama_satker }}
                                </span>
                                <span class="font-bold text-gray-700">{{ $item->total }}</span>
                            </li>
                        @empty
                            <li class="text-gray-500">No data available.</li>
                        @endforelse
                    </ul>
                </div>

                <!-- Bottlenecks -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4 text-red-800">Key Issues / Bottlenecks</h3>

                    <div class="mb-4">
                        <div class="text-xs text-gray-500 uppercase font-bold mb-1">Most Delayed Jenis Pengelolaan</div>
                        @if($mostDelayedJenis)
                            <div class="font-semibold text-red-600">{{ $mostDelayedJenis->nama_jenis }}</div>
                        @else
                            <div class="text-gray-500 italic">None</div>
                        @endif
                    </div>

                    <div>
                        <div class="text-xs text-gray-500 uppercase font-bold mb-1">Longest Pending Agenda</div>
                        @if($longestPending)
                            <div class="font-semibold text-gray-800">{{ $longestPending->nomor_agenda }}</div>
                            <div class="text-sm text-gray-600">{{ $longestPending->satker->nama_satker }}</div>
                            <div class="text-xs text-red-500 mt-1">Since
                                {{ $longestPending->tanggal_masuk->format('d M Y') }}
                                ({{ $longestPending->tanggal_masuk->diffInDays(now()) }} days)
                            </div>
                        @else
                            <div class="text-gray-500 italic">None</div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Trend Chart
            const trendCtx = document.getElementById('trendChart').getContext('2d');
            const trendData = @json($monthlyTrend);
            new Chart(trendCtx, {
                type: 'line',
                data: {
                    labels: Object.keys(trendData),
                    datasets: [{
                        label: 'Total Agenda',
                        data: Object.values(trendData),
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true } }
                }
            });

            // Status Chart
            const statusCtx = document.getElementById('statusChart').getContext('2d');
            const statusData = @json($statusDist);
            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: Object.keys(statusData),
                    datasets: [{
                        data: Object.values(statusData),
                        backgroundColor: [
                            'rgb(34, 197, 94)', // Green (Selesai)
                            'rgb(234, 179, 8)', // Yellow (Pending)
                            'rgb(239, 68, 68)'  // Red (Ditolak)
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'right' } }
                }
            });
        });
    </script>
</x-app-layout>