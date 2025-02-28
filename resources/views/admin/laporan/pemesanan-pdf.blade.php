<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Pemesanan</title>
    <style>
        @page {
            margin: 1.5cm 2cm;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.6;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #333;
        }
        .header h2 {
            margin: 0 0 5px 0;
            font-size: 16px;
            font-weight: bold;
        }
        .header p {
            margin: 5px 0;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        th {
            background-color: #f4f4f4;
            font-weight: bold;
            text-align: left;
            padding: 8px;
            font-size: 11px;
            border: 0.5px solid #ddd;
        }
        td {
            padding: 6px 8px;
            font-size: 10px;
            border: 0.5px solid #ddd;
            vertical-align: top;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            color: white;
            display: inline-block;
        }
        .badge-success { background: #28a745; }
        .badge-warning { background: #ffc107; }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 10px;
            font-size: 9px;
            text-align: right;
            border-top: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN PEMESANAN TIKET</h2>
        <p>{{ config('app.name') }}</p>
        <p>Periode: {{ request('date_from') ? \Carbon\Carbon::parse(request('date_from'))->format('d/m/Y') : '' }}
           {{ request('date_from') ? 's/d' : '' }}
           {{ request('date_to') ? \Carbon\Carbon::parse(request('date_to'))->format('d/m/Y') : \Carbon\Carbon::now()->format('d/m/Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;" class="text-center">No</th>
                <th style="width: 10%;">Tanggal</th>
                <th style="width: 15%;">Kode</th>
                <th style="width: 15%;">Pemesan</th>
                <th style="width: 20%;">Wisata</th>
                <th style="width: 10%;" class="text-center">Jumlah</th>
                <th style="width: 15%;" class="text-right">Total</th>
                <th style="width: 10%;" class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @php $totalPemesanan = 0; @endphp
            @forelse($pemesanan as $key => $item)
            @php $totalPemesanan += $item->total_harga; @endphp
            <tr>
                <td class="text-center">{{ $key + 1 }}</td>
                <td>{{ $item->created_at->format('d/m/Y') }}</td>
                <td>{{ $item->tiket->kode_tiket }}</td>
                <td>{{ $item->user->username }}</td>
                <td>{{ $item->wisata->nama_wisata }}</td>
                <td class="text-center">{{ $item->jumlah_tiket }}</td>
                <td class="text-right">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                <td class="text-center">
                    <span class="badge badge-{{ $item->status === 'success' ? 'success' : 'warning' }}">
                        {{ $item->status }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center">Tidak ada data pemesanan</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6" class="text-right"><strong>Total Pendapatan:</strong></td>
                <td class="text-right"><strong>Rp {{ number_format($totalPemesanan, 0, ',', '.') }}</strong></td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <div>Dicetak pada: {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</div>
        <div>Halaman {PAGE_NUM} dari {PAGE_COUNT}</div>
    </div>
</body>
</html>
