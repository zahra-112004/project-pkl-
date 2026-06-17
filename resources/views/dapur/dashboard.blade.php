@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4 pt-2">
        <div>
            <h3 class="fw-bold text-primary mb-1"><i class="fas fa-fire me-2"></i>ANTRIAN DAPUR</h3>
            <p class="text-muted mb-0">Pesanan masuk yang perlu segera diproses.</p>
        </div>
        <span class="badge bg-primary px-3 py-2">Total Antrian: {{ $orders->count() }}</span>
    </div>

    <div class="row g-3">
        @forelse($orders as $order)
        <div class="col-md-4">
            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-header bg-white border-0 pt-3 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-primary">MEJA {{ $order->table->number }}</h5>
                    <span class="text-muted small"><i class="far fa-clock me-1"></i> {{ $order->created_at->format('H:i') }}</span>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush mb-3">
                        @foreach($order->orderDetails as $detail)
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 border-0 bg-transparent">
                            <div>
                                <span class="badge bg-dark rounded-pill me-2">{{ $detail->quantity }}x</span>
                                <span class="fw-medium text-dark">{{ $detail->menu->name }}</span>
                            </div>
                        </li>
                        @endforeach
                    </ul>

                    <div class="d-grid mt-3">
                        @if($order->status == 'pending')
                            <form action="{{ route('dapur.update-status', $order->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="cooking">
                                <button class="btn btn-warning w-100 fw-bold text-white rounded-pill py-2 shadow-sm">
                                    <i class="fas fa-utensils me-2"></i> MULAI MASAK
                                </button>
                            </form>
                        @elseif($order->status == 'cooking')
                            <form action="{{ route('dapur.update-status', $order->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="ready">
                                <button class="btn btn-success w-100 fw-bold rounded-pill py-2 shadow-sm">
                                    <i class="fas fa-check me-2"></i> SIAP SAJIKAN
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <i class="fas fa-clipboard-list fa-4x text-muted opacity-25 mb-3"></i>
            <h5 class="text-muted">Tidak ada pesanan aktif saat ini.</h5>
        </div>
        @endforelse
    </div>
</div>

<script>
    // Auto refresh halaman setiap 30 detik
    setTimeout(function(){
       window.location.reload();
    }, 30000);
</script>
@endsection