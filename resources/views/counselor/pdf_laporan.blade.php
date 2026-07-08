<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Monitoring AI</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #2563EB;
            padding-bottom: 10px;
        }
        .header h2 {
            margin: 0;
            color: #2563EB;
        }
        .header p {
            margin: 5px 0 0 0;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
            color: #333;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #fcfcfc;
        }
        .badge {
            padding: 3px 6px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .text-center {
            text-align: center;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
            color: #777;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>Laporan Monitoring Emosi Karyawan (AI)</h2>
        <p>Mental Health Monitoring System - Dicetak pada: {{ date('d F Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 15%">Tanggal</th>
                <th style="width: 15%">Karyawan</th>
                <th style="width: 45%">Pesan Chat</th>
                <th style="width: 10%">Emosi</th>
                <th style="width: 10%">Conf.</th>
            </tr>
        </thead>
        <tbody>
            @forelse($messages as $index => $msg)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $msg->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ $msg->anonymousUser->unique_code ?? '-' }}</td>
                <td>{{ $msg->message }}</td>
                <td class="text-center">
                    {{ strtoupper($msg->emotion) }}
                </td>
                <td class="text-center">{{ $msg->confidence }}%</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Tidak ada data laporan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dokumen ini digenerate secara otomatis oleh sistem.
    </div>

</body>
</html>
