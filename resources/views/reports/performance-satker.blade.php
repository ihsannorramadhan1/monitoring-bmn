<x-app-layout>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Filter Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('reports.performance-satker') }}" method="GET"
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
                            <a href="{{ route('reports.performance-satker.pdf', request()->all()) }}" target="_blank"
                                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                Export PDF
                            </a>
                            <a href="{{ route('reports.performance-satker.excel', request()->all()) }}"
                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Export Excel
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Ranking Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Peringkat Kinerja Satker (Berdasarkan Completion Rate)</h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-4 py-2 text-center font-medium text-gray-500 uppercase tracking-wider">
                                        Rank</th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">
                                        Nama Satker</th>
                                    <th
                                        class="px-4 py-2 text-center font-medium text-gray-500 uppercase tracking-wider">
                                        Total Agenda</th>
                                    <th
                                        class="px-4 py-2 text-center font-medium text-gray-500 uppercase tracking-wider">
                                        Selesai</th>
                                    <th
                                        class="px-4 py-2 text-center font-medium text-gray-500 uppercase tracking-wider">
                                        Ditolak</th>
                                    <th
                                        class="px-4 py-2 text-center font-medium text-gray-500 uppercase tracking-wider">
                                        Pending</th>
                                    <th
                                        class="px-4 py-2 text-center font-medium text-gray-500 uppercase tracking-wider">
                                        Avg Duration</th>
                                    <th
                                        class="px-4 py-2 text-center font-medium text-gray-500 uppercase tracking-wider">
                                        Score</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($rankedData as $index => $item)
                                    <tr class="{{ $index < 3 ? 'bg-yellow-50' : '' }}">
                                        <td class="px-4 py-2 whitespace-nowrap text-center font-bold">
                                            @if($index == 0) 🥇 @elseif($index == 1) 🥈 @elseif($index == 2) 🥉 @else
                                            {{ $index + 1 }} @endif
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap font-medium">{{ $item['nama_satker'] }}</td>
                                        <td class="px-4 py-2 whitespace-nowrap text-center">{{ $item['total'] }}</td>
                                        <td class="px-4 py-2 whitespace-nowrap text-center text-green-600 font-bold">
                                            {{ $item['completed'] }}
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap text-center text-red-600">
                                            {{ $item['rejected'] }}
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap text-center text-yellow-600">
                                            {{ $item['pending'] }}
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap text-center">{{ $item['avg_duration'] }} Hari
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap text-center font-bold text-blue-600">
                                            {{ $item['score'] }}%
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-4 py-2 text-center text-gray-500">Tidak ada data kinerja
                                            satker.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>