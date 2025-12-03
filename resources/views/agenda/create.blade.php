<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form action="{{ route('agenda.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nomor Agenda (Read-only Preview) -->
                            <div>
                                <x-input-label for="nomor_agenda" :value="__('Nomor Agenda (Otomatis)')" />
                                <x-text-input id="nomor_agenda" class="block mt-1 w-full bg-gray-100" type="text"
                                    value="{{ $nomorAgendaPreview }}" disabled />
                                <p class="text-xs text-gray-500 mt-1">Nomor agenda akan digenerate saat disimpan.</p>
                            </div>

                            <!-- Tanggal Masuk -->
                            <div>
                                <x-input-label for="tanggal_masuk" :value="__('Tanggal Masuk')" />
                                <x-text-input id="tanggal_masuk" class="block mt-1 w-full" type="date"
                                    name="tanggal_masuk" :value="old('tanggal_masuk', date('Y-m-d'))" required />
                                <x-input-error :messages="$errors->get('tanggal_masuk')" class="mt-2" />
                            </div>

                            <!-- Satker -->
                            <div>
                                <x-input-label for="satker_id" :value="__('Satker')" />
                                <select id="satker_id" name="satker_id"
                                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full"
                                    required>
                                    <option value="">Pilih Satker</option>
                                    @foreach($satkers as $satker)
                                        <option value="{{ $satker->id }}" {{ old('satker_id') == $satker->id ? 'selected' : '' }}>{{ $satker->kode_satker }} - {{ $satker->nama_satker }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('satker_id')" class="mt-2" />
                            </div>

                            <!-- Jenis Pengelolaan -->
                            <div>
                                <x-input-label for="jenis_pengelolaan_id" :value="__('Jenis Pengelolaan')" />
                                <select id="jenis_pengelolaan_id" name="jenis_pengelolaan_id"
                                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full"
                                    required onchange="calculateTarget()">
                                    <option value="">Pilih Jenis</option>
                                    @foreach($jenisPengelolaans as $jenis)
                                        <option value="{{ $jenis->id }}" data-target="{{ $jenis->target_hari }}" {{ old('jenis_pengelolaan_id') == $jenis->id ? 'selected' : '' }}>
                                            {{ $jenis->nama_jenis }} ({{ $jenis->target_hari }} Hari)
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('jenis_pengelolaan_id')" class="mt-2" />
                            </div>

                            <!-- Tanggal Target (Calculated) -->
                            <div>
                                <x-input-label for="tanggal_target_display" :value="__('Estimasi Tanggal Target')" />
                                <x-text-input id="tanggal_target_display" class="block mt-1 w-full bg-gray-100"
                                    type="text" disabled />
                                <p class="text-xs text-gray-500 mt-1">Dihitung otomatis berdasarkan jenis pengelolaan.
                                </p>
                            </div>

                            <!-- PIC -->
                            <div>
                                <x-input-label for="pic_id" :value="__('PIC (Staff)')" />
                                <select id="pic_id" name="pic_id"
                                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full"
                                    required>
                                    <option value="">Pilih PIC</option>
                                    @foreach($pics as $pic)
                                        <option value="{{ $pic->id }}" {{ old('pic_id') == $pic->id ? 'selected' : '' }}>
                                            {{ $pic->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('pic_id')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="mt-6">
                            <x-input-label for="notes" :value="__('Catatan / Keterangan')" />
                            <textarea id="notes" name="notes"
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full"
                                rows="3">{{ old('notes') }}</textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>

                        <!-- File Upload -->
                        <div class="mt-6">
                            <x-input-label for="file_uploads" :value="__('Upload Dokumen (Max 5 file, @10MB)')" />
                            <input id="file_uploads" type="file" name="file_uploads[]" multiple class="block w-full text-sm text-gray-500
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-full file:border-0
                                file:text-sm file:font-semibold
                                file:bg-blue-50 file:text-blue-700
                                hover:file:bg-blue-100" />
                            <x-input-error :messages="$errors->get('file_uploads')" class="mt-2" />
                            <x-input-error :messages="$errors->get('file_uploads.*')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('agenda.index') }}"
                                class="text-gray-600 hover:text-gray-900 mr-4">Batal</a>
                            <x-primary-button>
                                {{ __('Simpan Agenda') }}
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script>
        function calculateTarget() {
            const jenisSelect = document.getElementById('jenis_pengelolaan_id');
            const tanggalMasukInput = document.getElementById('tanggal_masuk');
            const targetDisplay = document.getElementById('tanggal_target_display');

            const selectedOption = jenisSelect.options[jenisSelect.selectedIndex];
            const targetHari = parseInt(selectedOption.getAttribute('data-target')) || 0;
            const tanggalMasuk = new Date(tanggalMasukInput.value);

            if (targetHari > 0 && !isNaN(tanggalMasuk.getTime())) {
                const targetDate = new Date(tanggalMasuk);
                targetDate.setDate(targetDate.getDate() + targetHari);

                // Format to DD/MM/YYYY
                const day = String(targetDate.getDate()).padStart(2, '0');
                const month = String(targetDate.getMonth() + 1).padStart(2, '0');
                const year = targetDate.getFullYear();

                targetDisplay.value = `${day}/${month}/${year}`;
            } else {
                targetDisplay.value = '-';
            }
        }

        // Calculate on load and when date changes
        document.addEventListener('DOMContentLoaded', calculateTarget);
        document.getElementById('tanggal_masuk').addEventListener('change', calculateTarget);
    </script>
</x-app-layout>