@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4 pt-2">
        <div>
            <h3 class="fw-bold text-success mb-1"><i class="fas fa-history me-2"></i>RIWAYAT MASAK</h3>
            <p class="text-muted mb-0">Daftar pesanan yang telah selesai disajikan.</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 15px;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3">Waktu</th>
                            <th class="py-3">Meja</th>
                            <th class="py-3">Detail Menu</th>
                            <th class="py-3 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td class="ps-4">
                                <span class="text-muted small">{{ $order->created_at->format('d M') }}</span><br>
                                <strong>{{ $order->created_at->format('H:i') }}</strong>
                            </td>
                            <td>
                                <span class="badge bg-info text-white rounded-pill px-3">Meja {{ $order->table->number }}</span>
                            </td>
                            <td>
                                <ul class="list-unstyled mb-0">
                                    @foreach($order->orderDetails as $detail)
                                    <li>
                                        <small class="fw-bold">{{ $detail->quantity }}x</small> {{ $detail->menu->name }}
                                    </li>
                                    @endforeach
                                </ul>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-success-subtle text-success border border-success px-3 py-2 rounded-pill">
                                    <i class="fas fa-check-circle me-1"></i> Selesai
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="fas fa-folder-open fa-3x mb-3 opacity-25"></i>
                                <p>Belum ada riwayat pesanan hari ini.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection