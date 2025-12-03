@extends('reports.layout')

@section('title', 'Laporan Performance Satker')
@section('report_title', 'LAPORAN KINERJA & PERINGKAT SATKER')

@section('content')
    <table style="width: 100%;">
        <thead>
            <tr>
                <th style="width: 5%; text-align: center;">Rank</th>
                <th style="width: 30%;">Nama Satker</th>
                <th style="width: 10%; text-align: center;">Total</th>
                <th style="width: 10%; text-align: center;">Selesai</th>
                <th style="width: 10%; text-align: center;">Ditolak</th>
                <th style="width: 10%; text-align: center;">Pending</th>
                <th style="width: 15%; text-align: center;">Avg Duration</th>
                <th style="width: 10%; text-align: center;">Score</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rankedData as $index => $item)
                <tr style="background-color: {{ $index < 3 ? '#fffde7' : ($index % 2 == 0 ? '#ffffff' : '#f9f9f9') }};">
                    <td style="text-align: center; font-weight: bold;">{{ $index + 1 }}</td>
                    <td>{{ $item['nama_satker'] }}</td>
                    <td style="text-align: center;">{{ $item['total'] }}</td>
                    <td style="text-align: center; color: green; font-weight: bold;">{{ $item['completed'] }}</td>
                    <td style="text-align: center; color: red;">{{ $item['rejected'] }}</td>
                    <td style="text-align: center;">{{ $item['pending'] }}</td>
                    <td style="text-align: center;">{{ $item['avg_duration'] }} Hari</td>
                    <td style="text-align: center; font-weight: bold; color: blue;">{{ $item['score'] }}%</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center;">Tidak ada data kinerja satker.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 20px; font-size: 11px; color: #666;">
        * Score dihitung berdasarkan persentase agenda yang disetujui (Selesai / Total * 100).
    </div>
@endsection