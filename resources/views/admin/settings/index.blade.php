@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h4 class="fw-bold">Pengaturan Restoran</h4>
            <p class="text-muted">Kelola informasi dasar dan konfigurasi sistem QResto.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6 border-end">
                        <h5 class="mb-3 text-primary"><i class="fas fa-store me-2"></i>Profil Resto</h5>
                        <div class="mb-3">
                            <label class="form-label">Nama Restoran</label>
                            <input type="text" name="restaurant_name" class="form-control" value="{{ $settings['restaurant_name'] ?? 'QResto' }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea name="restaurant_address" class="form-control" rows="3">{{ $settings['restaurant_address'] ?? 'Jl. Politeknik Negeri Padang' }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nomor Telepon</label>
                            <input type="text" name="restaurant_phone" class="form-control" value="{{ $settings['restaurant_phone'] ?? '08123456789' }}">
                        </div>
                    </div>

                    <div class="col-md-6 ps-md-4">
                        <h5 class="mb-3 text-primary"><i class="fas fa-cogs me-2"></i>Konfigurasi Struk & Pajak</h5>
                        <div class="mb-3">
                            <label class="form-label">Pajak (PPN) %</label>
                            <div class="input-group">
                                <input type="number" name="restaurant_tax" class="form-control" value="{{ $settings['restaurant_tax'] ?? '10' }}">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Pesan di Bawah Struk (Footer)</label>
                            <input type="text" name="restaurant_footer" class="form-control" value="{{ $settings['restaurant_footer'] ?? 'Terima kasih atas kunjungannya!' }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Logo Restoran</label>
                            @if(isset($settings['restaurant_logo']))
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $settings['restaurant_logo']) }}" alt="Logo" class="img-thumbnail" style="max-height: 80px;">
                                </div>
                            @endif
                            <input type="file" name="logo" class="form-control">
                            <small class="text-muted">Gunakan file .png transparan untuk hasil terbaik.</small>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-end">
                    <button type="reset" class="btn btn-light me-2">Batal</button>
                    <button type="submit" class="btn btn-primary px-4">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
