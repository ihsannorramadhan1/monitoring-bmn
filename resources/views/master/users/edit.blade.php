<x-app-layout>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form action="{{ route('master.users.update', $user) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- NIP -->
                            <div>
                                <x-input-label for="nip" :value="__('NIP')" />
                                <x-text-input id="nip" class="block mt-1 w-full" type="text" name="nip"
                                    :value="old('nip', $user->nip)" required />
                                <x-input-error :messages="$errors->get('nip')" class="mt-2" />
                            </div>

                            <!-- Nama -->
                            <div>
                                <x-input-label for="name" :value="__('Nama Lengkap')" />
                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                                    :value="old('name', $user->name)" required />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <!-- Email -->
                            <div>
                                <x-input-label for="email" :value="__('Email')" />
                                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                                    :value="old('email', $user->email)" required />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <!-- Role -->
                            <div>
                                <x-input-label for="role" :value="__('Role')" />
                                <select id="role" name="role"
                                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full"
                                    {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>
                                        Admin</option>
                                    <option value="staff" {{ old('role', $user->role) == 'staff' ? 'selected' : '' }}>
                                        Staff</option>
                                    <option value="viewer" {{ old('role', $user->role) == 'viewer' ? 'selected' : '' }}>
                                        Viewer</option>
                                </select>
                                @if($user->id === auth()->id())
                                    <input type="hidden" name="role" value="{{ $user->role }}">
                                    <p class="text-xs text-gray-500 mt-1">Anda tidak dapat mengubah role Anda sendiri.</p>
                                @endif
                                <x-input-error :messages="$errors->get('role')" class="mt-2" />
                            </div>

                            <!-- Divisi -->
                            <div>
                                <x-input-label for="divisi" :value="__('Divisi')" />
                                <x-text-input id="divisi" class="block mt-1 w-full" type="text" name="divisi"
                                    :value="old('divisi', $user->divisi)" />
                                <x-input-error :messages="$errors->get('divisi')" class="mt-2" />
                            </div>

                            <!-- Status -->
                            <div>
                                <x-input-label for="status" :value="__('Status')" />
                                <select id="status" name="status"
                                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full"
                                    {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                    <option value="aktif" {{ old('status', $user->status) == 'aktif' ? 'selected' : '' }}>
                                        Aktif</option>
                                    <option value="nonaktif" {{ old('status', $user->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                </select>
                                @if($user->id === auth()->id())
                                    <input type="hidden" name="status" value="{{ $user->status }}">
                                    <p class="text-xs text-gray-500 mt-1">Anda tidak dapat menonaktifkan akun Anda sendiri.
                                    </p>
                                @endif
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6 gap-4">
                            <a href="{{ route('master.users.index') }}"
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