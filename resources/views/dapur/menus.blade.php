@extends('layouts.admin')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0 text-gray-800">Manajemen Stok Menu</h1>
                <p class="text-muted small">Atur ketersediaan menu (tersedia atau habis) secara langsung.</p>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="py-3">Foto</th>
                                <th class="py-3">Nama Menu</th>
                                <th class="py-3">Kategori</th>
                                <th class="py-3 text-center">Status (Tersedia/Habis)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($menus as $menu)
                                <tr>
                                    <td>
                                        @if($menu->image)
                                            <img src="{{ asset('storage/' . $menu->image) }}" class="rounded shadow-sm" style="width: 60px; height: 60px; object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center text-muted shadow-sm" style="width: 60px; height: 60px;">
                                                <i class="fas fa-image fs-4"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td><span class="fw-bold fs-6">{{ $menu->name }}</span></td>
                                    <td><span class="badge bg-secondary">{{ $menu->category->name ?? 'Tanpa Kategori' }}</span></td>
                                    <td class="text-center">
                                        <form action="{{ route('dapur.menus.toggle-status', $menu->id) }}" method="POST" class="d-inline-block">
                                            @csrf
                                            @method('PATCH')
                                            <div class="form-check form-switch d-flex justify-content-center align-items-center gap-2 m-0">
                                                <input class="form-check-input" type="checkbox" role="switch" id="switch{{ $menu->id }}" onchange="this.form.submit()" {{ $menu->is_available ? 'checked' : '' }} style="width: 40px; height: 20px; cursor: pointer;">
                                                <label class="form-check-label fw-bold {{ $menu->is_available ? 'text-success' : 'text-danger' }}" for="switch{{ $menu->id }}" style="cursor: pointer; min-width: 70px; text-align: left;">
                                                    {{ $menu->is_available ? 'Tersedia' : 'Habis' }}
                                                </label>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5">
                                        <p class="text-muted mb-0">Belum ada menu yang tersedia.</p>
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
