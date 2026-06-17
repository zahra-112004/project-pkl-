@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h4 class="fw-bold">Input Pesanan Baru</h4>
            <p class="text-muted">Pilih meja dan menu untuk pelanggan.</p>
        </div>
    </div>

    <form action="{{ route('kasir.store-order') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Informasi Meja</h5>
                        <div class="mb-3">
                            <label class="form-label">Nomor Meja</label>
                            <select name="table_id" class="form-select" required>
                                <option value="">-- Pilih Meja --</option>
                                @foreach($tables as $table)
                                    <option value="{{ $table->id }}" {{ $table->status != 'available' ? 'disabled' : '' }}>
                                        Meja {{ $table->number }} {{ $table->status != 'available' ? '(Sedang Digunakan)' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Nama Pelanggan (Opsional)</label>
                            <input type="text" name="customer_name" class="form-control" placeholder="Contoh: Budi">
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-3 fw-bold">
                    <i class="fas fa-save me-2"></i> SIMPAN PESANAN
                </button>
            </div>

            <div class="col-md-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Daftar Menu Makanan & Minuman</h5>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Menu</th>
                                        <th>Harga</th>
                                        <th width="150">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($menus as $menu)
                                    <tr>
                                        <td>
                                            <div class="fw-bold">{{ $menu->name }}</div>
                                            <small class="text-muted">{{ $menu->category->name ?? 'Tanpa Kategori' }}</small>
                                            @if(!$menu->is_available)
                                                <span class="badge bg-danger ms-1" style="font-size: 0.7em;">Stok Habis</span>
                                            @endif
                                        </td>
                                        <td>Rp {{ number_format($menu->price, 0, ',', '.') }}</td>
                                        <td>
                                            @if($menu->is_available)
                                                <input type="number" name="items[{{ $menu->id }}][quantity]" class="form-control form-control-sm" min="0" value="0">
                                                <input type="hidden" name="items[{{ $menu->id }}][price]" value="{{ $menu->price }}">
                                            @else
                                                <input type="number" class="form-control form-control-sm" value="0" disabled>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
