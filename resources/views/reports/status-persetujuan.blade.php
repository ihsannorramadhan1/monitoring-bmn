<x-app-layout>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Filter Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('reports.status-persetujuan') }}" method="GET"
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
                            <a href="{{ route('reports.status-persetujuan.pdf', request()->all()) }}" target="_blank"
                                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                Export PDF
                            </a>
                            <a href="{{ route('reports.status-persetujuan.excel', request()->all()) }}"
                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Export Excel
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Summary Table -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold mb-4">Ringkasan Status</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 text-sm">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-4 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">
                                            Status</th>
                                        <th
                                            class="px-4 py-2 text-center font-medium text-gray-500 uppercase tracking-wider">
                                            Jumlah</th>
                                        <th
                                            class="px-4 py-2 text-center font-medium text-gray-500 uppercase tracking-wider">
                                            Persentase</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($data as $item)
                                        <tr>
                                            <td class="px-4 py-2 whitespace-nowrap font-medium">
                                                {{ ucfirst($item['status']) }}
                                            </td>
                                            <td class="px-4 py-2 whitespace-nowrap text-center">{{ $item['total'] }}</td>
                                            <td class="px-4 py-2 whitespace-nowrap text-center">{{ $item['percentage'] }}%
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr class="bg-gray-100 font-bold">
                                        <td class="px-4 py-2">Total</td>
                                        <td class="px-4 py-2 text-center">{{ $total }}</td>
                                        <td class="px-4 py-2 text-center">100%</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Chart -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 flex flex-col items-center justify-center h-full">
                        <h3 class="text-lg font-semibold mb-4">Visualisasi Status</h3>
                        <div class="w-full max-w-md">
                            <canvas id="statusChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detailed List -->
            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Detail Agenda per Status</h3>

                    <div class="space-y-4">
                        @foreach($data as $item)
                            <div x-data="{ open: false }" class="border rounded-md">
                                <button @click="open = !open"
                                    class="flex justify-between items-center w-full px-4 py-3 bg-gray-50 hover:bg-gray-100 transition">
                                    <span class="font-medium text-gray-700">{{ ucfirst($item['status']) }}
                                        ({{ $item['total'] }})</span>
                                    <svg :class="{'rotate-180': open}" class="w-5 h-5 text-gray-500 transition-transform"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <div x-show="open" class="p-4 bg-white border-t">
                                    @if(count($item['details']) > 0)
                                        <ul class="list-disc pl-5 space-y-1 text-sm text-gray-600">
                                            @foreach($item['details'] as $detail)
                                                <li>
                                                    <span class="font-semibold">{{ $detail->nomor_agenda }}</span> -
                                                    {{ $detail->satker->nama_satker }}
                                                    <span
                                                        class="text-gray-400">({{ $detail->tanggal_masuk->format('d/m/Y') }})</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="text-sm text-gray-500 italic">Tidak ada data.</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('statusChart').getContext('2d');
            const data = @json($data);

            const labels = data.map(item => item.status.charAt(0).toUpperCase() + item.status.slice(1));
            const totals = data.map(item => item.total);
            const colors = [
                '#3B82F6', // Blue
                '#10B981', // Green
                '#F59E0B', // Yellow
                '#EF4444', // Red
                '#8B5CF6', // Purple
                '#6B7280', // Gray
                '#EC4899', // Pink
            ];

            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        data: totals,
                        backgroundColor: colors,
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>