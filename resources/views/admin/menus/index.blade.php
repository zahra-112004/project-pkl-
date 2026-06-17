@extends('layouts.admin')

@section('content')
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
            <h1 class="h3">Manajemen Menu</h1>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahMenu">
                <i class="fas fa-plus me-1"></i> Tambah Menu
            </button>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Foto</th>
                                <th>Nama Menu</th>
                                <th>Kategori</th>
                                <th>Harga</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($menus as $menu)
                                <tr>
                                    <td>
                                        <img src="{{ asset('storage/' . $menu->image) }}" class="rounded"
                                            style="width: 60px; height: 60px; object-fit: cover;">
                                    </td>
                                    <td><span class="fw-bold">{{ $menu->name }}</span></td>
                                    <td><span class="badge bg-secondary">{{ $menu->category->name }}</span></td>
                                    <td>Rp {{ number_format($menu->price, 0, ',', '.') }}</td>
                                    <td>
                                        <form action="{{ route('admin.menus.toggle-status', $menu->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" onchange="this.form.submit()" {{ $menu->is_available ? 'checked' : '' }}>
                                                <label class="form-check-label">{{ $menu->is_available ? 'On' : 'Off' }}</label>
                                            </div>
                                        </form>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-warning me-1" data-bs-toggle="modal"
                                            data-bs-target="#modalEditMenu{{ $menu->id }}">
                                            <i class="fas fa-edit me-1"></i> Edit
                                        </button>

                                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                            data-bs-target="#modalDeleteMenu{{ $menu->id }}">
                                            <i class="fas fa-trash me-1"></i> Hapus
                                        </button>
                                    </td>
                                </tr>

                                <div class="modal fade" id="modalDeleteMenu{{ $menu->id }}" tabindex="-1" aria-labelledby="modalDeleteMenuLabel{{ $menu->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="modalDeleteMenuLabel{{ $menu->id }}">Konfirmasi Hapus</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Apakah Anda yakin ingin menghapus menu <strong>{{ $menu->name }}</strong>?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <form action="{{ route('admin.menus.destroy', $menu->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal fade" id="modalEditMenu{{ $menu->id }}" tabindex="-1"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <form action="{{ route('admin.menus.update', $menu->id) }}" method="POST"
                                                enctype="multipart/form-data">
                                                @csrf @method('PUT')
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Menu: {{ $menu->name }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Nama Menu</label>
                                                            <input type="text" name="name" class="form-control"
                                                                value="{{ $menu->name }}" required>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Kategori</label>
                                                            <select name="category_id" class="form-select" required>
                                                                @foreach ($categories as $cat)
                                                                    <option value="{{ $cat->id }}"
                                                                        {{ $menu->category_id == $cat->id ? 'selected' : '' }}>
                                                                        {{ $cat->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Harga (Rp)</label>
                                                            <input type="number" name="price" class="form-control"
                                                                value="{{ $menu->price }}" required>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label d-block">Status Stok</label>
                                                            <div class="form-check form-switch mt-2">
                                                                <input type="hidden" name="is_available" value="0">
                                                                <input class="form-check-input" type="checkbox" role="switch" name="is_available" value="1" {{ $menu->is_available ? 'checked' : '' }}>
                                                                <label class="form-check-label">Tersedia / On</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12 mb-3">
                                                            <label class="form-label">Ganti Foto <small
                                                                    class="text-muted">(Kosongkan jika tidak
                                                                    ganti)</small></label>
                                                            <input type="file" name="image" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-success">Update Menu</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalTambahMenu" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('admin.menus.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Menu Baru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Menu</label>
                                <input type="text" name="name" class="form-control"
                                    placeholder="Nama masakan/minuman" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kategori</label>
                                <select name="category_id" class="form-select" required>
                                    <option value="">Pilih Kategori</option>
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Harga (Rp)</label>
                                <input type="number" name="price" class="form-control" placeholder="Contoh: 25000"
                                    required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Foto Menu</label>
                                <input type="file" name="image" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Menu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@if ($errors->any())
    <script>
        // Jika ada error, otomatis buka kembali modal tambah
        var myModal = new bootstrap.Modal(document.getElementById('modalTambahMenu'));
        myModal.show();
    </script>
@endif
