@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4 py-4">
    {{-- Header Section --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Manajemen Meja</h1>
            <p class="text-muted small">Generate QR Code untuk setiap meja restoran.</p>
        </div>
        <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahMeja">
            <i class="fas fa-plus me-1"></i> Tambah Meja
        </button>
    </div>

    {{-- Alert Success --}}
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm d-flex align-items-center">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- Alert Error --}}
    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm d-flex align-items-center">
            <i class="fas fa-exclamation-triangle me-2"></i>
            {{ session('error') }}
        </div>
    @endif

    <div class="row">
        @forelse($tables as $table)
        <div class="col-xl-3 col-md-4 col-sm-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge {{ $table->status == 'available' ? 'bg-success' : 'bg-danger' }}">
                            {{ strtoupper($table->status) }}
                        </span>

                        <div class="dropdown">
                            <button class="btn btn-link text-muted p-0" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.tables.show', $table->id) }}">
                                        <i class="fas fa-eye me-2 text-primary"></i> Detail Meja
                                    </a>
                                </li>
                                <li>
                                    <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modalEditMeja{{ $table->id }}">
                                        <i class="fas fa-edit me-2 text-warning"></i> Edit Meja
                                    </button>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('admin.tables.destroy', $table->id) }}" method="POST" id="delete-form-{{ $table->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="dropdown-item text-danger" onclick="confirmDelete({{ $table->id }}, '{{ $table->number }}')">
                                            <i class="fas fa-trash-alt me-2"></i> Hapus Meja
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <h3 class="fw-bold my-3">Meja {{ $table->number }}</h3>

                    <div class="bg-light p-3 rounded mb-3 border">
                        {!! QrCode::size(150)->margin(1)->generate(url('/order/' . $table->number)) !!}
                    </div>

                    <div class="d-grid gap-2">
                        <a href="{{ url('/order/' . $table->number) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-external-link-alt me-1"></i> Tes Scan
                        </a>
                        <button class="btn btn-light btn-sm text-dark border" onclick="window.print()">
                            <i class="fas fa-print me-1"></i> Cetak QR
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal Edit Meja --}}
        <div class="modal fade" id="modalEditMeja{{ $table->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content border-0 shadow">
                    <form action="{{ route('admin.tables.update', $table->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-header border-0">
                            <h5 class="modal-title fw-bold">Edit Meja</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Nomor Meja</label>
                                <input type="number" name="number" class="form-control" value="{{ $table->number }}" required min="1">
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Kapasitas</label>
                                <input type="number" name="capacity" class="form-control" value="{{ $table->capacity ?? 4 }}" required min="1">
                            </div>
                        </div>
                        <div class="modal-footer border-0">
                            <button type="button" class="btn btn-light w-100 mb-2" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary w-100">Update Meja</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <img src="https://illustrations.popsy.co/amber/shrugging-person.svg" style="width: 200px;" class="mb-3">
            <p class="text-muted">Belum ada meja.</p>
        </div>
        @endforelse
    </div>
</div>

{{-- Modal Tambah Meja --}}
<div class="modal fade" id="modalTambahMeja" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('admin.tables.store') }}" method="POST">
                @csrf
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Tambah Meja Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nomor Meja</label>
                        <input type="number" name="number" class="form-control" placeholder="Contoh: 1" required min="1">
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light w-100 mb-2" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary w-100">Simpan Meja</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .card { transition: transform 0.2s ease; }
    .card:hover { transform: translateY(-5px); }
    .dropdown-item { font-size: 0.9rem; }
</style>

{{-- SCRIPT SWEETALERT --}}
<script>
function confirmDelete(id, number) {
    Swal.fire({
        title: 'Hapus Meja ' + number + '?',
        text: "Data meja dan barcode akan dihapus permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + id).submit();
        }
    })
}
</script>
@endsection
