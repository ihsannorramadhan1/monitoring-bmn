<x-app-layout>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form action="{{ route('master.satker.update', $satker) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Kode Satker -->
                            <div>
                                <x-input-label for="kode_satker" :value="__('Kode Satker')" />
                                <x-text-input id="kode_satker" class="block mt-1 w-full" type="text" name="kode_satker"
                                    :value="old('kode_satker', $satker->kode_satker)" required />
                                <x-input-error :messages="$errors->get('kode_satker')" class="mt-2" />
                            </div>

                            <!-- Nama Satker -->
                            <div>
                                <x-input-label for="nama_satker" :value="__('Nama Satker')" />
                                <x-text-input id="nama_satker" class="block mt-1 w-full" type="text" name="nama_satker"
                                    :value="old('nama_satker', $satker->nama_satker)" required />
                                <x-input-error :messages="$errors->get('nama_satker')" class="mt-2" />
                            </div>

                            <!-- Instansi Induk -->
                            <div>
                                <x-input-label for="instansi_induk" :value="__('Instansi Induk')" />
                                <x-text-input id="instansi_induk" class="block mt-1 w-full" type="text"
                                    name="instansi_induk" :value="old('instansi_induk', $satker->instansi_induk)" />
                                <x-input-error :messages="$errors->get('instansi_induk')" class="mt-2" />
                            </div>

                            <!-- Email -->
                            <div>
                                <x-input-label for="email" :value="__('Email')" />
                                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                                    :value="old('email', $satker->email)" />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <!-- PIC Nama -->
                            <div>
                                <x-input-label for="pic_nama" :value="__('Nama PIC')" />
                                <x-text-input id="pic_nama" class="block mt-1 w-full" type="text" name="pic_nama"
                                    :value="old('pic_nama', $satker->pic_nama)" />
                                <x-input-error :messages="$errors->get('pic_nama')" class="mt-2" />
                            </div>

                            <!-- PIC Kontak -->
                            <div>
                                <x-input-label for="pic_kontak" :value="__('Kontak PIC (HP/WA)')" />
                                <x-text-input id="pic_kontak" class="block mt-1 w-full" type="text" name="pic_kontak"
                                    :value="old('pic_kontak', $satker->pic_kontak)" />
                                <x-input-error :messages="$errors->get('pic_kontak')" class="mt-2" />
                            </div>

                            <!-- Status -->
                            <div>
                                <x-input-label for="status" :value="__('Status')" />
                                <select id="status" name="status"
                                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">
                                    <option value="aktif" {{ old('status', $satker->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="nonaktif" {{ old('status', $satker->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                </select>
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Alamat -->
                        <div class="mt-6">
                            <x-input-label for="alamat" :value="__('Alamat')" />
                            <textarea id="alamat" name="alamat"
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full"
                                rows="3">{{ old('alamat', $satker->alamat) }}</textarea>
                            <x-input-error :messages="$errors->get('alamat')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6 gap-4">
                            <a href="{{ route('master.satker.index') }}"
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