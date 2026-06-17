<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <title>QResto - Menu Digital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', sans-serif; }
        .navbar-blue { background-color: #0d6efd; color: white; padding: 15px; }
        .card-menu { border-radius: 15px; transition: 0.3s; background: white; border: none; }
        .card-menu:hover:not(.disabled-card) { transform: translateY(-8px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
        .grayscale { filter: grayscale(100%); opacity: 0.6; }
        .sticky-nav { position: sticky; top: 0; z-index: 1000; }
    </style>
</head>
<body>
    <nav class="navbar-blue d-flex justify-content-between align-items-center mb-4 shadow-sm sticky-nav">
        <h5 class="mb-0 fw-bold text-white"><i class="fas fa-utensils me-2"></i>QRESTO</h5>
        @if(session('table_number'))
            <span class="badge bg-white text-primary px-3 py-2">Meja {{ session('table_number') }}</span>
        @endif
    </nav>
    <div class="container pb-5">
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @yield('content')
    </div>
</body>
</html>
