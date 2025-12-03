@extends('reports.layout')

@section('title', 'Executive Summary Report')
@section('report_title', 'EXECUTIVE SUMMARY REPORT')

@section('content')
    <!-- KPI Summary -->
    <table style="width: 100%; margin-bottom: 30px; border-collapse: separate; border-spacing: 10px 0;">
        <tr>
            <td style="width: 25%; background-color: #f3f4f6; padding: 15px; border-radius: 8px; text-align: center;">
                <div style="font-size: 10px; color: #6b7280; text-transform: uppercase; font-weight: bold;">Total Agenda</div>
                <div style="font-size: 24px; font-weight: bold; color: #1f2937; margin: 5px 0;">{{ $kpi['total'] }}</div>
                <div style="font-size: 10px; color: {{ $trends['total'] >= 0 ? 'green' : 'red' }};">
                    {{ $trends['total'] >= 0 ? '↑' : '↓' }} {{ abs($trends['total']) }}% vs last period
                </div>
            </td>
            <td style="width: 25%; background-color: #f3f4f6; padding: 15px; border-radius: 8px; text-align: center;">
                <div style="font-size: 10px; color: #6b7280; text-transform: uppercase; font-weight: bold;">Completed</div>
                <div style="font-size: 24px; font-weight: bold; color: #16a34a; margin: 5px 0;">{{ $kpi['completed'] }}</div>
                <div style="font-size: 10px; color: {{ $trends['completed'] >= 0 ? 'green' : 'red' }};">
                    {{ $trends['completed'] >= 0 ? '↑' : '↓' }} {{ abs($trends['completed']) }}% vs last period
                </div>
            </td>
            <td style="width: 25%; background-color: #f3f4f6; padding: 15px; border-radius: 8px; text-align: center;">
                <div style="font-size: 10px; color: #6b7280; text-transform: uppercase; font-weight: bold;">Pending</div>
                <div style="font-size: 24px; font-weight: bold; color: #ca8a04; margin: 5px 0;">{{ $kpi['pending'] }}</div>
                <div style="font-size: 10px; color: #6b7280;">Active workloads</div>
            </td>
            <td style="width: 25%; background-color: #f3f4f6; padding: 15px; border-radius: 8px; text-align: center;">
                <div style="font-size: 10px; color: #6b7280; text-transform: uppercase; font-weight: bold;">Avg Duration</div>
                <div style="font-size: 24px; font-weight: bold; color: #2563eb; margin: 5px 0;">{{ round($kpi['avg_duration'], 1) }}</div>
                <div style="font-size: 10px; color: {{ $trends['avg_duration'] <= 0 ? 'green' : 'red' }};">
                    {{ $trends['avg_duration'] > 0 ? '↑' : '↓' }} {{ abs($trends['avg_duration']) }}% vs last period
                </div>
            </td>
        </tr>
    </table>

    <!-- Two Column Layout -->
    <table style="width: 100%; margin-bottom: 30px;">
        <tr>
            <!-- Top Satker -->
            <td style="width: 50%; vertical-align: top; padding-right: 15px;">
                <h4 style="color: #1e40af; border-bottom: 2px solid #1e40af; padding-bottom: 5px; margin-bottom: 15px;">🏆 Top 5 Most Active Satker</h4>
                <table style="width: 100%;">
                    @forelse($topSatkers as $index => $item)
                    <tr>
                        <td style="padding: 8px 0; border-bottom: 1px solid #e5e7eb;">
                            <span style="font-weight: bold; color: #1e40af; margin-right: 10px;">{{ $index + 1 }}.</span>
                            {{ $item->satker->nama_satker }}
                        </td>
                        <td style="text-align: right; font-weight: bold; padding: 8px 0; border-bottom: 1px solid #e5e7eb;">
                            {{ $item->total }}
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="2" style="color: #6b7280;">No data available.</td></tr>
                    @endforelse
                </table>
            </td>

            <!-- Bottlenecks -->
            <td style="width: 50%; vertical-align: top; padding-left: 15px;">
                <h4 style="color: #991b1b; border-bottom: 2px solid #991b1b; padding-bottom: 5px; margin-bottom: 15px;">⚠️ Key Issues / Bottlenecks</h4>
                
                <div style="margin-bottom: 20px;">
                    <div style="font-size: 11px; color: #6b7280; text-transform: uppercase; font-weight: bold; margin-bottom: 5px;">Most Delayed Jenis Pengelolaan</div>
                    @if($mostDelayedJenis)
                        <div style="font-weight: bold; color: #dc2626; font-size: 14px;">{{ $mostDelayedJenis->nama_jenis }}</div>
                    @else
                        <div style="color: #6b7280; font-style: italic;">None</div>
                    @endif
                </div>

                <div>
                    <div style="font-size: 11px; color: #6b7280; text-transform: uppercase; font-weight: bold; margin-bottom: 5px;">Longest Pending Agenda</div>
                    @if($longestPending)
                        <div style="font-weight: bold; color: #1f2937; font-size: 14px;">{{ $longestPending->nomor_agenda }}</div>
                        <div style="font-size: 12px; color: #4b5563;">{{ $longestPending->satker->nama_satker }}</div>
                        <div style="font-size: 11px; color: #dc2626; margin-top: 3px;">
                            Since {{ $longestPending->tanggal_masuk->format('d M Y') }} ({{ $longestPending->tanggal_masuk->diffInDays(now()) }} days)
                        </div>
                    @else
                        <div style="color: #6b7280; font-style: italic;">None</div>
                    @endif
                </div>
            </td>
        </tr>
    </table>

    <!-- Note -->
    <div style="margin-top: 50px; text-align: center; font-size: 10px; color: #9ca3af;">
        Generated automatically by Monitoring BMN System on {{ date('d M Y H:i') }}
    </div>
@endsection
