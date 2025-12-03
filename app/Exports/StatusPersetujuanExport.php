<?php

namespace App\Exports;

use App\Models\Agenda;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StatusPersetujuanExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
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
        $query = Agenda::selectRaw('status, count(*) as total')
            ->groupBy('status');

        if ($this->request->filled('start_date') && $this->request->filled('end_date')) {
            $query->whereBetween('tanggal_masuk', [$this->request->start_date, $this->request->end_date]);
        }

        $summary = $query->get();
        $total = $summary->sum('total');

        // Add percentage and details
        $data = collect();
        foreach ($summary as $item) {
            $data->push([
                'status' => ucfirst($item->status),
                'total' => $item->total,
                'percentage' => $total > 0 ? round(($item->total / $total) * 100, 2) . '%' : '0%',
            ]);
        }

        // Also append detailed list below summary? 
        // User asked for "List detail per status (expandable)". In Excel, maybe just the summary first, then details?
        // Or maybe just the summary table as requested in "Table: Status, Jumlah, Persentase, List".
        // "List" column might be too long. Let's provide summary first, then a separate sheet or section for details?
        // Requirement says "Table: Status, Jumlah, Persentase, List (nomor agenda, satker, tanggal)".
        // This implies one row per status, with a "List" column containing joined strings?
        // Let's try to join the details in the "List" column for now, as per requirement.

        $detailedData = collect();
        foreach ($summary as $item) {
            $details = Agenda::with('satker')
                ->where('status', $item->status);

            if ($this->request->filled('start_date') && $this->request->filled('end_date')) {
                $details->whereBetween('tanggal_masuk', [$this->request->start_date, $this->request->end_date]);
            }

            $details = $details->get()->map(function ($agenda) {
                return "{$agenda->nomor_agenda} ({$agenda->satker->nama_satker} - {$agenda->tanggal_masuk->format('d/m/Y')})";
            })->implode("\n");

            $detailedData->push([
                'status' => ucfirst($item->status),
                'total' => $item->total,
                'percentage' => $total > 0 ? round(($item->total / $total) * 100, 2) . '%' : '0%',
                'list' => $details
            ]);
        }

        return $detailedData;
    }

    public function headings(): array
    {
        return [
            'Status',
            'Jumlah Agenda',
            'Persentase',
            'Daftar Agenda (Nomor, Satker, Tanggal)',
        ];
    }

    public function map($row): array
    {
        return [
            $row['status'],
            $row['total'],
            $row['percentage'],
            $row['list'],
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('D')->getAlignment()->setWrapText(true);
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
