<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Table;
use App\Models\Menu;
use App\Models\OrderDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $pendingPayments = Order::with('table')
                            ->where('payment_status', 'unpaid')
                            ->latest()
                            ->get();

        $tables = Table::orderBy('number', 'asc')->get();

        $todayEarnings = Order::whereDate('created_at', today())
                            ->where('payment_status', 'paid')
                            ->sum('total_price');

        $todayTransactions = Order::whereDate('created_at', today())
                            ->where('payment_status', 'paid')
                            ->count();

        return view('kasir.dashboard', compact(
            'pendingPayments', 'tables', 'todayEarnings', 'todayTransactions'
        ));
    }

    public function pembayaran()
    {
        $history = Order::where('status', 'paid')
                        ->latest()
                        ->paginate(10);

        return view('kasir.history', compact('history'));
    }

    public function showPembayaran($id)
    {
        $order = Order::with(['table', 'orderDetails.menu'])
                    ->findOrFail($id);

        return view('kasir.pembayaran_proses', compact('order'));
    }

    public function prosesBayar(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        DB::beginTransaction();
        try {
            $kembalian = $request->payment_amount - $order->total_price;

            $order->update([
                'kasir_id'       => Auth::id(),
                'payment_status' => 'paid',
                'payment_method' => $request->payment_method,
                'payment_amount' => $request->payment_amount,
                'change_amount'  => $kembalian,
                'paid_at'        => now(),
            ]);

            DB::commit();
            return redirect()->route('kasir.dashboard')->with('success', 'Pembayaran Berhasil! Jangan lupa kosongkan meja jika pelanggan sudah pergi.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function resetMeja($id)
    {
        $table = Table::findOrFail($id);
        $table->update(['status' => 'available']);

        return redirect()->route('kasir.dashboard')
                        ->with('success', 'Meja ' . $table->number . ' kini tersedia.');
    }

    public function printStruk($id)
    {
        $order = Order::with(['table', 'orderDetails.menu', 'kasir'])->findOrFail($id);
        return view('kasir.struk', compact('order'));
    }

    public function createOrder()
    {
        $menus = Menu::all();
        $tables = Table::orderBy('number', 'asc')->get();

        return view('kasir.orders.create', compact('menus', 'tables'));
    }

    public function storeOrder(Request $request)
    {
        $request->validate([
            'table_id' => 'required|exists:tables,id',
            'items' => 'required|array',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $orderCode = 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(5));

                $order = Order::create([
                    'order_code'    => $orderCode,
                    'table_id'      => $request->table_id,
                    'customer_name' => $request->customer_name ?? 'Pelanggan Manual',
                    'total_price'   => 0,
                    'status'        => 'pending',
                    'payment_status' => 'unpaid',
                ]);

                $totalPrice = 0;

                foreach ($request->items as $menu_id => $item) {
                    if (isset($item['quantity']) && $item['quantity'] > 0) {
                        $hargaSatuan = $item['price'];
                        $qty = $item['quantity'];
                        $hitungSubtotal = $qty * $hargaSatuan;

                        $totalPrice += $hitungSubtotal;

                        OrderDetail::create([
                            'order_id' => $order->id,
                            'menu_id'  => $menu_id,
                            'quantity' => $qty,
                            'price'    => $hargaSatuan,
                            'subtotal' => $hitungSubtotal,
                            'notes'    => $item['notes'] ?? null,
                        ]);
                    }
                }

                $order->update(['total_price' => $totalPrice]);

                $table = Table::find($request->table_id);
                $table->update(['status' => 'occupied']);

            });

            return redirect()->route('kasir.dashboard')
                            ->with('success', 'Pesanan berhasil dibuat!');

        } catch (\Exception $e) {
            Log::error('Gagal Simpan Pesanan: ' . $e->getMessage());
            return back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function history(Request $request)
    {
        $query = Order::where('payment_status', 'paid')
                        ->with(['table', 'orderDetails.menu', 'kasir']);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user && $user->role == 'kasir') {
            $query->where('kasir_id', $user->id);
        }

        if ($request->filled('customer_name')) {
            $query->where('customer_name', 'like', '%' . $request->customer_name . '%');
        }

        $payments = $query->latest()->paginate(10);

        return view('kasir.history', compact('payments'));
    }

    public function report(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $query = Order::with(['table', 'orderDetails.menu', 'kasir']);

        if ($user && $user->role == 'kasir') {
            $query->where('payment_status', 'paid')->where('kasir_id', $user->id);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = \Carbon\Carbon::parse($request->start_date)->startOfDay()->format('Y-m-d H:i:s');
            $endDate = \Carbon\Carbon::parse($request->end_date)->endOfDay()->format('Y-m-d H:i:s');
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        if ($request->filled('customer_name')) {
            $query->where('customer_name', 'like', '%' . $request->customer_name . '%');
        }

        if ($request->filled('kasir_id')) {
            $query->where('kasir_id', $request->kasir_id);
        }

        $total_revenue = (clone $query)->where('payment_status', 'paid')->sum('total_price');
        $total_transactions = $query->count();
        $reports = $query->latest()->paginate(10);
        $kasirs = User::where('role', 'kasir')->get();

        return view('kasir.report', compact('total_revenue', 'total_transactions', 'reports', 'kasirs'));
    }

    public function exportPdf(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $query = Order::with(['table', 'orderDetails.menu', 'kasir']);

        if ($user && $user->role == 'kasir') {
            $query->where('payment_status', 'paid')->where('kasir_id', $user->id);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = \Carbon\Carbon::parse($request->start_date)->startOfDay()->format('Y-m-d H:i:s');
            $endDate = \Carbon\Carbon::parse($request->end_date)->endOfDay()->format('Y-m-d H:i:s');
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        if ($request->filled('customer_name')) {
            $query->where('customer_name', 'like', '%' . $request->customer_name . '%');
        }

        if ($request->filled('kasir_id')) {
            $query->where('kasir_id', $request->kasir_id);
        }

        $total_revenue = (clone $query)->where('payment_status', 'paid')->sum('total_price');
        $total_transactions = $query->count();
        $reports = $query->latest()->get();
        $kasirs = User::where('role', 'kasir')->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('kasir.report_pdf', compact('total_revenue', 'total_transactions', 'reports', 'request', 'kasirs'));
        return $pdf->download('laporan-penjualan-qresto.pdf');
    }
}
