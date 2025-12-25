<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Display the reporting dashboard.
     */
    public function index()
    {
        return view('reports.index');
    }

    public function daftarAgenda(Request $request)
    {
        $satkers = \App\Models\Satker::where('status', 'aktif')->get();
        $jenisPengelolaans = \App\Models\JenisPengelolaan::where('status', 'aktif')->get();

        $query = \App\Models\Agenda::with(['satker', 'jenisPengelolaan', 'pic']);

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_masuk', [$request->start_date, $request->end_date]);
        }

        if ($request->filled('satker_id')) {
            $query->where('satker_id', $request->satker_id);
        }

        if ($request->filled('jenis_pengelolaan_id')) {
            $query->where('jenis_pengelolaan_id', $request->jenis_pengelolaan_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $agendas = $query->orderBy('tanggal_masuk', 'desc')->get();

        return view('reports.daftar-agenda', compact('agendas', 'satkers', 'jenisPengelolaans'));
    }

    public function daftarAgendaPdf(Request $request)
    {
        $query = \App\Models\Agenda::with(['satker', 'jenisPengelolaan', 'pic']);

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_masuk', [$request->start_date, $request->end_date]);
        }

        if ($request->filled('satker_id')) {
            $query->where('satker_id', $request->satker_id);
        }

        if ($request->filled('jenis_pengelolaan_id')) {
            $query->where('jenis_pengelolaan_id', $request->jenis_pengelolaan_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $agendas = $query->orderBy('tanggal_masuk', 'desc')->get();

        $orientation = 'landscape';
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.pdf.daftar-agenda', compact('agendas', 'orientation'))
            ->setPaper('a4', $orientation);

        return $pdf->stream('laporan-daftar-agenda.pdf');
    }

    public function daftarAgendaExcel(Request $request)
    {
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\AgendaExport($request), 'laporan-daftar-agenda.xlsx');
    }

    public function statusPersetujuan(Request $request)
    {
        $query = \App\Models\Agenda::selectRaw('status, count(*) as total')
            ->groupBy('status');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_masuk', [$request->start_date, $request->end_date]);
        }

        $summary = $query->get();
        $total = $summary->sum('total');

        $data = $summary->map(function ($item) use ($total, $request) {
            $details = \App\Models\Agenda::with('satker')
                ->where('status', $item->status);

            if ($request->filled('start_date') && $request->filled('end_date')) {
                $details->whereBetween('tanggal_masuk', [$request->start_date, $request->end_date]);
            }

            return [
                'status' => $item->status,
                'total' => $item->total,
                'percentage' => $total > 0 ? round(($item->total / $total) * 100, 2) : 0,
                'details' => $details->get()
            ];
        });

        return view('reports.status-persetujuan', compact('data', 'total'));
    }

    public function statusPersetujuanPdf(Request $request)
    {
        $query = \App\Models\Agenda::selectRaw('status, count(*) as total')
            ->groupBy('status');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_masuk', [$request->start_date, $request->end_date]);
        }

        $summary = $query->get();
        $total = $summary->sum('total');

        $data = $summary->map(function ($item) use ($total, $request) {
            $details = \App\Models\Agenda::with('satker')
                ->where('status', $item->status);

            if ($request->filled('start_date') && $request->filled('end_date')) {
                $details->whereBetween('tanggal_masuk', [$request->start_date, $request->end_date]);
            }

            return [
                'status' => $item->status,
                'total' => $item->total,
                'percentage' => $total > 0 ? round(($item->total / $total) * 100, 2) : 0,
                'details' => $details->get()
            ];
        });

        $orientation = 'portrait';
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.pdf.status-persetujuan', compact('data', 'total', 'orientation'))
            ->setPaper('a4', $orientation);

        return $pdf->stream('laporan-status-persetujuan.pdf');
    }

    public function statusPersetujuanExcel(Request $request)
    {
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\StatusPersetujuanExport($request), 'laporan-status-persetujuan.xlsx');
    }

    public function durasiProses(Request $request)
    {
        $jenisPengelolaans = \App\Models\JenisPengelolaan::where('status', 'aktif')->get();
        $data = collect();
        $overdueAgendas = collect();

        foreach ($jenisPengelolaans as $jenis) {
            $query = \App\Models\Agenda::where('jenis_pengelolaan_id', $jenis->id)
                ->whereIn('status', ['disetujui', 'ditolak']);

            if ($request->filled('start_date') && $request->filled('end_date')) {
                $query->whereBetween('tanggal_masuk', [$request->start_date, $request->end_date]);
            }

            $agendas = $query->get();
            $avgDurasi = $agendas->avg('durasi_hari');
            $avgDurasi = $avgDurasi ? round($avgDurasi, 1) : 0;

            $variance = $avgDurasi - $jenis->target_hari;
            $status = $variance <= 0 ? 'On Time' : 'Overdue';

            $data->push([
                'jenis' => $jenis->nama_jenis,
                'target' => $jenis->target_hari,
                'avg_actual' => $avgDurasi,
                'variance' => round($variance, 1),
                'status' => $status,
            ]);
        }

        // Get list of overdue agendas
        $overdueQuery = \App\Models\Agenda::with(['satker', 'jenisPengelolaan'])
            ->whereIn('status', ['disetujui', 'ditolak'])
            ->whereRaw('durasi_hari > (SELECT target_hari FROM jenis_pengelolaans WHERE jenis_pengelolaans.id = agendas.jenis_pengelolaan_id)');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $overdueQuery->whereBetween('tanggal_masuk', [$request->start_date, $request->end_date]);
        }

        $overdueAgendas = $overdueQuery->orderBy('durasi_hari', 'desc')->limit(10)->get();

        return view('reports.durasi-proses', compact('data', 'overdueAgendas'));
    }

    public function durasiProsesPdf(Request $request)
    {
        $jenisPengelolaans = \App\Models\JenisPengelolaan::where('status', 'aktif')->get();
        $data = collect();

        foreach ($jenisPengelolaans as $jenis) {
            $query = \App\Models\Agenda::where('jenis_pengelolaan_id', $jenis->id)
                ->whereIn('status', ['disetujui', 'ditolak']);

            if ($request->filled('start_date') && $request->filled('end_date')) {
                $query->whereBetween('tanggal_masuk', [$request->start_date, $request->end_date]);
            }

            $agendas = $query->get();
            $avgDurasi = $agendas->avg('durasi_hari');
            $avgDurasi = $avgDurasi ? round($avgDurasi, 1) : 0;

            $variance = $avgDurasi - $jenis->target_hari;
            $status = $variance <= 0 ? 'On Time' : 'Overdue';

            $data->push([
                'jenis' => $jenis->nama_jenis,
                'target' => $jenis->target_hari,
                'avg_actual' => $avgDurasi,
                'variance' => round($variance, 1),
                'status' => $status,
            ]);
        }

        $overdueQuery = \App\Models\Agenda::with(['satker', 'jenisPengelolaan'])
            ->whereIn('status', ['disetujui', 'ditolak'])
            ->whereRaw('durasi_hari > (SELECT target_hari FROM jenis_pengelolaans WHERE jenis_pengelolaans.id = agendas.jenis_pengelolaan_id)');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $overdueQuery->whereBetween('tanggal_masuk', [$request->start_date, $request->end_date]);
        }

        $overdueAgendas = $overdueQuery->orderBy('durasi_hari', 'desc')->limit(20)->get();

        $orientation = 'portrait';
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.pdf.durasi-proses', compact('data', 'overdueAgendas', 'orientation'))
            ->setPaper('a4', $orientation);

        return $pdf->stream('laporan-analisis-durasi.pdf');
    }

    public function durasiProsesExcel(Request $request)
    {
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\DurasiProsesExport($request), 'laporan-analisis-durasi.xlsx');
    }

    public function performanceSatker(Request $request)
    {
        $satkers = \App\Models\Satker::where('status', 'aktif')->get();
        $data = collect();

        foreach ($satkers as $satker) {
            $query = \App\Models\Agenda::where('satker_id', $satker->id);

            if ($request->filled('start_date') && $request->filled('end_date')) {
                $query->whereBetween('tanggal_masuk', [$request->start_date, $request->end_date]);
            }

            $agendas = $query->get();
            $total = $agendas->count();

            $completed = $agendas->where('status', 'disetujui')->count();
            $rejected = $agendas->where('status', 'ditolak')->count();
            $pending = $agendas->whereIn('status', ['masuk', 'verifikasi', 'disposisi', 'proses'])->count();

            $completedAgendas = $agendas->whereIn('status', ['disetujui', 'ditolak']);
            $avgDuration = $completedAgendas->avg('durasi_hari');
            $avgDuration = $avgDuration ? round($avgDuration, 1) : 0;

            $score = $total > 0 ? round(($completed / $total) * 100, 1) : 0;

            $data->push([
                'nama_satker' => $satker->nama_satker,
                'total' => $total,
                'completed' => $completed,
                'rejected' => $rejected,
                'pending' => $pending,
                'avg_duration' => $avgDuration,
                'score' => $score,
            ]);
        }

        // Rank by Score (desc), then by Total (desc)
        $rankedData = $data->sortByDesc([
            ['score', 'desc'],
            ['total', 'desc'],
        ])->values();

        return view('reports.performance-satker', compact('rankedData'));
    }

    public function performanceSatkerPdf(Request $request)
    {
        $satkers = \App\Models\Satker::where('status', 'aktif')->get();
        $data = collect();

        foreach ($satkers as $satker) {
            $query = \App\Models\Agenda::where('satker_id', $satker->id);

            if ($request->filled('start_date') && $request->filled('end_date')) {
                $query->whereBetween('tanggal_masuk', [$request->start_date, $request->end_date]);
            }

            $agendas = $query->get();
            $total = $agendas->count();

            $completed = $agendas->where('status', 'disetujui')->count();
            $rejected = $agendas->where('status', 'ditolak')->count();
            $pending = $agendas->whereIn('status', ['masuk', 'verifikasi', 'disposisi', 'proses'])->count();

            $completedAgendas = $agendas->whereIn('status', ['disetujui', 'ditolak']);
            $avgDuration = $completedAgendas->avg('durasi_hari');
            $avgDuration = $avgDuration ? round($avgDuration, 1) : 0;

            $score = $total > 0 ? round(($completed / $total) * 100, 1) : 0;

            $data->push([
                'nama_satker' => $satker->nama_satker,
                'total' => $total,
                'completed' => $completed,
                'rejected' => $rejected,
                'pending' => $pending,
                'avg_duration' => $avgDuration,
                'score' => $score,
            ]);
        }

        $rankedData = $data->sortByDesc([
            ['score', 'desc'],
            ['total', 'desc'],
        ])->values();

        $orientation = 'portrait';
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.pdf.performance-satker', compact('rankedData', 'orientation'))
            ->setPaper('a4', $orientation);

        return $pdf->stream('laporan-performance-satker.pdf');
    }

    public function performanceSatkerExcel(Request $request)
    {
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\PerformanceSatkerExport($request), 'laporan-performance-satker.xlsx');
    }

    public function summaryBulanan(Request $request)
    {
        $startDate = $request->start_date ? \Carbon\Carbon::parse($request->start_date) : \Carbon\Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? \Carbon\Carbon::parse($request->end_date) : \Carbon\Carbon::now()->endOfMonth();

        // Previous Period
        $prevStartDate = $startDate->copy()->subMonth();
        $prevEndDate = $endDate->copy()->subMonth();

        // 1. KPIs
        $currentAgendas = \App\Models\Agenda::whereBetween('tanggal_masuk', [$startDate, $endDate])->get();
        $prevAgendas = \App\Models\Agenda::whereBetween('tanggal_masuk', [$prevStartDate, $prevEndDate])->get();

        $kpi = [
            'total' => $currentAgendas->count(),
            'completed' => $currentAgendas->whereIn('status', ['disetujui', 'ditolak'])->count(),
            'pending' => $currentAgendas->whereIn('status', ['masuk', 'verifikasi', 'disposisi', 'proses'])->count(),
            'avg_duration' => $currentAgendas->whereIn('status', ['disetujui', 'ditolak'])->avg('durasi_hari') ?? 0,
        ];

        $prevKpi = [
            'total' => $prevAgendas->count(),
            'completed' => $prevAgendas->whereIn('status', ['disetujui', 'ditolak'])->count(),
            'avg_duration' => $prevAgendas->whereIn('status', ['disetujui', 'ditolak'])->avg('durasi_hari') ?? 0,
        ];

        // Trends (% change)
        $trends = [
            'total' => $prevKpi['total'] > 0 ? round((($kpi['total'] - $prevKpi['total']) / $prevKpi['total']) * 100, 1) : 0,
            'completed' => $prevKpi['completed'] > 0 ? round((($kpi['completed'] - $prevKpi['completed']) / $prevKpi['completed']) * 100, 1) : 0,
            'avg_duration' => $prevKpi['avg_duration'] > 0 ? round((($kpi['avg_duration'] - $prevKpi['avg_duration']) / $prevKpi['avg_duration']) * 100, 1) : 0,
        ];

        // 2. Top 5 Satker
        $topSatkers = \App\Models\Agenda::whereBetween('tanggal_masuk', [$startDate, $endDate])
            ->select('satker_id', \DB::raw('count(*) as total'))
            ->groupBy('satker_id')
            ->orderByDesc('total')
            ->take(5)
            ->with('satker')
            ->get();

        // 3. Bottlenecks
        // Most Delayed Jenis
        $delayedJenis = \App\Models\Agenda::whereBetween('tanggal_masuk', [$startDate, $endDate])
            ->whereIn('status', ['disetujui', 'ditolak'])
            ->whereRaw('durasi_hari > (SELECT target_hari FROM jenis_pengelolaans WHERE jenis_pengelolaans.id = agendas.jenis_pengelolaan_id)')
            ->select('jenis_pengelolaan_id', \DB::raw('count(*) as total'))
            ->groupBy('jenis_pengelolaan_id')
            ->orderByDesc('total')
            ->first();

        $mostDelayedJenis = $delayedJenis ? \App\Models\JenisPengelolaan::find($delayedJenis->jenis_pengelolaan_id) : null;

        // Longest Pending
        $longestPending = \App\Models\Agenda::whereIn('status', ['masuk', 'verifikasi', 'disposisi', 'proses'])
            ->orderBy('tanggal_masuk', 'asc')
            ->first();

        // 4. Charts Data
        // Status Distribution
        $statusDist = [
            'Selesai' => $kpi['completed'],
            'Pending' => $kpi['pending'],
            'Ditolak' => $currentAgendas->where('status', 'ditolak')->count(),
        ];

        // Monthly Trend (Last 6 Months)
        $monthlyTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $monthStart = \Carbon\Carbon::now()->subMonths($i)->startOfMonth();
            $monthEnd = \Carbon\Carbon::now()->subMonths($i)->endOfMonth();
            $count = \App\Models\Agenda::whereBetween('tanggal_masuk', [$monthStart, $monthEnd])->count();
            $monthlyTrend[$monthStart->format('M Y')] = $count;
        }

        return view('reports.executive-summary', compact('kpi', 'trends', 'topSatkers', 'mostDelayedJenis', 'longestPending', 'statusDist', 'monthlyTrend'));
    }

    public function summaryBulananPdf(Request $request)
    {
        // Logic duplicated for PDF (ideally refactor to service, but keeping simple for now)
        $startDate = $request->start_date ? \Carbon\Carbon::parse($request->start_date) : \Carbon\Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? \Carbon\Carbon::parse($request->end_date) : \Carbon\Carbon::now()->endOfMonth();

        $prevStartDate = $startDate->copy()->subMonth();
        $prevEndDate = $endDate->copy()->subMonth();

        $currentAgendas = \App\Models\Agenda::whereBetween('tanggal_masuk', [$startDate, $endDate])->get();
        $prevAgendas = \App\Models\Agenda::whereBetween('tanggal_masuk', [$prevStartDate, $prevEndDate])->get();

        $kpi = [
            'total' => $currentAgendas->count(),
            'completed' => $currentAgendas->whereIn('status', ['disetujui', 'ditolak'])->count(),
            'pending' => $currentAgendas->whereIn('status', ['masuk', 'verifikasi', 'disposisi', 'proses'])->count(),
            'avg_duration' => $currentAgendas->whereIn('status', ['disetujui', 'ditolak'])->avg('durasi_hari') ?? 0,
        ];

        $prevKpi = [
            'total' => $prevAgendas->count(),
            'completed' => $prevAgendas->whereIn('status', ['disetujui', 'ditolak'])->count(),
            'avg_duration' => $prevAgendas->whereIn('status', ['disetujui', 'ditolak'])->avg('durasi_hari') ?? 0,
        ];

        $trends = [
            'total' => $prevKpi['total'] > 0 ? round((($kpi['total'] - $prevKpi['total']) / $prevKpi['total']) * 100, 1) : 0,
            'completed' => $prevKpi['completed'] > 0 ? round((($kpi['completed'] - $prevKpi['completed']) / $prevKpi['completed']) * 100, 1) : 0,
            'avg_duration' => $prevKpi['avg_duration'] > 0 ? round((($kpi['avg_duration'] - $prevKpi['avg_duration']) / $prevKpi['avg_duration']) * 100, 1) : 0,
        ];

        $topSatkers = \App\Models\Agenda::whereBetween('tanggal_masuk', [$startDate, $endDate])
            ->select('satker_id', \DB::raw('count(*) as total'))
            ->groupBy('satker_id')
            ->orderByDesc('total')
            ->take(5)
            ->with('satker')
            ->get();

        $delayedJenis = \App\Models\Agenda::whereBetween('tanggal_masuk', [$startDate, $endDate])
            ->whereIn('status', ['disetujui', 'ditolak'])
            ->whereRaw('durasi_hari > (SELECT target_hari FROM jenis_pengelolaans WHERE jenis_pengelolaans.id = agendas.jenis_pengelolaan_id)')
            ->select('jenis_pengelolaan_id', \DB::raw('count(*) as total'))
            ->groupBy('jenis_pengelolaan_id')
            ->orderByDesc('total')
            ->first();

        $mostDelayedJenis = $delayedJenis ? \App\Models\JenisPengelolaan::find($delayedJenis->jenis_pengelolaan_id) : null;

        $longestPending = \App\Models\Agenda::whereIn('status', ['masuk', 'verifikasi', 'disposisi', 'proses'])
            ->orderBy('tanggal_masuk', 'asc')
            ->first();

        $orientation = 'portrait';
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.pdf.executive-summary', compact('kpi', 'trends', 'topSatkers', 'mostDelayedJenis', 'longestPending', 'orientation'))
            ->setPaper('a4', $orientation);

        return $pdf->stream('laporan-executive-summary.pdf');
    }

    public function summaryBulananExcel(Request $request)
    {
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\ExecutiveSummaryExport($request), 'laporan-executive-summary.xlsx');
    }
}
