<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                    role="alert">
                    <span class="block sm:inline">{!! session('success') !!}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Main Info -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <div class="flex justify-between items-start mb-4">
                                <h3 class="text-lg font-semibold text-gray-800">Informasi Agenda</h3>
                                @php
                                    $statusColors = [
                                        'masuk' => 'bg-gray-100 text-gray-800',
                                        'verifikasi' => 'bg-indigo-100 text-indigo-800',
                                        'disposisi' => 'bg-yellow-100 text-yellow-800',
                                        'proses' => 'bg-blue-100 text-blue-800',
                                        'disetujui' => 'bg-green-100 text-green-800',
                                        'ditolak' => 'bg-red-100 text-red-800',
                                        'dibatalkan' => 'bg-gray-300 text-gray-700',
                                    ];
                                @endphp
                                <span
                                    class="px-3 py-1 rounded-full text-sm font-semibold {{ $statusColors[$agenda->status] ?? 'bg-gray-100' }}">
                                    {{ ucfirst($agenda->status) }}
                                </span>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                <div>
                                    <p class="text-gray-500">Nomor Agenda</p>
                                    <p class="font-medium">{{ $agenda->nomor_agenda }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Satker</p>
                                    <p class="font-medium">{{ $agenda->satker->kode_satker }} -
                                        {{ $agenda->satker->nama_satker }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Jenis Pengelolaan</p>
                                    <p class="font-medium">{{ $agenda->jenisPengelolaan->nama_jenis }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500">PIC</p>
                                    <p class="font-medium">{{ $agenda->pic->name }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Tanggal Masuk</p>
                                    <p class="font-medium">{{ $agenda->tanggal_masuk->format('d F Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Target Penyelesaian</p>
                                    <p
                                        class="font-medium {{ $agenda->isOverdue && !in_array($agenda->status, ['disetujui', 'ditolak', 'dibatalkan']) ? 'text-red-600 font-bold' : '' }}">
                                        {{ $agenda->tanggal_target->format('d F Y') }}
                                        @if($agenda->isOverdue && !in_array($agenda->status, ['disetujui', 'ditolak', 'dibatalkan']))
                                            (Lewat Target)
                                        @endif
                                    </p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Durasi Berjalan</p>
                                    <p class="font-medium">{{ $agenda->durasi_hari }} Hari</p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Dibuat Oleh</p>
                                    <p class="font-medium">{{ $agenda->creator->name }}</p>
                                </div>
                            </div>

                            <div class="mt-6">
                                <p class="text-gray-500 mb-1">Catatan / Keterangan</p>
                                <div class="bg-gray-50 p-3 rounded text-gray-700 whitespace-pre-line">
                                    {{ $agenda->notes ?? '-' }}
                                </div>
                            </div>

                            <div class="mt-6">
                                <p class="text-gray-500 mb-2">Dokumen Lampiran</p>
                                @if($agenda->file_uploads && count($agenda->file_uploads) > 0)
                                    <ul class="space-y-2">
                                        @foreach($agenda->file_uploads as $file)
                                            <li class="flex items-center">
                                                <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13">
                                                    </path>
                                                </svg>
                                                <a href="{{ Storage::url($file['path']) }}" target="_blank"
                                                    class="text-blue-600 hover:underline text-sm">
                                                    {{ $file['original_name'] }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-sm text-gray-500 italic">Tidak ada dokumen dilampirkan.</p>
                                @endif
                            </div>

                            <div class="mt-8 flex space-x-3 border-t pt-6">
                                <a href="{{ route('agenda.edit', $agenda) }}"
                                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Edit Agenda
                                </a>
                                <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'update-status-modal')"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Update Status
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Timeline / History -->
                <div class="lg:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Riwayat Status</h3>

                            <div class="space-y-6">
                                @foreach($agenda->historyLogs->sortByDesc('created_at') as $log)
                                    <div class="flex gap-4">
                                        <div class="flex-shrink-0">
                                            <div class="flex items-center justify-center w-8 h-8 bg-blue-100 rounded-full">
                                                <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-semibold text-gray-900">
                                                {{ ucfirst($log->status_new) }}
                                            </h4>
                                            <time
                                                class="block mb-1 text-xs font-normal leading-none text-gray-400">{{ $log->created_at->format('d M Y, H:i') }}</time>
                                            <p class="mb-2 text-sm font-normal text-gray-500">
                                                Oleh: {{ $log->changer->name }}
                                            </p>
                                            @if($log->notes)
                                                <p
                                                    class="text-xs text-gray-600 italic bg-gray-50 p-2 rounded border border-gray-100">
                                                    "{{ $log->notes }}"
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Update Status Modal -->
    <x-modal name="update-status-modal" :show="false" focusable>
        <form method="POST" action="{{ route('agenda.update-status', $agenda) }}" class="p-6">
            @csrf

            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Update Status Agenda') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ __('Silakan pilih status baru untuk agenda ini.') }}
            </p>

            <div class="mt-6">
                <x-input-label for="status" value="{{ __('Status Baru') }}" />
                <select id="status" name="status"
                    class="mt-1 block w-3/4 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                    required>
                    <option value="masuk" {{ $agenda->status == 'masuk' ? 'selected' : '' }}>Masuk</option>
                    <option value="verifikasi" {{ $agenda->status == 'verifikasi' ? 'selected' : '' }}>Verifikasi</option>
                    <option value="disposisi" {{ $agenda->status == 'disposisi' ? 'selected' : '' }}>Disposisi</option>
                    <option value="proses" {{ $agenda->status == 'proses' ? 'selected' : '' }}>Proses</option>
                    <option value="disetujui" {{ $agenda->status == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                    <option value="ditolak" {{ $agenda->status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    <option value="dibatalkan" {{ $agenda->status == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>

            <div class="mt-6">
                <x-input-label for="notes" value="{{ __('Catatan Status') }}" />
                <textarea id="notes" name="notes"
                    class="mt-1 block w-3/4 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                    rows="3" placeholder="Tambahkan catatan perubahan status..."></textarea>
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Batal') }}
                </x-secondary-button>

                <x-primary-button class="ms-3">
                    {{ __('Simpan Status') }}
                </x-primary-button>
            </div>
        </form>
    </x-modal>
</x-app-layout>