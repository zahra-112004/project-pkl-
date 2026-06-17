@extends('layouts.app')

@section('content')
<div class="mb-4 text-center">
    <h2 class="fw-bold text-primary">MENU KAMI</h2>
    <p class="text-muted small">Pilih menu favorit Anda</p>
    <div class="mx-auto bg-primary" style="height: 3px; width: 40px; border-radius: 2px;"></div>
</div>

@foreach($categories as $category)
    @if($category->menus->count() > 0)
    <div class="mb-4">
        <h5 class="fw-bold mb-3 text-dark px-2" style="border-left: 4px solid #0d6efd; padding-left: 10px;">
            {{ strtoupper($category->name) }}
        </h5>

        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3">
            @foreach($category->menus as $menu)
            <div class="col">
                <div class="card card-menu h-100 shadow-sm border-0 {{ !$menu->is_available ? 'bg-light opacity-75' : '' }}" style="border-radius: 15px; overflow: hidden;">
                    <div class="position-relative">
                        <img src="{{ asset('storage/'.$menu->image) }}"
                             class="w-100 {{ !$menu->is_available ? 'grayscale' : '' }}"
                             style="height: 130px; object-fit: cover;">

                        @if(!$menu->is_available)
                            <div class="position-absolute top-50 start-50 translate-middle w-100 text-center">
                                <span class="badge bg-danger shadow">HABIS</span>
                            </div>
                        @endif
                    </div>

                    <div class="card-body p-2 text-center">
                        <h6 class="fw-bold mb-1 text-dark text-truncate small">{{ $menu->name }}</h6>
                        <p class="text-primary fw-bold mb-2 small">Rp {{ number_format($menu->price, 0, ',', '.') }}</p>

                        @if($menu->is_available)
                            <form action="{{ route('order.add-to-cart') }}" method="POST">
                                @csrf
                                <input type="hidden" name="menu_id" value="{{ $menu->id }}">
                                <button type="submit" class="btn btn-primary btn-sm w-100 rounded-pill py-2 fw-bold">
                                    <i class="fas fa-plus me-1"></i> Tambah
                                </button>
                            </form>
                        @else
                            <button class="btn btn-secondary btn-sm w-100 rounded-pill py-2 fw-bold" disabled>N/A</button>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
@endforeach

@if(session('cart') && count(session('cart')) > 0)
<div class="fixed-bottom p-3 d-flex justify-content-center" style="z-index: 1050;">
    <a href="{{ route('order.cart') }}" class="btn btn-dark shadow-lg rounded-pill py-3 px-4 w-100" style="max-width: 500px;">
        <div class="d-flex justify-content-between align-items-center">
            <span><i class="fas fa-shopping-cart me-2 text-primary"></i> {{ count(session('cart')) }} Item</span>
            <span class="fw-bold">Lihat Keranjang <i class="fas fa-arrow-right ms-1"></i></span>
        </div>
    </a>
</div>
<div style="height: 80px;"></div>
@endif

<style>
    .card-menu { transition: transform 0.2s; }
    .card-menu:active { transform: scale(0.95); }
    .grayscale { filter: grayscale(100%); opacity: 0.6; }
</style>
@endsection
