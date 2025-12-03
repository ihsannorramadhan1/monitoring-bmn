<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Agenda;
use App\Models\AgendaHistoryLog;
use App\Models\JenisPengelolaan;
use App\Models\Satker;
use App\Models\User;
use Carbon\Carbon;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AgendaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Agenda::with(['satker', 'jenisPengelolaan', 'pic']);

        // Search by Nomor Agenda
        if ($request->filled('search')) {
            $query->where('nomor_agenda', 'ilike', "%{$request->search}%");
        }

        // Filter by Satker
        if ($request->filled('satker_id')) {
            $query->where('satker_id', $request->satker_id);
        }

        // Filter by Jenis Pengelolaan
        if ($request->filled('jenis_pengelolaan_id')) {
            $query->where('jenis_pengelolaan_id', $request->jenis_pengelolaan_id);
        }

        // Filter by Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by Date Range (Tanggal Masuk)
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_masuk', [$request->start_date, $request->end_date]);
        }

        $agendas = $query->orderBy('created_at', 'desc')->paginate(15);
        $satkers = Satker::where('status', 'aktif')->get();
        $jenisPengelolaans = JenisPengelolaan::where('status', 'aktif')->get();

        return view('agenda.index', compact('agendas', 'satkers', 'jenisPengelolaans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $satkers = Satker::where('status', 'aktif')->get();
        $jenisPengelolaans = JenisPengelolaan::where('status', 'aktif')->get();
        $pics = User::where('role', 'staff')->where('status', 'aktif')->get();

        // Generate Nomor Agenda Preview (AGD/YYYY/MM/XXXX)
        $year = date('Y');
        $month = date('m');
        $lastAgenda = Agenda::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('id', 'desc')
            ->first();

        $nextSequence = $lastAgenda ? (intval(substr($lastAgenda->nomor_agenda, -4)) + 1) : 1;
        $nomorAgendaPreview = "AGD/{$year}/{$month}/" . str_pad($nextSequence, 4, '0', STR_PAD_LEFT);

        return view('agenda.create', compact('satkers', 'jenisPengelolaans', 'pics', 'nomorAgendaPreview'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'satker_id' => 'required|exists:satkers,id',
            'jenis_pengelolaan_id' => 'required|exists:jenis_pengelolaans,id',
            'tanggal_masuk' => 'required|date',
            'pic_id' => 'required|exists:users,id',
            'file_uploads.*' => 'nullable|file|max:10240', // Max 10MB per file
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Generate Nomor Agenda
            $year = date('Y');
            $month = date('m');
            $lastAgenda = Agenda::whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->orderBy('id', 'desc')
                ->lockForUpdate()
                ->first();

            $nextSequence = $lastAgenda ? (intval(substr($lastAgenda->nomor_agenda, -4)) + 1) : 1;
            $nomorAgenda = "AGD/{$year}/{$month}/" . str_pad($nextSequence, 4, '0', STR_PAD_LEFT);

            // Calculate Tanggal Target
            $jenis = JenisPengelolaan::find($validated['jenis_pengelolaan_id']);
            $tanggalMasuk = Carbon::parse($validated['tanggal_masuk']);
            $tanggalTarget = $tanggalMasuk->copy()->addDays($jenis->target_hari);

            // Handle File Uploads
            $filePaths = [];
            if ($request->hasFile('file_uploads')) {
                foreach ($request->file('file_uploads') as $file) {
                    $path = $file->store('agenda_files', 'public');
                    $filePaths[] = [
                        'original_name' => $file->getClientOriginalName(),
                        'path' => $path
                    ];
                }
            }

            // Create Agenda
            $agenda = Agenda::create([
                'nomor_agenda' => $nomorAgenda,
                'satker_id' => $validated['satker_id'],
                'jenis_pengelolaan_id' => $validated['jenis_pengelolaan_id'],
                'tanggal_masuk' => $validated['tanggal_masuk'],
                'tanggal_target' => $tanggalTarget,
                'status' => 'masuk',
                'pic_id' => $validated['pic_id'],
                'file_uploads' => $filePaths,
                'notes' => $validated['notes'],
                'created_by' => auth()->id(),
            ]);

            // Create History Log
            AgendaHistoryLog::create([
                'agenda_id' => $agenda->id,
                'status_old' => null,
                'status_new' => 'masuk',
                'changed_by' => auth()->id(),
                'notes' => 'Agenda baru dibuat.',
            ]);

            DB::commit();

            return redirect()->route('agenda.index')
                ->with('success', "Agenda berhasil dibuat dengan nomor: <strong>{$nomorAgenda}</strong>");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat membuat agenda: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Agenda $agenda)
    {
        $agenda->load(['satker', 'jenisPengelolaan', 'pic', 'creator', 'historyLogs.changer']);
        return view('agenda.show', compact('agenda'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Agenda $agenda)
    {
        $satkers = Satker::where('status', 'aktif')->get();
        $jenisPengelolaans = JenisPengelolaan::where('status', 'aktif')->get();
        $pics = User::where('role', 'staff')->where('status', 'aktif')->get();

        return view('agenda.edit', compact('agenda', 'satkers', 'jenisPengelolaans', 'pics'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Agenda $agenda)
    {
        $validated = $request->validate([
            'satker_id' => 'required|exists:satkers,id',
            'jenis_pengelolaan_id' => 'required|exists:jenis_pengelolaans,id',
            'tanggal_masuk' => 'required|date',
            'pic_id' => 'required|exists:users,id',
            'file_uploads.*' => 'nullable|file|max:10240',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Recalculate Tanggal Target if Jenis or Tanggal Masuk changed
            $tanggalTarget = $agenda->tanggal_target;
            if ($agenda->jenis_pengelolaan_id != $validated['jenis_pengelolaan_id'] || $agenda->tanggal_masuk != $validated['tanggal_masuk']) {
                $jenis = JenisPengelolaan::find($validated['jenis_pengelolaan_id']);
                $tanggalMasuk = Carbon::parse($validated['tanggal_masuk']);
                $tanggalTarget = $tanggalMasuk->copy()->addDays($jenis->target_hari);
            }

            // Handle File Uploads (Append to existing)
            $filePaths = $agenda->file_uploads ?? [];
            if ($request->hasFile('file_uploads')) {
                foreach ($request->file('file_uploads') as $file) {
                    $path = $file->store('agenda_files', 'public');
                    $filePaths[] = [
                        'original_name' => $file->getClientOriginalName(),
                        'path' => $path
                    ];
                }
            }

            $agenda->update([
                'satker_id' => $validated['satker_id'],
                'jenis_pengelolaan_id' => $validated['jenis_pengelolaan_id'],
                'tanggal_masuk' => $validated['tanggal_masuk'],
                'tanggal_target' => $tanggalTarget,
                'pic_id' => $validated['pic_id'],
                'file_uploads' => $filePaths,
                'notes' => $validated['notes'],
            ]);

            // Log Update
            AgendaHistoryLog::create([
                'agenda_id' => $agenda->id,
                'status_old' => $agenda->status,
                'status_new' => $agenda->status,
                'changed_by' => auth()->id(),
                'notes' => 'Agenda diperbarui.',
            ]);

            DB::commit();

            return redirect()->route('agenda.show', $agenda)
                ->with('success', 'Agenda berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat memperbarui agenda: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Agenda $agenda)
    {
        // Soft delete implementation (assuming SoftDeletes trait is used or status update)
        // Since migration didn't add deleted_at, we'll use status 'dibatalkan' or similar if requested, 
        // but user asked for "Soft delete or set status to specific".
        // Let's set status to 'dibatalkan' as a logical delete for now, or just delete if it's a mistake.
        // Given the context of "Monitoring", usually we don't delete data.
        // Let's check the migration. It has timestamps but not softDeletes.
        // Let's set status to 'dibatalkan'.

        $agenda->update(['status' => 'dibatalkan']);

        AgendaHistoryLog::create([
            'agenda_id' => $agenda->id,
            'status_old' => $agenda->status,
            'status_new' => 'dibatalkan',
            'changed_by' => auth()->id(),
            'notes' => 'Agenda dibatalkan (dihapus).',
        ]);

        return redirect()->route('agenda.index')
            ->with('success', 'Agenda berhasil dibatalkan.');
    }

    /**
     * Update the status of the specified resource.
     */
    public function updateStatus(Request $request, Agenda $agenda)
    {
        $validated = $request->validate([
            'status' => 'required|in:masuk,verifikasi,disposisi,proses,disetujui,ditolak,dibatalkan',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $oldStatus = $agenda->status;
            $newStatus = $validated['status'];
            $tanggalSelesai = $agenda->tanggal_selesai;

            // Set Tanggal Selesai if approved/rejected
            if (in_array($newStatus, ['disetujui', 'ditolak']) && $oldStatus != $newStatus) {
                $tanggalSelesai = now();
            } elseif (!in_array($newStatus, ['disetujui', 'ditolak'])) {
                $tanggalSelesai = null; // Reset if moved back to process
            }

            $agenda->update([
                'status' => $newStatus,
                'tanggal_selesai' => $tanggalSelesai,
            ]);

            AgendaHistoryLog::create([
                'agenda_id' => $agenda->id,
                'status_old' => $oldStatus,
                'status_new' => $newStatus,
                'changed_by' => auth()->id(),
                'notes' => $validated['notes'] ?? 'Status diperbarui.',
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Status agenda berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui status: ' . $e->getMessage());
        }
    }
}
