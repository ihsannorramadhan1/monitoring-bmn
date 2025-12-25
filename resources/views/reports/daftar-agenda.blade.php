<x-app-layout>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Filter Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('reports.daftar-agenda') }}" method="GET"
                        class="grid grid-cols-1 md:grid-cols-4 gap-4">

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

                        <!-- Satker -->
                        <div>
                            <x-input-label for="satker_id" :value="__('Satker')" />
                            <select id="satker_id" name="satker_id"
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">
                                <option value="">Semua Satker</option>
                                @foreach($satkers as $satker)
                                    <option value="{{ $satker->id }}" {{ request('satker_id') == $satker->id ? 'selected' : '' }}>{{ $satker->nama_satker }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Jenis Pengelolaan -->
                        <div>
                            <x-input-label for="jenis_pengelolaan_id" :value="__('Jenis Pengelolaan')" />
                            <select id="jenis_pengelolaan_id" name="jenis_pengelolaan_id"
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">
                                <option value="">Semua Jenis</option>
                                @foreach($jenisPengelolaans as $jenis)
                                    <option value="{{ $jenis->id }}" {{ request('jenis_pengelolaan_id') == $jenis->id ? 'selected' : '' }}>{{ $jenis->nama_jenis }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Status -->
                        <div>
                            <x-input-label for="status" :value="__('Status')" />
                            <select id="status" name="status"
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">
                                <option value="">Semua Status</option>
                                <option value="masuk" {{ request('status') == 'masuk' ? 'selected' : '' }}>Masuk</option>
                                <option value="verifikasi" {{ request('status') == 'verifikasi' ? 'selected' : '' }}>
                                    Verifikasi</option>
                                <option value="disposisi" {{ request('status') == 'disposisi' ? 'selected' : '' }}>
                                    Disposisi</option>
                                <option value="proses" {{ request('status') == 'proses' ? 'selected' : '' }}>Proses
                                </option>
                                <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>
                                    Disetujui</option>
                                <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak
                                </option>
                                <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>
                                    Dibatalkan</option>
                            </select>
                        </div>

                        <!-- Buttons -->
                        <div class="md:col-span-4 flex justify-end space-x-2">
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Filter / Preview
                            </button>
                            <a href="{{ route('reports.daftar-agenda.pdf', request()->all()) }}" target="_blank"
                                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                Export PDF
                            </a>
                            <a href="{{ route('reports.daftar-agenda.excel', request()->all()) }}"
                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Export Excel
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Preview Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Preview Laporan ({{ $agendas->count() }} Data)</h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">
                                        No</th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">
                                        Nomor Agenda</th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">
                                        Satker</th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">
                                        Jenis</th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">
                                        Tgl Masuk</th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">
                                        Target</th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">
                                        Status</th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">
                                        PIC</th>
                                    @if(auth()->user()->role === 'admin')
                                        <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">
                                            Durasi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($agendas as $index => $agenda)
                                    <tr class="{{ $index % 2 == 0 ? 'bg-white' : 'bg-gray-50' }}">
                                        <td class="px-4 py-2 whitespace-nowrap">{{ $index + 1 }}</td>
                                        <td class="px-4 py-2 whitespace-nowrap font-medium">{{ $agenda->nomor_agenda }}</td>
                                        <td class="px-4 py-2 whitespace-nowrap">{{ $agenda->satker->nama_satker }}</td>
                                        <td class="px-4 py-2 whitespace-nowrap">{{ $agenda->jenisPengelolaan->nama_jenis }}
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap">
                                            {{ $agenda->tanggal_masuk->format('d/m/Y') }}
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap">
                                            {{ $agenda->tanggal_target->format('d/m/Y') }}
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap">{{ ucfirst($agenda->status) }}</td>
                                        <td class="px-4 py-2 whitespace-nowrap">{{ $agenda->pic->name }}</td>
                                        @if(auth()->user()->role === 'admin')
                                            <td class="px-4 py-2 whitespace-nowrap">{{ $agenda->durasi_hari }} Hari</td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ auth()->user()->role === 'admin' ? 9 : 8 }}"
                                            class="px-4 py-2 text-center text-gray-500">Tidak ada data agenda.
                                        </td>
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