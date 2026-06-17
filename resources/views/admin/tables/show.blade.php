@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Detail Meja {{ $table->number }}</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Detail Meja</li>
    </ol>

    <div class="row">
        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-body text-center py-4">
                    <i class="fas fa-chair fa-5x mb-3 {{ $table->status == 'occupied' ? 'text-danger' : 'text-success' }}"></i>
                    <h3 class="fw-bold">Status: {{ ucfirst($table->status) }}</h3>
                    <p class="text-muted">Kapasitas: {{ $table->capacity }} orang</p>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-dark"><i class="fas fa-list me-2"></i>Daftar Pesanan Aktif</h6>
                </div>
                <div class="card-body">
                    @if($activeOrder)
                        <div class="alert alert-info">
                            <strong>Kode Order:</strong> {{ $activeOrder->order_code }} <br>
                            <strong>Pelanggan:</strong> {{ $activeOrder->customer_name }}
                        </div>
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Menu</th>
                                    <th>Qty</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($activeOrder->orderDetails as $item)
                                <tr>
                                    <td>{{ $item->menu->name }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="2" class="text-end">Total Harga:</th>
                                    <th class="text-danger">Rp {{ number_format($activeOrder->total_price, 0, ',', '.') }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-concierge-bell fa-3x text-secondary mb-3"></i>
                            <p class="text-muted">Tidak ada pesanan aktif di meja ini.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
