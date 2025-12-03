<x-app-layout>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Alert Success/Error handled by Toast Component now, but keeping inline for fallback or removing if redundant. 
                 The Toast component handles session('success'), so we can remove the inline alert if we want to rely on Toast. 
                 Let's keep it simple and remove the inline alert to avoid double notification if the Toast works. 
                 Actually, let's leave it for now or remove it? User asked for "Success alerts (green toast)". 
                 So I should probably remove the inline one to avoid duplication. -->

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                        <a href="{{ route('master.satker.create') }}"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow-sm transition duration-150 ease-in-out w-full md:w-auto text-center">
                            + Tambah Satker
                        </a>

                        <!-- Search & Filter -->
                        <form action="{{ route('master.satker.index') }}" method="GET"
                            class="flex flex-col md:flex-row gap-2 w-full md:w-auto">
                            <input type="text" name="search" placeholder="Cari Satker..."
                                value="{{ request('search') }}"
                                class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 w-full md:w-64">
                            <select name="status"
                                class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 w-full md:w-auto">
                                <option value="">Semua Status</option>
                                <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif
                                </option>
                            </select>
                            <button type="submit"
                                class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded shadow-sm transition duration-150 ease-in-out w-full md:w-auto">
                                Cari
                            </button>
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/12">
                                        Kode</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-3/12">
                                        Nama Satker</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-2/12">
                                        Instansi Induk</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-3/12">
                                        PIC</th>
                                    <th
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-1/12">
                                        Status</th>
                                    <th
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-2/12">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($satkers as $satker)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $satker->kode_satker }}</td>
                                        <td class="px-6 py-4">{{ $satker->nama_satker }}</td>
                                        <td class="px-6 py-4">{{ $satker->instansi_induk }}</td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $satker->pic_nama }}</div>
                                            <div class="text-sm text-gray-500">{{ $satker->pic_kontak }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $satker->status == 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ ucfirst($satker->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                            <div class="flex justify-center items-center gap-2">
                                                <a href="{{ route('master.satker.edit', $satker->id) }}"
                                                    class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 px-3 py-1 rounded-md hover:bg-indigo-100 transition duration-150">Edit</a>

                                                <button type="button"
                                                    onclick="openConfirmModal('delete-satker', '{{ route('master.satker.destroy', $satker->id) }}')"
                                                    class="text-red-600 hover:text-red-900 bg-red-50 px-3 py-1 rounded-md hover:bg-red-100 transition duration-150">
                                                    Delete
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">Belum ada data Satker.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $satkers->withQueryString()->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>

    <x-modal-confirm id="delete-satker" title="Hapus Satker"
        message="Apakah Anda yakin ingin menghapus data Satker ini? Data yang dihapus tidak dapat dikembalikan."
        confirmText="Hapus" />
</x-app-layout>