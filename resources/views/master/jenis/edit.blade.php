<x-app-layout>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form action="{{ route('master.jenis-pengelolaan.update', $jenisPengelolaan) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Kode -->
                            <div>
                                <x-input-label for="kode" :value="__('Kode')" />
                                <x-text-input id="kode" class="block mt-1 w-full" type="text" name="kode"
                                    :value="old('kode', $jenisPengelolaan->kode)" required />
                                <x-input-error :messages="$errors->get('kode')" class="mt-2" />
                            </div>

                            <!-- Nama Jenis -->
                            <div>
                                <x-input-label for="nama_jenis" :value="__('Nama Jenis')" />
                                <x-text-input id="nama_jenis" class="block mt-1 w-full" type="text" name="nama_jenis"
                                    :value="old('nama_jenis', $jenisPengelolaan->nama_jenis)" required />
                                <x-input-error :messages="$errors->get('nama_jenis')" class="mt-2" />
                            </div>

                            <!-- Kategori -->
                            <div>
                                <x-input-label for="kategori" :value="__('Kategori')" />
                                <select id="kategori" name="kategori"
                                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">
                                    <option value="pemanfaatan" {{ old('kategori', $jenisPengelolaan->kategori) == 'pemanfaatan' ? 'selected' : '' }}>Pemanfaatan
                                    </option>
                                    <option value="pemindahtanganan" {{ old('kategori', $jenisPengelolaan->kategori) == 'pemindahtanganan' ? 'selected' : '' }}>
                                        Pemindahtanganan</option>
                                    <option value="penghapusan" {{ old('kategori', $jenisPengelolaan->kategori) == 'penghapusan' ? 'selected' : '' }}>Penghapusan
                                    </option>
                                    <option value="sewa" {{ old('kategori', $jenisPengelolaan->kategori) == 'sewa' ? 'selected' : '' }}>Sewa</option>
                                    <option value="lainnya" {{ old('kategori', $jenisPengelolaan->kategori) == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                                <x-input-error :messages="$errors->get('kategori')" class="mt-2" />
                            </div>

                            <!-- Target Hari -->
                            <div>
                                <x-input-label for="target_hari" :value="__('Target SLA (Hari)')" />
                                <x-text-input id="target_hari" class="block mt-1 w-full" type="number"
                                    name="target_hari" :value="old('target_hari', $jenisPengelolaan->target_hari)"
                                    required min="1" />
                                <x-input-error :messages="$errors->get('target_hari')" class="mt-2" />
                            </div>

                            <!-- Status -->
                            <div>
                                <x-input-label for="status" :value="__('Status')" />
                                <select id="status" name="status"
                                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">
                                    <option value="aktif" {{ old('status', $jenisPengelolaan->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="nonaktif" {{ old('status', $jenisPengelolaan->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                </select>
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        <div class="mt-6">
                            <x-input-label for="deskripsi" :value="__('Deskripsi')" />
                            <textarea id="deskripsi" name="deskripsi"
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full"
                                rows="3">{{ old('deskripsi', $jenisPengelolaan->deskripsi) }}</textarea>
                            <x-input-error :messages="$errors->get('deskripsi')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6 gap-4">
                            <a href="{{ route('master.jenis-pengelolaan.index') }}"
                                class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition ease-in-out duration-150 font-medium">
                                Batal
                            </a>
                            <x-primary-button>
                                {{ __('Perbarui') }}
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>