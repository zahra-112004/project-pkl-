<?php

namespace App\Http\Controllers\Dapur;

use App\Models\Order;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DapurController extends Controller
{
    public function index()
    {
        // Tetap tampilkan yang sedang diproses (Pending & Cooking)
        $orders = Order::with(['table', 'orderDetails.menu'])
            ->whereIn('status', ['pending', 'cooking'])
            ->oldest()
            ->get();

        return view('dapur.dashboard', compact('orders'));
    }

    public function history()
    {
        $orders = Order::whereIn('status', ['ready', 'served'])
                    ->with(['table', 'orderDetails.menu'])
                    ->latest()
                    ->get();

        return view('dapur.history', compact('orders'));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $order->update(['status' => $request->status]);

        return back()->with('success', 'Status pesanan berhasil diperbarui ke ' . $request->status);
    }
}
