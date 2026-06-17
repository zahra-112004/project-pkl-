@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="col-md-6 text-center px-4">
            <div class="mb-4">
                <i class="fas fa-check-circle text-success shadow-sm rounded-circle" style="font-size: 80px;"></i>
            </div>

            <h3 class="fw-bold text-dark">Pesanan Diterima!</h3>

            <div class="alert alert-info border-0 shadow-sm rounded-pill mt-3 mb-4 py-2">
                <small><i class="fas fa-info-circle me-1"></i> Silahkan Lakukan Pembayaran Ke Kasir Agar Makanan Segera Diproses</small>
            </div>

            <p class="text-muted small mb-4">
                Pesanan Anda sedang disiapkan. Silahkan tunggu di meja Yaa:
            </p>

            <div class="card border-0 shadow-sm mb-4 mx-auto" style="border-radius: 20px; background-color: #f8f9fa; max-width: 200px;">
                <div class="card-body p-3">
                    <span class="fw-bold h1 text-primary">{{ session('table_number') }}</span>
                </div>
            </div>

            <div class="d-grid">
                <a href="{{ route('order.menu', session('table_number')) }}" class="btn btn-outline-primary btn-lg rounded-pill fw-bold">
                    <i class="fas fa-book-open me-2"></i>Pesan Lagi
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
