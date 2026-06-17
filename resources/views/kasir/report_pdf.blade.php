<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan QResto</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 12px;
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
            font-size: 24px;
            color: #212529;
        }
        .header p {
            margin: 5px 0 0 0;
            color: #6c757d;
        }
        .summary {
            width: 100%;
            margin-bottom: 20px;
        }
        .summary td {
            padding: 10px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            text-align: center;
            width: 50%;
        }
        .summary h3 {
            margin: 0 0 5px 0;
            font-size: 18px;
            color: #4e73df;
        }
        .summary span {
            font-weight: bold;
            font-size: 14px;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.data-table th, table.data-table td {
            border: 1px solid #dee2e6;
            padding: 8px;
        }
        table.data-table th {
            background-color: #f1f3f5;
            font-weight: bold;
            text-align: left;
        }
        table.data-table th.text-center, table.data-table td.text-center {
            text-align: center;
        }
        table.data-table th.text-right, table.data-table td.text-right {
            text-align: right;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
            color: #6c757d;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Laporan Penjualan QResto</h1>
        <p>
            @if(request('start_date') && request('end_date'))
                @if(auth()->user()->role == 'kasir')
                    Periode: {{ \Carbon\Carbon::parse(request('start_date'))->format('d M Y') }} s/d {{ \Carbon\Carbon::parse(request('end_date'))->format('d M Y') }}<br>
                @else
                    Periode: {{ \Carbon\Carbon::parse(request('start_date'))->format('d M Y H:i') }} s/d {{ \Carbon\Carbon::parse(request('end_date'))->format('d M Y H:i') }}<br>
                @endif
            @else
                Periode: Semua Waktu (Hingga {{ now()->format('d M Y H:i') }})<br>
            @endif
            @if(request('customer_name'))
                Pelanggan: {{ request('customer_name') }}
            @endif
            @if(request('kasir_id'))
                <br>Kasir: {{ $kasirs->firstWhere('id', request('kasir_id'))->name ?? 'Semua' }}
            @endif
        </p>
    </div>

    <table class="summary">
        <tr>
            <td>
                <span>Total Pendapatan</span>
                <h3>Rp {{ number_format($total_revenue, 0, ',', '.') }}</h3>
            </td>
            <td>
                <span>Total Transaksi Selesai</span>
                <h3>{{ $total_transactions }} Pesanan</h3>
            </td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>ID Order</th>
                <th>Pelanggan</th>
                <th>Kasir</th>
                <th>Waktu Transaksi</th>
                <th class="text-center">Meja</th>
                <th>Menu & Qty</th>
                <th class="text-right">Jumlah Bayar</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reports as $order)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td><strong>#{{ $order->order_code }}</strong></td>
                <td>{{ $order->customer_name ?? '-' }}</td>
                <td>{{ $order->kasir->name ?? '-' }}</td>
                <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                <td class="text-center">{{ $order->table->number ?? '-' }}</td>
                <td>
                    <ul style="margin:0; padding-left:15px; font-size:10px;">
                        @foreach($order->orderDetails as $item)
                            <li>{{ $item->menu->name ?? 'Unknown' }} (x{{ $item->quantity }})</li>
                        @endforeach
                    </ul>
                </td>
                <td class="text-right">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center">Tidak ada data transaksi.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ now()->format('d M Y H:i:s') }}
    </div>

</body>
</html>
