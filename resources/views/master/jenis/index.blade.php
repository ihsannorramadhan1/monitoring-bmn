<x-app-layout>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                    role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                        <a href="{{ route('master.jenis-pengelolaan.create') }}"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow-sm transition duration-150 ease-in-out w-full md:w-auto text-center">
                            + Tambah Jenis
                        </a>

                        <form action="{{ route('master.jenis-pengelolaan.index') }}" method="GET"
                            class="flex flex-col md:flex-row gap-2 w-full md:w-auto">
                            <select name="kategori"
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full md:w-auto">
                                <option value="">Semua Kategori</option>
                                <option value="pemanfaatan" {{ request('kategori') == 'pemanfaatan' ? 'selected' : '' }}>
                                    Pemanfaatan</option>
                                <option value="pemindahtanganan" {{ request('kategori') == 'pemindahtanganan' ? 'selected' : '' }}>Pemindahtanganan</option>
                                <option value="penghapusan" {{ request('kategori') == 'penghapusan' ? 'selected' : '' }}>
                                    Penghapusan</option>
                                <option value="sewa" {{ request('kategori') == 'sewa' ? 'selected' : '' }}>Sewa</option>
                                <option value="lainnya" {{ request('kategori') == 'lainnya' ? 'selected' : '' }}>Lainnya
                                </option>
                            </select>
                            <select name="status"
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full md:w-auto">
                                <option value="">Semua Status</option>
                                <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif
                                </option>
                            </select>
                            <input type="text" name="search" placeholder="Cari Kode/Nama..."
                                value="{{ request('search') }}"
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full md:w-48">
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
                                        Nama Jenis</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-2/12">
                                        Kategori</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-2/12">
                                        Target SLA</th>
                                    <th
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-1/12">
                                        Status</th>
                                    <th
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-3/12">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($jenisPengelolaans as $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->kode }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $item->nama_jenis }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ ucfirst($item->kategori) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $item->target_hari }} Hari
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $item->status === 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ ucfirst($item->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                            <div class="flex justify-center items-center gap-2">
                                                <a href="{{ route('master.jenis-pengelolaan.edit', $item) }}"
                                                    class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 px-3 py-1 rounded-md hover:bg-indigo-100 transition duration-150">Edit</a>
                                                <form action="{{ route('master.jenis-pengelolaan.destroy', $item) }}"
                                                    method="POST" class="inline-block"
                                                    onsubmit="return confirm('Yakin ingin menonaktifkan jenis pengelolaan ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="text-red-600 hover:text-red-900 bg-red-50 px-3 py-1 rounded-md hover:bg-red-100 transition duration-150">Hapus</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">Tidak ada data jenis
                                            pengelolaan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $jenisPengelolaans->withQueryString()->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>