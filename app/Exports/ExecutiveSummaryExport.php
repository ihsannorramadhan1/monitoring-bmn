<?php

namespace App\Exports;

use App\Models\Agenda;
use App\Models\Satker;
use App\Models\JenisPengelolaan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class ExecutiveSummaryExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // For Excel, we'll output a summary of KPIs and Top Lists.
        // This is a simplified version of the full dashboard for Excel.

        $startDate = $this->request->start_date ? Carbon::parse($this->request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $this->request->end_date ? Carbon::parse($this->request->end_date) : Carbon::now()->endOfMonth();

        $data = collect();

        // 1. KPIs
        $agendas = Agenda::whereBetween('tanggal_masuk', [$startDate, $endDate])->get();
        $total = $agendas->count();
        $completed = $agendas->whereIn('status', ['disetujui', 'ditolak'])->count();
        $pending = $agendas->whereIn('status', ['masuk', 'verifikasi', 'disposisi', 'proses'])->count();

        $completedAgendas = $agendas->whereIn('status', ['disetujui', 'ditolak']);
        $avgDuration = $completedAgendas->avg('durasi_hari');
        $avgDuration = $avgDuration ? round($avgDuration, 1) : 0;

        $data->push(['Section' => 'KPI Summary', 'Metric' => 'Total Agenda', 'Value' => $total]);
        $data->push(['Section' => 'KPI Summary', 'Metric' => 'Completed', 'Value' => $completed]);
        $data->push(['Section' => 'KPI Summary', 'Metric' => 'Pending', 'Value' => $pending]);
        $data->push(['Section' => 'KPI Summary', 'Metric' => 'Avg Duration (Days)', 'Value' => $avgDuration]);

        // 2. Top Satker
        $topSatkers = Agenda::whereBetween('tanggal_masuk', [$startDate, $endDate])
            ->select('satker_id', \DB::raw('count(*) as total'))
            ->groupBy('satker_id')
            ->orderByDesc('total')
            ->take(5)
            ->with('satker')
            ->get();

        foreach ($topSatkers as $index => $item) {
            $data->push([
                'Section' => 'Top 5 Satker',
                'Metric' => 'Rank ' . ($index + 1) . ': ' . $item->satker->nama_satker,
                'Value' => $item->total . ' Agendas'
            ]);
        }

        // 3. Bottlenecks - Most Delayed Jenis
        // Simplified for Excel: Just listing top delayed
        $delayed = Agenda::whereBetween('tanggal_masuk', [$startDate, $endDate])
            ->whereIn('status', ['disetujui', 'ditolak'])
            ->whereRaw('durasi_hari > (SELECT target_hari FROM jenis_pengelolaans WHERE jenis_pengelolaans.id = agendas.jenis_pengelolaan_id)')
            ->select('jenis_pengelolaan_id', \DB::raw('count(*) as total'))
            ->groupBy('jenis_pengelolaan_id')
            ->orderByDesc('total')
            ->take(3)
            ->with('jenisPengelolaan')
            ->get();

        foreach ($delayed as $index => $item) {
            $data->push([
                'Section' => 'Bottlenecks (Most Delayed Jenis)',
                'Metric' => 'Rank ' . ($index + 1) . ': ' . $item->jenisPengelolaan->nama_jenis,
                'Value' => $item->total . ' Delayed Agendas'
            ]);
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'Section',
            'Metric / Item',
            'Value',
        ];
    }

    public function map($row): array
    {
        return [
            $row['Section'],
            $row['Metric'],
            $row['Value'],
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
