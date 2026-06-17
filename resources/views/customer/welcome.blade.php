@extends('layouts.app')

@section('content')
<div class="d-flex align-items-center justify-content-center" style="min-height: 80vh;">
    <div class="text-center p-4 p-md-5 bg-white shadow mx-3" style="border-radius: 25px; max-width: 500px;">
        <div class="mb-4">
            <div class="bg-primary bg-opacity-10 d-inline-block p-4 rounded-circle">
                <i class="fas fa-utensils fa-3x text-primary"></i>
            </div>
        </div>
        <h2 class="fw-bold mb-2">Selamat Datang!</h2>
        <p class="text-muted mb-4 small">Silakan tekan tombol di bawah untuk melihat menu dan mulai memesan.</p>

        <div class="alert alert-primary border-0 py-3 mb-4 shadow-sm" style="border-radius: 20px;">
            <small class="d-block text-uppercase fw-bold opacity-75">Anda Berada di Meja</small>
            <h1 class="display-3 fw-bold mb-0 text-primary">{{ $table_number }}</h1>
        </div>

        <a href="{{ route('order.menu', $table_number) }}" class="btn btn-primary btn-lg w-100 py-3 rounded-pill fw-bold shadow">
            LIHAT MENU SEKARANG <i class="fas fa-chevron-right ms-2"></i>
        </a>
    </div>
</div>
@endsection
