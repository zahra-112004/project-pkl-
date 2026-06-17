@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <a href="{{ route('order.menu', ['number' => $table_number]) }}" class="text-decoration-none text-primary fw-bold small">
            <i class="fas fa-arrow-left me-1"></i> Kembali Pilih Menu
        </a>
    </div>

    <h4 class="fw-bold mb-4">Pesanan Meja {{ $table_number }}</h4>

    @if (session('cart') && count(session('cart')) > 0)
        @php $total = 0 @endphp
        @foreach (session('cart') as $id => $details)
            @php $total += $details['price'] * $details['quantity'] @endphp
            <div class="card border-0 shadow-sm mb-2" style="border-radius: 12px;">
                <div class="card-body p-2">
                    <div class="d-flex align-items-center">
                        <img src="{{ asset('storage/' . $details['image']) }}" class="rounded" width="60" height="60" style="object-fit: cover;">

                        <div class="ms-3 flex-grow-1">
                            <h6 class="fw-bold mb-0 small">{{ $details['name'] }}</h6>
                            <p class="text-primary fw-bold mb-0 small">Rp {{ number_format($details['price'], 0, ',', '.') }}</p>
                        </div>

                        <div class="d-flex align-items-center bg-light rounded-pill px-1">
                            <form action="{{ route('order.update-cart') }}" method="POST" class="m-0">
                                @csrf
                                <input type="hidden" name="id" value="{{ $id }}">
                                <input type="hidden" name="quantity" value="{{ $details['quantity'] - 1 }}">
                                <button type="submit" class="btn btn-sm text-primary p-1" {{ $details['quantity'] <= 1 ? 'disabled' : '' }}>
                                    <i class="fas fa-minus-circle fa-lg"></i>
                                </button>
                            </form>

                            <span class="px-2 fw-bold small">{{ $details['quantity'] }}</span>

                            <form action="{{ route('order.update-cart') }}" method="POST" class="m-0">
                                @csrf
                                <input type="hidden" name="id" value="{{ $id }}">
                                <input type="hidden" name="quantity" value="{{ $details['quantity'] + 1 }}">
                                <button type="submit" class="btn btn-sm text-primary p-1">
                                    <i class="fas fa-plus-circle fa-lg"></i>
                                </button>
                            </form>
                        </div>

                        <form action="{{ route('order.remove-from-cart') }}" method="POST" class="ms-2 m-0">
                            @csrf
                            <input type="hidden" name="id" value="{{ $id }}">
                            <button type="submit" class="btn btn-sm text-danger border-0"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach

        <div class="card border-0 shadow-sm mt-4 p-3 bg-primary text-white" style="border-radius: 15px;">
            <div class="d-flex justify-content-between align-items-center">
                <span class="small">Total Bayar</span>
                <h4 class="fw-bold mb-0">Rp {{ number_format($total, 0, ',', '.') }}</h4>
            </div>
        </div>

        <form action="{{ route('order.checkout') }}" method="POST" class="mt-4">
            @csrf
            <div class="mb-3">
                <label class="form-label small fw-bold text-muted px-2">Nama Anda (Opsional)</label>
                <input type="text" name="customer_name" class="form-control rounded-pill border-0 shadow-sm p-3" placeholder="Contoh: Budi">
            </div>

            <button type="submit" class="btn btn-primary w-100 btn-lg rounded-pill fw-bold shadow py-3">
                PESAN SEKARANG <i class="fas fa-paper-plane ms-2"></i>
            </button>
        </form>
    @else
        <div class="text-center py-5">
            <i class="fas fa-shopping-basket fa-4x text-muted opacity-25 mb-3"></i>
            <p class="text-muted">Wah, keranjangmu masih kosong nih.</p>
            <a href="{{ route('order.menu', $table_number) }}" class="btn btn-primary rounded-pill px-4">Lihat Menu</a>
        </div>
    @endif
@endsection
