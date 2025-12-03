<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // KPIs
        $totalAgenda = \App\Models\Agenda::count();
        $agendaPending = \App\Models\Agenda::whereNotIn('status', ['disetujui', 'ditolak'])->count();
        $agendaSelesai = \App\Models\Agenda::where('status', 'disetujui')->count();
        $avgDurasi = \App\Models\Agenda::whereNotNull('durasi_hari')->avg('durasi_hari');
        $avgDurasi = number_format($avgDurasi, 1);

        // Status Distribution (Pie Chart)
        $statusDistribution = \App\Models\Agenda::select('status', \DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        // Monthly Trend (Line Chart) - Last 6 months
        $monthlyTrend = \App\Models\Agenda::select(
            \DB::raw("TO_CHAR(tanggal_masuk, 'YYYY-MM') as month"),
            \DB::raw('count(*) as total')
        )
            ->where('tanggal_masuk', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $trendLabels = $monthlyTrend->pluck('month')->map(function ($date) {
            return \Carbon\Carbon::createFromFormat('Y-m', $date)->format('M Y');
        })->toArray();
        $trendData = $monthlyTrend->pluck('total')->toArray();

        // Top 5 Satker
        $topSatker = \App\Models\Agenda::select('satker_id', \DB::raw('count(*) as total'))
            ->with('satker')
            ->groupBy('satker_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'totalAgenda',
            'agendaPending',
            'agendaSelesai',
            'avgDurasi',
            'statusDistribution',
            'trendLabels',
            'trendData',
            'topSatker'
        ));
    }
}
