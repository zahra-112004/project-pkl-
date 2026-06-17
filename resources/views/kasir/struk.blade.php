<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembayaran #{{ $order->order_code }}</title>
    <style>
        body { font-family: monospace; width: 300px; margin: 0 auto; padding: 20px; color: #000; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .border-top { border-top: 1px dashed #000; padding-top: 10px; mt-10px; }
        .border-bottom { border-bottom: 1px dashed #000; padding-bottom: 10px; mb-10px; }
        .mb-2 { margin-bottom: 10px; }
        .mt-2 { margin-top: 10px; }
        table { width: 100%; border-collapse: collapse; }
        td { vertical-align: top; }
    </style>
</head>
<body onload="window.print()">
    <div class="text-center mb-2">
        <h2 style="margin:0;">QResto</h2>
        <p style="margin:5px 0;">Jl. Contoh Alamat Resto No. 123<br>Telp: 08123456789</p>
    </div>

    <div class="border-top border-bottom mb-2">
        <table>
            <tr><td>No. Order</td><td>: {{ $order->order_code }}</td></tr>
            <tr><td>Tanggal</td><td>: {{ $order->created_at->format('d/m/Y H:i') }}</td></tr>
            <tr><td>Kasir</td><td>: {{ $order->kasir->name ?? '-' }}</td></tr>
            <tr><td>Pelanggan</td><td>: {{ $order->customer_name ?? '-' }}</td></tr>
            <tr><td>Meja</td><td>: {{ $order->table->number ?? '-' }}</td></tr>
        </table>
    </div>

    <table class="mb-2 border-bottom">
        @foreach($order->orderDetails as $item)
        <tr>
            <td colspan="3">{{ $item->menu->name ?? 'Unknown' }}</td>
        </tr>
        <tr>
            <td>{{ $item->quantity }} x {{ number_format($item->price, 0, ',', '.') }}</td>
            <td class="text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </table>

    <table class="mb-2 font-bold">
        <tr>
            <td>Total</td>
            <td class="text-right">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Tunai/Dibayar</td>
            <td class="text-right">Rp {{ number_format($order->payment_amount, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Kembali</td>
            <td class="text-right">Rp {{ number_format($order->change_amount, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="text-center border-top mt-2">
        <p>Terima Kasih Atas Kunjungan Anda</p>
    </div>
</body>
</html>
