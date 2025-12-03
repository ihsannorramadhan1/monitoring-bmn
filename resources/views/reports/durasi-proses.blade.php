<x-app-layout>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Filter Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('reports.durasi-proses') }}" method="GET"
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
                            <a href="{{ route('reports.durasi-proses.pdf', request()->all()) }}" target="_blank"
                                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                Export PDF
                            </a>
                            <a href="{{ route('reports.durasi-proses.excel', request()->all()) }}"
                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Export Excel
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Chart -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Perbandingan Durasi Aktual vs Target SLA</h3>
                    <div class="w-full h-96">
                        <canvas id="durasiChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Summary Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Analisis Performa per Jenis Pengelolaan</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">
                                        Jenis Pengelolaan</th>
                                    <th
                                        class="px-4 py-2 text-center font-medium text-gray-500 uppercase tracking-wider">
                                        Target SLA (Hari)</th>
                                    <th
                                        class="px-4 py-2 text-center font-medium text-gray-500 uppercase tracking-wider">
                                        Avg Durasi Aktual</th>
                                    <th
                                        class="px-4 py-2 text-center font-medium text-gray-500 uppercase tracking-wider">
                                        Variance</th>
                                    <th
                                        class="px-4 py-2 text-center font-medium text-gray-500 uppercase tracking-wider">
                                        Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($data as $item)
                                    <tr>
                                        <td class="px-4 py-2 whitespace-nowrap font-medium">{{ $item['jenis'] }}</td>
                                        <td class="px-4 py-2 whitespace-nowrap text-center">{{ $item['target'] }}</td>
                                        <td class="px-4 py-2 whitespace-nowrap text-center">{{ $item['avg_actual'] }}</td>
                                        <td
                                            class="px-4 py-2 whitespace-nowrap text-center {{ $item['variance'] > 0 ? 'text-red-600 font-bold' : 'text-green-600' }}">
                                            {{ $item['variance'] > 0 ? '+' : '' }}{{ $item['variance'] }}
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap text-center">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $item['status'] == 'On Time' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $item['status'] }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Overdue List -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4 text-red-600">Daftar Agenda Overdue (Top 10)</h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-red-50">
                                <tr>
                                    <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">
                                        Nomor Agenda</th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">
                                        Satker</th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">
                                        Jenis</th>
                                    <th
                                        class="px-4 py-2 text-center font-medium text-gray-500 uppercase tracking-wider">
                                        Tgl Masuk</th>
                                    <th
                                        class="px-4 py-2 text-center font-medium text-gray-500 uppercase tracking-wider">
                                        Target</th>
                                    <th
                                        class="px-4 py-2 text-center font-medium text-gray-500 uppercase tracking-wider">
                                        Durasi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($overdueAgendas as $agenda)
                                    <tr>
                                        <td class="px-4 py-2 whitespace-nowrap font-medium">{{ $agenda->nomor_agenda }}</td>
                                        <td class="px-4 py-2 whitespace-nowrap">{{ $agenda->satker->nama_satker }}</td>
                                        <td class="px-4 py-2 whitespace-nowrap">{{ $agenda->jenisPengelolaan->nama_jenis }}
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap text-center">
                                            {{ $agenda->tanggal_masuk->format('d/m/Y') }}
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap text-center">
                                            {{ $agenda->tanggal_target->format('d/m/Y') }}
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap text-center font-bold text-red-600">
                                            {{ $agenda->durasi_hari }} Hari
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-2 text-center text-gray-500">Tidak ada agenda
                                            overdue.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('durasiChart').getContext('2d');
            const data = @json($data);

            const labels = data.map(item => item.jenis);
            const targets = data.map(item => item.target);
            const actuals = data.map(item => item.avg_actual);

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Target SLA (Hari)',
                            data: targets,
                            backgroundColor: 'rgba(59, 130, 246, 0.5)', // Blue
                            borderColor: 'rgb(59, 130, 246)',
                            borderWidth: 1
                        },
                        {
                            label: 'Rata-rata Durasi Aktual (Hari)',
                            data: actuals,
                            backgroundColor: 'rgba(239, 68, 68, 0.5)', // Red
                            borderColor: 'rgb(239, 68, 68)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Hari'
                            }
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>