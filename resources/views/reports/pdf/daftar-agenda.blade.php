@extends('reports.layout')

@section('title', 'Laporan Daftar Agenda')
@section('report_title', 'LAPORAN DAFTAR AGENDA PERSETUJUAN')

@section('content')
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 15%;">Nomor Agenda</th>
                <th style="width: 20%;">Satker</th>
                <th style="width: 15%;">Jenis Pengelolaan</th>
                <th style="width: 10%;">Tgl Masuk</th>
                <th style="width: 10%;">Target</th>
                <th style="width: 10%;">Status</th>
                <th style="width: 10%;">PIC</th>
                @if(auth()->user()->role === 'admin')
                    <th style="width: 5%;">Durasi</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse($agendas as $index => $agenda)
                <tr style="background-color: {{ $index % 2 == 0 ? '#ffffff' : '#f9f9f9' }};">
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ $agenda->nomor_agenda }}</td>
                    <td>{{ $agenda->satker->nama_satker }}</td>
                    <td>{{ $agenda->jenisPengelolaan->nama_jenis }}</td>
                    <td style="text-align: center;">{{ $agenda->tanggal_masuk->format('d/m/Y') }}</td>
                    <td style="text-align: center;">{{ $agenda->tanggal_target->format('d/m/Y') }}</td>
                    <td style="text-align: center;">{{ ucfirst($agenda->status) }}</td>
                    <td>{{ $agenda->pic->name }}</td>
                    @if(auth()->user()->role === 'admin')
                        <td style="text-align: center;">{{ $agenda->durasi_hari }}</td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="{{ auth()->user()->role === 'admin' ? 9 : 8 }}" style="text-align: center;">Tidak ada data
                        agenda.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 20px;">
        <strong>Total Records: {{ $agendas->count() }}</strong>
    </div>
@endsection