@extends('reports.layout')

@section('title', 'Executive Summary Report')
@section('report_title', 'EXECUTIVE SUMMARY REPORT')

@section('content')
    <!-- KPI Summary -->
    <table style="width: 100%; margin-bottom: 30px; border-collapse: separate; border-spacing: 15px 0;">
        <tr>
            <td
                style="width: 25%; background-color: #f9fafb; padding: 15px; border: 1px solid #e5e7eb; border-radius: 8px; text-align: center;">
                <div
                    style="font-size: 10px; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; font-weight: bold;">
                    Total Agenda</div>
                <div style="font-size: 28px; font-weight: bold; color: #111827; margin: 10px 0;">{{ $kpi['total'] }}</div>
                <div style="font-size: 10px; font-weight: 500; color: {{ $trends['total'] >= 0 ? '#16a34a' : '#dc2626' }};">
                    {{ $trends['total'] >= 0 ? '+' : '' }}{{ round($trends['total']) }}% vs last period
                </div>
            </td>
            <td
                style="width: 25%; background-color: #f9fafb; padding: 15px; border: 1px solid #e5e7eb; border-radius: 8px; text-align: center;">
                <div
                    style="font-size: 10px; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; font-weight: bold;">
                    Completed</div>
                <div style="font-size: 28px; font-weight: bold; color: #16a34a; margin: 10px 0;">{{ $kpi['completed'] }}
                </div>
                <div
                    style="font-size: 10px; font-weight: 500; color: {{ $trends['completed'] >= 0 ? '#16a34a' : '#dc2626' }};">
                    {{ $trends['completed'] >= 0 ? '+' : '' }}{{ round($trends['completed']) }}% vs last period
                </div>
            </td>
            <td
                style="width: 25%; background-color: #f9fafb; padding: 15px; border: 1px solid #e5e7eb; border-radius: 8px; text-align: center;">
                <div
                    style="font-size: 10px; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; font-weight: bold;">
                    Pending</div>
                <div style="font-size: 28px; font-weight: bold; color: #d97706; margin: 10px 0;">{{ $kpi['pending'] }}</div>
                <div style="font-size: 10px; color: #6b7280;">Active workloads</div>
            </td>
            <td
                style="width: 25%; background-color: #f9fafb; padding: 15px; border: 1px solid #e5e7eb; border-radius: 8px; text-align: center;">
                <div
                    style="font-size: 10px; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; font-weight: bold;">
                    Avg Duration</div>
                <div style="font-size: 28px; font-weight: bold; color: #2563eb; margin: 10px 0;">
                    {{ round($kpi['avg_duration'], 1) }}</div>
                <div
                    style="font-size: 10px; font-weight: 500; color: {{ $trends['avg_duration'] <= 0 ? '#16a34a' : '#dc2626' }};">
                    {{ $trends['avg_duration'] > 0 ? '+' : '' }}{{ round($trends['avg_duration']) }}% vs last period
                </div>
            </td>
        </tr>
    </table>

    <!-- Two Column Layout -->
    <table style="width: 100%; margin-bottom: 30px; border-spacing: 20px 0; border-collapse: separate;">
        <tr>
            <!-- Top Satker -->
            <td style="width: 50%; vertical-align: top; padding-right: 15px;">
                <h4
                    style="color: #1e40af; border-bottom: 2px solid #1e40af; padding-bottom: 10px; margin-top: 0; margin-bottom: 20px; font-size: 14px; text-transform: uppercase;">
                    Top 5 Most Active Satker</h4>
                <table style="width: 100%; border-collapse: collapse;">
                    @forelse($topSatkers as $index => $item)
                        <tr>
                            <td style="padding: 10px 0; border-bottom: 1px solid #f3f4f6;">
                                <span
                                    style="display: inline-block; width: 20px; height: 20px; background-color: #dbeafe; color: #1e40af; border-radius: 50%; text-align: center; line-height: 20px; font-size: 10px; font-weight: bold; margin-right: 10px;">{{ $index + 1 }}</span>
                                <span style="color: #374151; font-size: 12px;">{{ $item->satker->nama_satker }}</span>
                            </td>
                            <td
                                style="text-align: right; font-weight: bold; padding: 10px 0; border-bottom: 1px solid #f3f4f6; color: #111827; font-size: 12px;">
                                {{ $item->total }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" style="color: #9ca3af; padding: 10px 0; text-align: center; font-style: italic;">No
                                data available.</td>
                        </tr>
                    @endforelse
                </table>
            </td>

            <!-- Bottlenecks -->
            <td style="width: 50%; vertical-align: top; padding-left: 15px;">
                <h4
                    style="color: #991b1b; border-bottom: 2px solid #991b1b; padding-bottom: 10px; margin-top: 0; margin-bottom: 20px; font-size: 14px; text-transform: uppercase;">
                    Key Issues / Bottlenecks</h4>

                <div style="margin-bottom: 25px;">
                    <div
                        style="font-size: 10px; color: #6b7280; text-transform: uppercase; font-weight: bold; margin-bottom: 8px;">
                        Most Delayed Jenis Pengelolaan</div>
                    @if($mostDelayedJenis)
                        <div
                            style="font-weight: bold; color: #dc2626; font-size: 14px; padding: 10px; background-color: #fef2f2; border-radius: 4px; border: 1px solid #fee2e2;">
                            {{ $mostDelayedJenis->nama_jenis }}</div>
                    @else
                        <div style="color: #9ca3af; font-style: italic;">None</div>
                    @endif
                </div>

                <div>
                    <div
                        style="font-size: 10px; color: #6b7280; text-transform: uppercase; font-weight: bold; margin-bottom: 8px;">
                        Longest Pending Agenda</div>
                    @if($longestPending)
                        <div style="background-color: #f9fafb; padding: 10px; border-radius: 4px; border: 1px solid #f3f4f6;">
                            <div style="font-weight: bold; color: #1f2937; font-size: 14px;">{{ $longestPending->nomor_agenda }}
                            </div>
                            <div style="font-size: 11px; color: #4b5563; margin-top: 2px;">
                                {{ $longestPending->satker->nama_satker }}</div>
                            <div style="font-size: 11px; color: #dc2626; margin-top: 5px; font-weight: 500;">
                                Since {{ $longestPending->tanggal_masuk->format('d M Y') }} <span
                                    style="color: #9ca3af;">•</span> {{ $longestPending->tanggal_masuk->diffInDays(now()) }}
                                days
                            </div>
                        </div>
                    @else
                        <div style="color: #9ca3af; font-style: italic;">None</div>
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