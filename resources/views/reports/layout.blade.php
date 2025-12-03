<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Laporan Monitoring BMN')</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
        }

        .header h2 {
            margin: 5px 0 0;
            font-size: 14px;
            font-weight: normal;
        }

        .header p {
            margin: 5px 0 0;
            font-size: 12px;
            color: #666;
        }

        .content {
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            font-size: 10px;
            color: #999;
            text-align: right;
            border-top: 1px solid #ddd;
            padding-top: 5px;
        }

        .page-number:after {
            content: counter(page);
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Kementerian Keuangan Republik Indonesia</h1>
        <h2>Direktorat Jenderal Kekayaan Negara</h2>
        <h2>Kantor Pelayanan Kekayaan Negara dan Lelang Banjarmasin</h2>
        <p>Jl. Pramuka No. 7, Banjarmasin, Kalimantan Selatan</p>
    </div>

    <div class="content">
        <h3 style="text-align: center; margin-bottom: 20px;">@yield('report_title')</h3>
        <p style="text-align: center; margin-bottom: 20px;">Periode: @yield('period', date('d F Y'))</p>

        @yield('content')
    </div>

    <div class="footer">
        Dicetak pada: {{ date('d F Y H:i:s') }} | Halaman <span class="page-number"></span>
    </div>
</body>

</html>