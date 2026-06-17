@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Riwayat Pembayaran</h1>
            <p class="text-muted small">Daftar semua transaksi yang telah lunas (Paid).</p>
        </div>
    </div>
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('kasir.pembayaran.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-9">
                    <label class="form-label small fw-bold">Nama Pelanggan</label>
                    <input type="text" name="customer_name" class="form-control" placeholder="Cari nama pelanggan..." value="{{ request('customer_name') }}">
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-2"></i>Filter
                    </button>
                    @if(request('customer_name'))
                        <a href="{{ route('kasir.pembayaran.index') }}" class="btn btn-secondary w-50">Reset</a>
                    @endif
                </div>
            </form>
        </div>
    </div>
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="py-3">No</th>
                            <th class="py-3">Tanggal & Waktu</th>
                            <th class="py-3">Pelanggan</th>
                            <th class="py-3">Nomor Meja</th>
                            <th class="py-3">Total Pembayaran</th>
                            <th class="py-3 text-center">Status</th>
                            <th class="py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <div class="fw-bold">{{ $payment->created_at->format('d M Y') }}</div>
                                <small class="text-muted">{{ $payment->created_at->format('H:i') }} WIB</small>
                            </td>
                            <td>
                                <span class="fw-medium">{{ $payment->customer_name ?? '-' }}</span>
                            </td>
                            <td>
                                <span class="badge bg-info text-dark">
                                    Meja {{ $payment->table->number ?? '-' }}
                                </span>
                            </td>
                            <td class="fw-bold text-success">
                                Rp {{ number_format($payment->total_price, 0, ',', '.') }}
                            </td>
                            <td class="text-center">
                                <span class="badge bg-success">
                                    <i class="fas fa-check-circle me-1"></i> PAID
                                </span>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#detailModal{{ $payment->id }}">
                                    <i class="fas fa-eye"></i> Detail
                                </button>

                                <!-- Modal Detail -->
                                <div class="modal fade text-start" id="detailModal{{ $payment->id }}" tabindex="-1" aria-labelledby="detailModalLabel{{ $payment->id }}" aria-hidden="true">
                                  <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <h5 class="modal-title" id="detailModalLabel{{ $payment->id }}">Detail Transaksi - #{{ $payment->order_code }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                      </div>
                                      <div class="modal-body">
                                        <div class="row mb-3">
                                            <div class="col-sm-6">
                                                <strong>Pelanggan:</strong> {{ $payment->customer_name }}<br>
                                                <strong>Meja:</strong> {{ $payment->table->number ?? '-' }}<br>
                                                <strong>Waktu:</strong> {{ $payment->created_at->format('d M Y H:i') }}<br>
                                                <strong>Kasir:</strong> {{ $payment->kasir->name ?? '-' }}
                                            </div>
                                            <div class="col-sm-6 text-sm-end">
                                                <strong>Metode:</strong> {{ strtoupper($payment->payment_method ?? '-') }}<br>
                                                <strong>Waktu Bayar:</strong> {{ $payment->paid_at ? \Carbon\Carbon::parse($payment->paid_at)->format('d M Y H:i') : '-' }}<br>
                                            </div>
                                        </div>
                                        <table class="table table-bordered table-sm">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Menu</th>
                                                    <th>Harga</th>
                                                    <th>Qty</th>
                                                    <th class="text-end">Subtotal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($payment->orderDetails as $item)
                                                <tr>
                                                    <td>{{ $item->menu->name ?? 'Unknown' }}</td>
                                                    <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                                    <td>{{ $item->quantity }}</td>
                                                    <td class="text-end">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="3" class="text-end">Total Harga:</th>
                                                    <th class="text-end">Rp {{ number_format($payment->total_price, 0, ',', '.') }}</th>
                                                </tr>
                                                <tr>
                                                    <th colspan="3" class="text-end">Jumlah Bayar:</th>
                                                    <th class="text-end text-success">Rp {{ number_format($payment->payment_amount, 0, ',', '.') }}</th>
                                                </tr>
                                                <tr>
                                                    <th colspan="3" class="text-end">Kembalian:</th>
                                                    <th class="text-end text-warning">Rp {{ number_format($payment->change_amount, 0, ',', '.') }}</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                      </div>
                                      <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <img src="https://illustrations.popsy.co/amber/empty-folder.svg" style="width: 150px;" class="mb-3">
                                <p class="text-muted">Belum ada riwayat transaksi lunas.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white py-3 border-0 border-top">
            <div class="row align-items-center">
                <div class="col-md-6 text-muted small text-center text-md-start mb-3 mb-md-0">
                    Menampilkan <strong>{{ $payments->firstItem() ?? 0 }}</strong> s/d <strong>{{ $payments->lastItem() ?? 0 }}</strong> dari <strong>{{ $payments->total() }}</strong> transaksi
                </div>
                <div class="col-md-6">
                    <div class="d-flex justify-content-center justify-content-md-end">
                        {{ $payments->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    // Refresh otomatis setiap 30 detik agar data update real-time
    setTimeout(function(){
       window.location.reload(1);
    }, 30000);
</script>
@endsection
