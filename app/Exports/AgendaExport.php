<?php

namespace App\Exports;

use App\Models\Agenda;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AgendaExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
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
        $query = Agenda::with(['satker', 'jenisPengelolaan', 'pic']);

        if ($this->request->filled('start_date') && $this->request->filled('end_date')) {
            $query->whereBetween('tanggal_masuk', [$this->request->start_date, $this->request->end_date]);
        }

        if ($this->request->filled('satker_id')) {
            $query->where('satker_id', $this->request->satker_id);
        }

        if ($this->request->filled('jenis_pengelolaan_id')) {
            $query->where('jenis_pengelolaan_id', $this->request->jenis_pengelolaan_id);
        }

        if ($this->request->filled('status')) {
            $query->where('status', $this->request->status);
        }

        return $query->orderBy('tanggal_masuk', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'Nomor Agenda',
            'Satker',
            'Jenis Pengelolaan',
            'Tanggal Masuk',
            'Tanggal Target',
            'Status',
            'PIC',
            'Durasi (Hari)',
            'Catatan',
        ];
    }

    public function map($agenda): array
    {
        return [
            $agenda->nomor_agenda,
            $agenda->satker->nama_satker,
            $agenda->jenisPengelolaan->nama_jenis,
            $agenda->tanggal_masuk->format('d/m/Y'),
            $agenda->tanggal_target->format('d/m/Y'),
            ucfirst($agenda->status),
            $agenda->pic->name,
            $agenda->durasi_hari,
            $agenda->notes,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
