@extends('reports.layout')

@section('title', 'Laporan Analisis Durasi Proses')
@section('report_title', 'LAPORAN ANALISIS DURASI PROSES & SLA')

@section('content')
    <!-- Summary Table -->
    <h4 style="margin-bottom: 10px;">Analisis Performa per Jenis Pengelolaan</h4>
    <table style="width: 100%; margin-bottom: 30px;">
        <thead>
            <tr>
                <th style="width: 30%;">Jenis Pengelolaan</th>
                <th style="width: 15%; text-align: center;">Target SLA</th>
                <th style="width: 15%; text-align: center;">Avg Actual</th>
                <th style="width: 15%; text-align: center;">Variance</th>
                <th style="width: 25%; text-align: center;">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $item)
                <tr>
                    <td>{{ $item['jenis'] }}</td>
                    <td style="text-align: center;">{{ $item['target'] }} Hari</td>
                    <td style="text-align: center;">{{ $item['avg_actual'] }} Hari</td>
                    <td style="text-align: center; color: {{ $item['variance'] > 0 ? 'red' : 'green' }};">
                        {{ $item['variance'] > 0 ? '+' : '' }}{{ $item['variance'] }}
                    </td>
                    <td style="text-align: center;">
                        <span style="color: {{ $item['status'] == 'On Time' ? 'green' : 'red' }}; font-weight: bold;">
                            {{ $item['status'] }}
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Overdue List -->
    <h4 style="margin-bottom: 10px; color: #d32f2f;">Daftar Agenda Overdue (Top 20)</h4>
    <table style="width: 100%;">
        <thead>
            <tr>
                <th style="width: 20%;">Nomor Agenda</th>
                <th style="width: 25%;">Satker</th>
                <th style="width: 20%;">Jenis</th>
                <th style="width: 15%; text-align: center;">Tgl Masuk</th>
                <th style="width: 10%; text-align: center;">Target</th>
                <th style="width: 10%; text-align: center;">Durasi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($overdueAgendas as $agenda)
                <tr>
                    <td>{{ $agenda->nomor_agenda }}</td>
                    <td>{{ $agenda->satker->nama_satker }}</td>
                    <td>{{ $agenda->jenisPengelolaan->nama_jenis }}</td>
                    <td style="text-align: center;">{{ $agenda->tanggal_masuk->format('d/m/Y') }}</td>
                    <td style="text-align: center;">{{ $agenda->tanggal_target->format('d/m/Y') }}</td>
                    <td style="text-align: center; color: red; font-weight: bold;">{{ $agenda->durasi_hari }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center;">Tidak ada agenda overdue.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

@endsection