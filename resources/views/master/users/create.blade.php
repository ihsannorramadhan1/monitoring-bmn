<x-app-layout>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
                        <p class="text-sm text-blue-700">
                            Password akan digenerate secara otomatis dan ditampilkan setelah user berhasil dibuat.
                        </p>
                    </div>

                    <form action="{{ route('master.users.store') }}" method="POST">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- NIP -->
                            <div>
                                <x-input-label for="nip" :value="__('NIP')" />
                                <x-text-input id="nip" class="block mt-1 w-full" type="text" name="nip"
                                    :value="old('nip')" required autofocus />
                                <x-input-error :messages="$errors->get('nip')" class="mt-2" />
                            </div>

                            <!-- Nama -->
                            <div>
                                <x-input-label for="name" :value="__('Nama Lengkap')" />
                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                                    :value="old('name')" required />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <!-- Email -->
                            <div>
                                <x-input-label for="email" :value="__('Email')" />
                                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                                    :value="old('email')" required />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <!-- Role -->
                            <div>
                                <x-input-label for="role" :value="__('Role')" />
                                <select id="role" name="role"
                                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">
                                    <option value="">Pilih Role</option>
                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>Staff</option>
                                    <option value="viewer" {{ old('role') == 'viewer' ? 'selected' : '' }}>Viewer</option>
                                </select>
                                <x-input-error :messages="$errors->get('role')" class="mt-2" />
                            </div>

                            <!-- Divisi -->
                            <div>
                                <x-input-label for="divisi" :value="__('Divisi')" />
                                <x-text-input id="divisi" class="block mt-1 w-full" type="text" name="divisi"
                                    :value="old('divisi')" />
                                <x-input-error :messages="$errors->get('divisi')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('master.users.index') }}"
                                class="text-gray-600 hover:text-gray-900 mr-4">Batal</a>
                            <x-primary-button>
                                {{ __('Simpan') }}
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>