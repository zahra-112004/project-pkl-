@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4 py-4">
    {{-- Judul Halaman --}}
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800 fw-bold">Laporan Penjualan</h1>
            <p class="text-muted small mb-0">Pantau performa bisnis dan transaksi harian Anda secara real-time.</p>
        </div>
        {{-- Tombol Cetak --}}
        <a href="{{ route('kasir.report.pdf', request()->all()) }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mt-3 mt-sm-0">
            <i class="fas fa-download fa-sm text-white-50 me-2"></i> Cetak Laporan PDF
        </a>
    </div>

    {{-- Form Filter --}}
    @if(auth()->user()->role == 'admin')
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <form action="{{ route('kasir.report') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label small fw-bold">Tanggal Mulai</label>
                        <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold">Tanggal Selesai</label>
                        <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold">Nama Kasir</label>
                        <select name="kasir_id" class="form-select">
                            <option value="">-- Semua Kasir --</option>
                            @foreach($kasirs as $kasir)
                                <option value="{{ $kasir->id }}" {{ request('kasir_id') == $kasir->id ? 'selected' : '' }}>{{ $kasir->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter me-2"></i>Filter
                        </button>
                        @if(request('start_date') || request('kasir_id'))
                            <a href="{{ route('kasir.report') }}" class="btn btn-secondary w-50">Reset</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    @elseif(auth()->user()->role == 'kasir')
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <form action="{{ route('kasir.report') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Tanggal Mulai</label>
                        <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-5">
                        <label class="form-label small fw-bold">Tanggal Selesai</label>
                        <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter me-2"></i>Filter
                        </button>
                        @if(request('start_date') || request('end_date'))
                            <a href="{{ route('kasir.report') }}" class="btn btn-secondary w-50">Reset</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Kartu Statistik (Sama seperti kode lama kamu) --}}
    <div class="row mb-4">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100 py-2" style="border-left: 4px solid #4e73df !important;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1" style="font-size: 0.7rem; letter-spacing: 1px;">
                                Total Pendapatan</div>
                            <div class="h4 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($total_revenue, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-coins fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100 py-2" style="border-left: 4px solid #1cc88a !important;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1" style="font-size: 0.7rem; letter-spacing: 1px;">
                                Transaksi Selesai</div>
                            <div class="h4 mb-0 font-weight-bold text-gray-800">{{ $total_transactions }} Pesanan</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-12 mb-4">
            <div class="card border-0 shadow-sm h-100 py-2" style="border-left: 4px solid #f6c23e !important;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1" style="font-size: 0.7rem; letter-spacing: 1px;">
                                Rata-rata Per Pesanan</div>
                            <div class="h4 mb-0 font-weight-bold text-gray-800">
                                Rp {{ $total_transactions > 0 ? number_format($total_revenue / $total_transactions, 0, ',', '.') : 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Utama (Sama seperti kode lama kamu) --}}
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
        <div class="card-header bg-white border-0 py-3 d-flex align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Riwayat Transaksi</h6>
            @if(auth()->user()->role == 'kasir')
                <span class="badge bg-soft-primary text-primary px-3 py-2 rounded-pill" style="background-color: #eef2ff;">Lunas (Paid)</span>
            @else
                <span class="badge bg-soft-secondary text-secondary px-3 py-2 rounded-pill" style="background-color: #f8f9fa;">Semua Status</span>
            @endif
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3 border-0 fw-bold">ID Order</th>
                            @if(auth()->user()->role == 'kasir')
                            <th class="py-3 border-0 fw-bold">Pelanggan</th>
                            @endif
                            <th class="py-3 border-0 fw-bold">Waktu Transaksi</th>
                            <th class="py-3 border-0 fw-bold text-center">Meja</th>
                            @if(auth()->user()->role == 'admin')
                            <th class="py-3 border-0 fw-bold text-center">Status</th>
                            @endif
                            <th class="py-3 border-0 fw-bold text-end pe-4">Total Harga</th>
                            <th class="py-3 border-0 fw-bold text-center pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reports as $order)
                        <tr>
                            <td class="ps-4">
                                <span class="fw-bold text-dark">#{{ $order->order_code }}</span>
                            </td>
                            @if(auth()->user()->role == 'kasir')
                            <td>
                                <span class="fw-medium">{{ $order->customer_name ?? '-' }}</span>
                            </td>
                            @endif
                            <td>
                                <div class="fw-bold">{{ $order->created_at->format('d M Y') }}</div>
                                <small class="text-muted">{{ $order->created_at->format('H:i') }} WIB</small>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-info text-dark">
                                    Meja {{ $order->table->number ?? '-' }}
                                </span>
                            </td>
                            @if(auth()->user()->role == 'admin')
                            <td class="text-center">
                                @if($order->payment_status == 'paid')
                                    <span class="badge bg-success">Lunas</span>
                                @else
                                    <span class="badge bg-warning text-dark">Belum Lunas</span>
                                @endif
                                <br>
                                <small class="text-muted">{{ ucfirst($order->status) }}</small>
                            </td>
                            @endif
                            <td class="text-end pe-4">
                                <span class="fw-bold text-success">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                            </td>
                            <td class="text-center pe-4">
                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#detailModal{{ $order->id }}">
                                    <i class="fas fa-eye"></i> Detail
                                </button>
                                
                                <!-- Modal Detail -->
                                <div class="modal fade text-start" id="detailModal{{ $order->id }}" tabindex="-1" aria-labelledby="detailModalLabel{{ $order->id }}" aria-hidden="true">
                                  <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <h5 class="modal-title" id="detailModalLabel{{ $order->id }}">Detail Transaksi - #{{ $order->order_code }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                      </div>
                                      <div class="modal-body">
                                        <div class="row mb-3">
                                            <div class="col-sm-6">
                                                <strong>Pelanggan:</strong> {{ $order->customer_name ?? '-' }}<br>
                                                <strong>Meja:</strong> {{ $order->table->number ?? '-' }}<br>
                                                <strong>Waktu:</strong> {{ $order->created_at->format('d M Y H:i') }}<br>
                                                <strong>Kasir:</strong> {{ $order->kasir->name ?? '-' }}
                                            </div>
                                            <div class="col-sm-6 text-sm-end">
                                                <strong>Metode:</strong> {{ strtoupper($order->payment_method ?? '-') }}<br>
                                                <strong>Waktu Bayar:</strong> {{ $order->paid_at ? \Carbon\Carbon::parse($order->paid_at)->format('d M Y H:i') : '-' }}<br>
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
                                                @foreach($order->orderDetails as $item)
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
                                                    <th class="text-end">Rp {{ number_format($order->total_price, 0, ',', '.') }}</th>
                                                </tr>
                                                <tr>
                                                    <th colspan="3" class="text-end">Jumlah Bayar:</th>
                                                    <th class="text-end text-success">Rp {{ number_format($order->payment_amount, 0, ',', '.') }}</th>
                                                </tr>
                                                <tr>
                                                    <th colspan="3" class="text-end">Kembalian:</th>
                                                    <th class="text-end text-warning">Rp {{ number_format($order->change_amount, 0, ',', '.') }}</th>
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
                            <td colspan="{{ auth()->user()->role == 'admin' ? 7 : (auth()->user()->role == 'kasir' ? 6 : 5) }}" class="text-center py-5">
                                <p class="text-muted mb-0">Tidak ada data untuk rentang tanggal ini.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Footer & Pagination --}}
        <div class="card-footer bg-white py-3 border-0 border-top">
            <div class="row align-items-center">
                <div class="col-md-6 text-muted small text-center text-md-start mb-3 mb-md-0">
                    Menampilkan <strong>{{ $reports->firstItem() ?? 0 }}</strong> s/d <strong>{{ $reports->lastItem() ?? 0 }}</strong> dari <strong>{{ $reports->total() }}</strong> transaksi
                </div>
                <div class="col-md-6">
                    <div class="d-flex justify-content-center justify-content-md-end">
                        {{ $reports->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Style CSS kamu tetap sama --}}
<style>
    .table thead th { letter-spacing: 0.05rem; }
    .card { transition: transform 0.2s; }
    .bg-soft-primary { background-color: #f0f4ff; color: #4e73df; }
    /* ... rest of your styles ... */
</style>
<script>
    // Refresh otomatis setiap 30 detik agar data update real-time
    setTimeout(function(){
       window.location.reload(1);
    }, 30000);
</script>
@endsection