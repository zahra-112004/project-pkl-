@extends('layouts.admin')

@section('title', 'Proses Pembayaran')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">
        <i class="bi bi-cash-register me-2 text-success"></i>Proses Pembayaran
    </h4>
    <a href="{{ route('kasir.dashboard') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h5 class="fw-bold mb-3"><i class="bi bi-receipt me-2"></i>Detail Pesanan #{{ $order->order_code }}</h5>
                <p class="mb-2 text-muted">
                    <i class="bi bi-person-fill me-1"></i> Pelanggan: <strong>{{ $order->customer_name ?? '-' }}</strong> |
                    <i class="bi bi-grid-fill me-1"></i> Meja: <strong>{{ $order->table->number ?? '-' }}</strong>
                </p>
                <hr>
                <ul class="list-group list-group-flush mb-4">
                    @foreach($order->orderDetails as $detail)
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span>{{ $detail->menu->name ?? 'Menu Terhapus' }} <small class="text-muted">(x{{ $detail->quantity }})</small></span>
                        <span>Rp {{ number_format($detail->price * $detail->quantity, 0, ',', '.') }}</span>
                    </li>
                    @endforeach
                    <li class="list-group-item d-flex justify-content-between px-0 fw-bold fs-5 text-primary mt-2">
                        <span>Total Tagihan</span>
                        <span>Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <form action="{{ route('kasir.pembayaran.konfirmasi', $order->id) }}" method="POST">
            @csrf
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold mb-3"><i class="bi bi-cash-coin me-2"></i>Input Pembayaran</h5>

                    <div class="mb-3">
                        <label class="form-label">Metode Pembayaran</label>
                        <select name="payment_method" class="form-select" required>
                            <option value="cash">Tunai (Cash)</option>
                            <option value="qris">QRIS</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jumlah Uang Diterima</label>
                        <input type="number" name="payment_amount" class="form-control form-control-lg" id="bayar" placeholder="0" required>
                    </div>

                    <div class="alert alert-success border-0 shadow-sm">
                        <div class="d-flex justify-content-between fs-4 fw-bold">
                            <span>Kembalian:</span>
                            <span id="kembalian">Rp 0</span>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success w-100 fw-bold py-2">
                        SELESAI PEMBAYARAN
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    const total = {{ $order->total_price }};
    const inputBayar = document.getElementById('bayar');
    const displayKembalian = document.getElementById('kembalian');

    inputBayar.addEventListener('input', function() {
        let bayar = parseFloat(this.value) || 0;
        let kembali = bayar - total;

        if (bayar > 0) {
            displayKembalian.innerText = kembali >= 0 ?
                'Rp ' + new Intl.NumberFormat('id-ID').format(kembali) : 'Uang Kurang!';
            displayKembalian.className = kembali >= 0 ? 'text-success' : 'text-danger';
        } else {
            displayKembalian.innerText = 'Rp 0';
        }
    });
</script>
@endsection
