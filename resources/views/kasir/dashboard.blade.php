@extends('layouts.admin')

@section('title', 'Dashboard Kasir')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">
        <i class="bi bi-cash-register me-2 text-success"></i>Dashboard Kasir
    </h4>
    <span class="text-muted">Selamat datang, {{ Auth::user()->name }}</span>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm bg-success text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-white bg-opacity-25 rounded p-3 me-3">
                        <i class="bi bi-wallet2 fs-3 text-white"></i>
                    </div>
                    <div>
                        <h6 class="mb-1 opacity-75">Pendapatan Hari Ini</h6>
                        <h3 class="fw-bold mb-0">Rp {{ number_format($todayEarnings ?? 0, 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm bg-primary text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-white bg-opacity-25 rounded p-3 me-3">
                        <i class="bi bi-check-circle fs-3 text-white"></i>
                    </div>
                    <div>
                        <h6 class="mb-1 opacity-75">Transaksi Selesai</h6>
                        <h3 class="fw-bold mb-0">{{ $todayTransactions ?? 0 }} Pesanan</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0 text-dark">
                    <i class="bi bi-grid-3x3-gap me-2 text-primary"></i>Status Meja
                </h5>
            </div>
            <div class="card-body" style="max-height: 600px; overflow-y: auto;">
                <div class="row row-cols-2 g-2">
                    @foreach($tables as $meja)
                    <div class="col">
                        <div class="card h-100 border-1 {{ $meja->status == 'occupied' ? 'border-danger bg-light-danger' : 'border-success bg-light-success' }}">
                            <div class="card-body p-2 text-center">
                                <h6 class="mb-1">Meja {{ $meja->number }}</h6>

                                @if($meja->status == 'occupied')
                                    <span class="badge bg-danger mb-2 small">Ditempati</span>
                                    <button type="button" class="btn btn-xs btn-danger w-100 py-1 shadow-sm" data-bs-toggle="modal" data-bs-target="#emptyTableModal{{ $meja->id }}">
                                        <i class="bi bi-x-circle me-1"></i>Kosongkan
                                    </button>

                                    <!-- Modal Konfirmasi Kosongkan -->
                                    <div class="modal fade text-start" id="emptyTableModal{{ $meja->id }}" tabindex="-1" aria-labelledby="emptyTableModalLabel{{ $meja->id }}" aria-hidden="true">
                                      <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                          <div class="modal-header border-0 pb-0">
                                            <h5 class="modal-title fw-bold" id="emptyTableModalLabel{{ $meja->id }}">Konfirmasi Kosongkan Meja</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                          </div>
                                          <div class="modal-body text-center py-4">
                                            <div class="mb-3">
                                                <i class="fas fa-exclamation-triangle text-warning" style="font-size: 3rem;"></i>
                                            </div>
                                            <p class="mb-0 fs-5">Apakah pelayan sudah mengonfirmasi <strong>Meja {{ $meja->number }}</strong> bersih dan siap digunakan pelanggan baru?</p>
                                          </div>
                                          <div class="modal-footer border-0 pt-0 justify-content-center">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                            <form action="{{ route('kasir.meja.kosongkan', $meja->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-danger px-4">Ya, Kosongkan</button>
                                            </form>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                @else
                                    <span class="badge bg-success mb-2 small">Tersedia</span>
                                    <div class="py-2">
                                        <i class="bi bi-check2-circle text-success fs-4"></i>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0 text-dark">
                    <i class="bi bi-hourglass-split me-2 text-warning"></i>Menunggu Pembayaran
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Kode</th>
                                <th>Pelanggan</th>
                                <th>Meja</th>
                                <th>Total Tagihan</th>
                                <th>Status Dapur</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pendingPayments as $order)
                            <tr>
                                <td class="fw-bold text-primary">#{{ $order->order_code }}</td>
                                <td><span class="text-dark fw-medium">{{ $order->customer_name ?? '-' }}</span></td>
                                <td><span class="badge bg-dark text-white">Meja {{ $order->table->number }}</span></td>
                                <td class="fw-bold">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                <td>
                                    @if($order->status == 'ready')
                                        <span class="badge bg-success">SIAP DISAJIKAN</span>
                                    @else
                                        <span class="badge bg-warning text-dark">SEDANG PROSES</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('kasir.pembayaran.show', $order->id) }}" class="btn btn-success btn-sm px-3 rounded-pill">
                                        <i class="bi bi-currency-dollar me-1"></i> Bayar
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="80" class="mb-3 opacity-50" alt="Empty">
                                    <p class="text-muted mb-0">Belum ada pesanan yang perlu dibayar saat ini.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .btn-xs { padding: 0.2rem 0.4rem; font-size: 0.75rem; }
    .bg-light-danger { background-color: #fff5f5; }
    .bg-light-success { background-color: #f6fff9; }
</style>
<script>
    // Refresh otomatis setiap 30 detik agar data update real-time
    setTimeout(function(){
       window.location.reload(1);
    }, 30000);
</script>
@endsection
