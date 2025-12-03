<?php

namespace App\Exports;

use App\Models\Agenda;
use App\Models\JenisPengelolaan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DurasiProsesExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
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
        $jenisPengelolaans = JenisPengelolaan::where('status', 'aktif')->get();
        $data = collect();

        foreach ($jenisPengelolaans as $jenis) {
            $query = Agenda::where('jenis_pengelolaan_id', $jenis->id)
                ->whereIn('status', ['disetujui', 'ditolak']); // Only completed agendas have final duration

            if ($this->request->filled('start_date') && $this->request->filled('end_date')) {
                $query->whereBetween('tanggal_masuk', [$this->request->start_date, $this->request->end_date]);
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

        return $data;
    }

    public function headings(): array
    {
        return [
            'Jenis Pengelolaan',
            'Target SLA (Hari)',
            'Rata-rata Durasi Aktual (Hari)',
            'Variance (Hari)',
            'Status Performa',
        ];
    }

    public function map($row): array
    {
        return [
            $row['jenis'],
            $row['target'],
            $row['avg_actual'],
            $row['variance'],
            $row['status'],
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
