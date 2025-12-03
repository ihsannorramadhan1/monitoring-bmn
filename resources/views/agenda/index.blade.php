<x-app-layout>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                    role="alert">
                    <span class="block sm:inline">{!! session('success') !!}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="flex flex-col md:flex-row justify-between items-center mb-6 space-y-4 md:space-y-0">
                        <a href="{{ route('agenda.create') }}"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            + Tambah Agenda
                        </a>

                        <form action="{{ route('agenda.index') }}" method="GET"
                            class="flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-2 w-full md:w-auto">
                            <select name="satker_id"
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">Semua Satker</option>
                                @foreach($satkers as $satker)
                                    <option value="{{ $satker->id }}" {{ request('satker_id') == $satker->id ? 'selected' : '' }}>{{ $satker->nama_satker }}</option>
                                @endforeach
                            </select>
                            <select name="jenis_pengelolaan_id"
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">Semua Jenis</option>
                                @foreach($jenisPengelolaans as $jenis)
                                    <option value="{{ $jenis->id }}" {{ request('jenis_pengelolaan_id') == $jenis->id ? 'selected' : '' }}>{{ $jenis->nama_jenis }}</option>
                                @endforeach
                            </select>
                            <select name="status"
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
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
                            </select>
                            <button type="submit"
                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Filter</button>
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        No. Agenda</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Satker</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Jenis</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tgl Masuk</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Target</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($agendas as $agenda)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $agenda->nomor_agenda }}</td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900">{{ $agenda->satker->nama_satker }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900">{{ $agenda->jenisPengelolaan->nama_jenis }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $agenda->tanggal_masuk->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $agenda->tanggal_target->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $statusColors = [
                                                    'masuk' => 'bg-gray-100 text-gray-800',
                                                    'verifikasi' => 'bg-indigo-100 text-indigo-800',
                                                    'disposisi' => 'bg-yellow-100 text-yellow-800',
                                                    'proses' => 'bg-blue-100 text-blue-800',
                                                    'disetujui' => 'bg-green-100 text-green-800',
                                                    'ditolak' => 'bg-red-100 text-red-800',
                                                ];
                                                $colorClass = $statusColors[$agenda->status] ?? 'bg-gray-100 text-gray-800';
                                            @endphp
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $colorClass }}">
                                                {{ ucfirst($agenda->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('agenda.show', $agenda->id) }}"
                                                class="text-blue-600 hover:text-blue-900 mr-2">Lihat</a>
                                            <a href="{{ route('agenda.edit', $agenda->id) }}"
                                                class="text-indigo-600 hover:text-indigo-900 mr-2">Edit</a>

                                            <button type="button"
                                                onclick="openConfirmModal('delete-agenda', '{{ route('agenda.destroy', $agenda->id) }}')"
                                                class="text-red-600 hover:text-red-900">
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">Belum ada data Agenda.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $agendas->withQueryString()->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>

    <x-modal-confirm id="delete-agenda" title="Hapus Agenda"
        message="Apakah Anda yakin ingin menghapus agenda ini? Data yang dihapus tidak dapat dikembalikan."
        confirmText="Hapus" />
</x-app-layout>