<?php

namespace App\Exports;

use App\Models\Agenda;
use App\Models\Satker;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PerformanceSatkerExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
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
        $satkers = Satker::where('status', 'aktif')->get();
        $data = collect();

        foreach ($satkers as $satker) {
            $query = Agenda::where('satker_id', $satker->id);

            if ($this->request->filled('start_date') && $this->request->filled('end_date')) {
                $query->whereBetween('tanggal_masuk', [$this->request->start_date, $this->request->end_date]);
            }

            $agendas = $query->get();
            $total = $agendas->count();

            if ($total == 0)
                continue; // Skip satker with no agenda? Or show with 0? Let's skip to keep ranking clean or show at bottom. Requirement says "Ranking". 0 activity is low rank. Let's include all active satkers.

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

        // Add Rank column
        return $rankedData->map(function ($item, $key) {
            return [
                'rank' => $key + 1,
                'nama_satker' => $item['nama_satker'],
                'total' => $item['total'],
                'completed' => $item['completed'],
                'rejected' => $item['rejected'],
                'pending' => $item['pending'],
                'avg_duration' => $item['avg_duration'],
                'score' => $item['score'] . '%',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Rank',
            'Nama Satker',
            'Total Agenda',
            'Selesai (Disetujui)',
            'Ditolak',
            'Pending',
            'Rata-rata Durasi (Hari)',
            'Completion Rate (Score)',
        ];
    }

    public function map($row): array
    {
        return [
            $row['rank'],
            $row['nama_satker'],
            $row['total'],
            $row['completed'],
            $row['rejected'],
            $row['pending'],
            $row['avg_duration'],
            $row['score'],
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
