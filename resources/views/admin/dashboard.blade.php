@extends('layouts.admin')

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">Dashboard Admin</h1>
        <p class="text-muted">Ringkasan aktivitas QResto hari ini.</p>

        <div class="row mt-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100 py-2" style="border-left: 4px solid #4e73df !important;">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1" style="font-size: 0.7rem; letter-spacing: 1px;">Total Menu</div>
                                <div class="h4 mb-0 font-weight-bold text-gray-800">{{ $total_menu }} Menu</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-utensils fa-2x text-gray-300" style="color: #dddfeb;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100 py-2" style="border-left: 4px solid #1cc88a !important;">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1" style="font-size: 0.7rem; letter-spacing: 1px;">Pesanan (Hari Ini)</div>
                                <div class="h4 mb-0 font-weight-bold text-gray-800">{{ $total_orders }} Pesanan</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-shopping-basket fa-2x text-gray-300" style="color: #dddfeb;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100 py-2" style="border-left: 4px solid #36b9cc !important;">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1" style="font-size: 0.7rem; letter-spacing: 1px;">Pendapatan</div>
                                <div class="h4 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($revenue, 0, ',', '.') }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-wallet fa-2x text-gray-300" style="color: #dddfeb;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100 py-2" style="border-left: 4px solid #f6c23e !important;">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1" style="font-size: 0.7rem; letter-spacing: 1px;">Meja Terisi</div>
                                <div class="h4 mb-0 font-weight-bold text-gray-800">{{ $active_tables }} / {{ $total_tables }} Meja</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-chair fa-2x text-gray-300" style="color: #dddfeb;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                <h6 class="m-0 font-weight-bold text-dark"><i class="fas fa-th-large me-2 text-primary"></i>Status Meja
                    Real-Time</h6>
                <div class="small">
                    <span class="badge bg-success me-1">Tersedia</span>
                    <span class="badge bg-danger">Terisi</span>
                </div>
            </div>
            <div class="card-body bg-light">
                <div class="row g-3">
                    @foreach ($tables_list as $meja)
                        <div class="col-6 col-md-3 col-lg-2">
                            <div class="card h-100 {{ $meja->status == 'occupied' ? 'bg-danger text-white border-0' : 'bg-white border-success text-success' }} shadow-sm">
                                <div class="card-body text-center py-4">
                                    <i class="fas fa-chair fa-lg mb-2"></i>
                                    <div class="h6 fw-bold mb-0">Meja {{ $meja->number }}</div>
                                    <div style="font-size: 0.75rem;">
                                        {{ $meja->status == 'occupied' ? 'Ditempati' : 'Kosong' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 font-weight-bold text-dark"><i class="fas fa-clock me-2 text-info"></i>Pesanan Terbaru</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light text-secondary text-uppercase" style="font-size: 0.8rem;">
                            <tr>
                                <th>No. Meja</th>
                                <th>Waktu</th>
                                <th>Status</th>
                                <th class="text-end">Total Bayar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recent_orders as $order)
                                <tr>
                                    <td class="fw-bold text-dark">Meja {{ $order->table->number }}</td>
                                    <td>{{ $order->created_at->format('H:i') }} WIB</td>
                                    <td>
                                        @php
                                            $badgeClass = match ($order->status) {
                                                'pending' => 'bg-warning text-dark',
                                                'cooking' => 'bg-info',
                                                'ready' => 'bg-primary',
                                                'paid' => 'bg-success',
                                                default => 'bg-secondary',
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td class="text-end fw-bold">Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted small italic">Belum ada pesanan
                                        hari ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <style>
        .transition-card {
            transition: all 0.2s ease-in-out;
            cursor: pointer;
        }

        .table-link:hover .transition-card {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }
    </style>
@endsection
<script>
    // Refresh halaman setiap 30 detik untuk update status meja real-time
    setTimeout(function(){
       window.location.reload(1);
    }, 30000);
</script>
