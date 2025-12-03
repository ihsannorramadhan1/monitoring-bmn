@extends('reports.layout')

@section('title', 'Laporan Status Persetujuan')
@section('report_title', 'LAPORAN STATUS PERSETUJUAN AGENDA')

@section('content')
    <!-- Summary Table -->
    <h4 style="margin-bottom: 10px;">Ringkasan Status</h4>
    <table style="width: 100%; margin-bottom: 30px;">
        <thead>
            <tr>
                <th style="width: 40%;">Status</th>
                <th style="width: 30%; text-align: center;">Jumlah Agenda</th>
                <th style="width: 30%; text-align: center;">Persentase</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $item)
                <tr>
                    <td>{{ ucfirst($item['status']) }}</td>
                    <td style="text-align: center;">{{ $item['total'] }}</td>
                    <td style="text-align: center;">{{ $item['percentage'] }}%</td>
                </tr>
            @endforeach
            <tr style="background-color: #f2f2f2; font-weight: bold;">
                <td>Total</td>
                <td style="text-align: center;">{{ $total }}</td>
                <td style="text-align: center;">100%</td>
            </tr>
        </tbody>
    </table>

    <!-- Detailed List -->
    <h4 style="margin-bottom: 10px;">Detail Agenda per Status</h4>

    @foreach($data as $item)
        @if($item['total'] > 0)
            <div style="margin-bottom: 20px;">
                <h5 style="margin-bottom: 5px; border-bottom: 1px solid #ddd; padding-bottom: 5px;">
                    {{ ucfirst($item['status']) }} ({{ $item['total'] }})
                </h5>
                <ul style="font-size: 11px; margin-top: 5px;">
                    @foreach($item['details'] as $detail)
                        <li style="margin-bottom: 3px;">
                            <strong>{{ $detail->nomor_agenda }}</strong> - {{ $detail->satker->nama_satker }}
                            ({{ $detail->tanggal_masuk->format('d/m/Y') }})
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    @endforeach

@endsection