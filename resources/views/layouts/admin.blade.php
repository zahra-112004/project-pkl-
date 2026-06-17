<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QResto - {{ ucfirst(auth()->user()->role) }} Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* Mencegah scroll ke samping pada seluruh halaman */
        html, body {
            background-color: #f8f9fa;
            max-width: 100%;
            overflow-x: hidden;
        }

        /* Sidebar Fixed Logic - Dikunci agar tidak mendorong konten */
        .sidebar {
            background: #212529;
            color: white;
            height: 100vh;
            width: 260px;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            overflow-y: auto;
            padding: 1.5rem;
        }

        /* Main Content - Area ini dipaksa mengisi sisa layar dengan presisi */
        main {
            margin-left: 260px;
            width: calc(100% - 260px);
            min-height: 100vh;
            padding: 2rem;
            position: relative;
        }

        /* Responsif untuk layar kecil (Tablet/HP) */
        @media (max-width: 768px) {
            .sidebar {
                margin-left: -260px; /* Sembunyikan jika layar kecil */
            }
            main {
                margin-left: 0;
                width: 100%;
            }
        }

        /* Styling menu sesuai keinginanmu sebelumnya */
        .nav-link { color: #adb5bd; transition: 0.3s; padding: 0.8rem 1rem; border-radius: 8px; margin-bottom: 2px; }
        .nav-link:hover, .nav-link.active { color: white; background: #343a40; }
        .nav-link i { width: 25px; }

        .menu-header {
            font-size: 0.7rem;
            letter-spacing: 1px;
            opacity: 0.5;
            padding: 1.5rem 1rem 0.5rem;
        }
    </style>
</head>
<body>

    <nav class="sidebar shadow">
        @php $logo = \App\Models\Setting::where('key', 'restaurant_logo')->first(); @endphp
        <div class="d-flex align-items-center mb-4 px-2">
            @if($logo && $logo->value)
                <img src="{{ asset('storage/' . $logo->value) }}" alt="Logo" class="me-2" style="max-height: 35px;">
            @else
                <i class="fas fa-utensils me-2 fs-4 text-primary"></i>
            @endif
            <span class="fs-4 fw-bold text-white">{{ \App\Models\Setting::where('key', 'restaurant_name')->value('value') ?? 'QResto' }}</span>
        </div>

        <ul class="nav flex-column">
            {{-- DASHBOARD (SEMUA ROLE) --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->is('*/dashboard') ? 'active' : '' }}"
                   href="{{ url(auth()->user()->role.'/dashboard') }}">
                    <i class="fas fa-home"></i> Dashboard
                </a>
            </li>

            {{-- KHUSUS ADMIN: MASTER DATA --}}
            @if(auth()->user()->role == 'admin')
                <div class="text-uppercase text-light fw-bold menu-header">Master Data</div>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">
                        <i class="fas fa-tags"></i> Kategori
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.menus.*') ? 'active' : '' }}" href="{{ route('admin.menus.index') }}">
                        <i class="fas fa-hamburger"></i> Menu Makanan
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.tables.*') ? 'active' : '' }}" href="{{ route('admin.tables.index') }}">
                        <i class="fas fa-chair"></i> Meja/QR Code
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                        <i class="fas fa-users-cog"></i> Pegawai
                    </a>
                </li>
            @endif

            {{-- TRANSAKSI: ADMIN & KASIR --}}
            @if(auth()->user()->role == 'admin' || auth()->user()->role == 'kasir')
                <div class="text-uppercase text-light fw-bold menu-header">Transaksi</div>
                @if(auth()->user()->role == 'kasir')
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('kasir.pesanan-baru') ? 'active' : '' }}" href="{{ route('kasir.pesanan-baru') }}">
                <i class="fas fa-plus-circle"></i> Pesanan Baru
            </a>
        </li>
    @endif
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('kasir.pembayaran.index') ? 'active' : '' }}" href="{{ route('kasir.pembayaran.index') }}">
                        <i class="fas fa-file-invoice-dollar"></i> Pembayaran
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('kasir.report') ? 'active' : '' }}" href="{{ route('kasir.report') }}">
                        <i class="fas fa-chart-line"></i> Laporan Penjualan
                    </a>
                </li>
            @endif

           {{-- PRODUKSI: EKSKLUSIF HANYA UNTUK ROLE DAPUR --}}
@if(auth()->user()->role == 'dapur') 
    <div class="text-uppercase text-light fw-bold menu-header">Produksi</div>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('dapur.dashboard') ? 'active' : '' }}" href="{{ route('dapur.dashboard') }}">
            <i class="fas fa-fire"></i> Antrean Aktif
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('dapur.history') ? 'active' : '' }}" href="{{ route('dapur.history') }}">
            <i class="fas fa-history"></i> Riwayat Masak
        </a>
    </li>
@endif

            {{-- SISTEM: KHUSUS ADMIN --}}
            @if(auth()->user()->role == 'admin')
                <div class="text-uppercase text-light fw-bold menu-header">Sistem</div>
                <li class="nav-item">
                    <a class="nav-link{{ request()->routeIs('admin.settings.*') ? 'active' : ''}}"
                        href="{{ route('admin.settings.index') }}">
                        <i class="fas fa-cogs"></i> Pengaturan Resto
                    </a>
                </li>
            @endif

            {{-- LOGOUT --}}
            <li class="nav-item mt-4">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="nav-link border-0 bg-transparent w-100 text-start text-danger">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </li>
        </ul>
    </nav>

    <main>
        <div class="d-flex justify-content-end mb-4 align-items-center">
            <div class="text-end me-3">
                <div class="fw-bold text-dark">{{ auth()->user()->name }}</div>
                <small class="text-muted text-uppercase" style="font-size: 0.65rem;">{{ auth()->user()->role }} Account</small>
            </div>
            <span class="badge bg-primary p-2 rounded-circle">
                <i class="fas fa-user-circle fs-4"></i>
            </span>
        </div>

        {{-- Isu scroll horizontal biasanya diatasi dengan membungkus yield dalam container-fluid p-0 --}}
        <div class="container-fluid p-0">
            @yield('content')
        </div>
    </main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
