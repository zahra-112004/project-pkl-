<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Order;
use App\Models\Table;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Menghitung statistik dasar
        $total_menu = Menu::count();

        // Menghitung total pesanan hari ini (tanpa filter status)
        $total_orders = Order::whereDate('created_at', today())->count();

        // PERBAIKAN: Hitung Pendapatan berdasarkan 'payment_status'
        $revenue = Order::where('payment_status', 'paid')
                        ->whereDate('created_at', today())
                        ->sum('total_price');

        $total_tables = Table::count();

        // PERBAIKAN: Sesuaikan status meja (tadi kita pakai 'occupied' atau 'filled'?)
        // Sesuaikan dengan yang ada di database kamu (biasanya 'occupied' atau 'filled')
        $active_tables = Table::where('status', '!=', 'available')->count();

        // 2. Mengambil semua meja untuk denah (Grid Meja)
        $tables_list = Table::orderBy('number', 'asc')->get();

        // 3. PERBAIKAN: Mengambil 5 pesanan TERBARU yang MASIH AKTIF
        // Filter agar yang sudah 'served' (disajikan) tidak memenuhi dashboard utama
        $recent_orders = Order::with('table')
                              ->where('status', '!=', 'served')
                              ->latest()
                              ->take(5)
                              ->get();

        return view('admin.dashboard', compact(
            'total_menu',
            'total_orders',
            'revenue',
            'total_tables',
            'active_tables',
            'tables_list',
            'recent_orders'
        ));
    }
}
