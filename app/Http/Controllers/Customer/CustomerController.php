<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Table;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    public function index($number)
    {
        $table = Table::where('number', $number)->firstOrFail();
        $categories = Category::with('menus')->get();

        return view('customer.index', compact('categories', 'table'));
    }

    // app/Http/Controllers/Customer/CustomerController.php

public function welcome($number)
{
    // 1. Cari meja berdasarkan nomor
    $table = Table::where('number', $number)->firstOrFail();

    // 2. Simpan ke session (sudah benar)
    session(['table_number' => $table->number]);

    // 3. LOGIKA TAMBAHAN: Update status meja di database menjadi 'filled'
    $table->update(['status' => 'occupied']);

    return view('customer.welcome', ['table_number' => $table->number]);
}

    public function addToCart(Request $request)
    {
        $request->validate(['menu_id' => 'required|exists:menus,id']);
        $menu = Menu::findOrFail($request->menu_id);

        if (!$menu->is_available) {
            return redirect()->back()->with('error', 'Menu sedang habis.');
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$request->menu_id])) {
            $cart[$request->menu_id]['quantity']++;
        } else {
            $cart[$request->menu_id] = [
                "name" => $menu->name,
                "quantity" => 1,
                "price" => $menu->price,
                "image" => $menu->image
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', $menu->name . ' masuk keranjang!');
    }

    public function viewCart()
    {
        $cart = session()->get('cart', []);
        $table_number = session('table_number');

        if (!$table_number) {
            return redirect('/')->with('error', 'Silakan scan QR Code kembali.');
        }

        return view('customer.cart', compact('cart', 'table_number'));
    }

    public function checkout(Request $request)
    {
        $cart = session()->get('cart');
        $tableNumber = session('table_number');

        // Validasi Awal
        if (!$cart) return back()->with('error', 'Keranjang kosong!');

        $table = Table::where('number', $tableNumber)->first();
        if (!$table) return redirect('/')->with('error', 'Meja tidak ditemukan.');

        DB::beginTransaction();
        try {
            // 1. Hitung Total
            $totalPrice = 0;
            foreach ($cart as $details) {
                $totalPrice += $details['price'] * $details['quantity'];
            }

            // 2. Buat Order
            $order = Order::create([
                'table_id'      => $table->id,
                'order_code'    => Order::generateOrderCode(),
                'customer_name' => $request->customer_name ?? 'Pelanggan Meja ' . $table->number,
                'total_price'   => $totalPrice,
                'status'        => 'pending',
            ]);

            // 3. Buat Detail (Subtotal dikirim eksplisit agar DB tidak komplain)
            foreach ($cart as $id => $details) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'menu_id'  => $id,
                    'quantity' => $details['quantity'],
                    'price'    => $details['price'],
                    'subtotal' => $details['quantity'] * $details['price'],
                    'notes'    => $details['notes'] ?? null,
                ]);
            }

            DB::commit();
            session()->forget('cart');

            return redirect()->route('order.success');
        } catch (\Exception $e) {
            DB::rollback();
            // Gunakan logger() helper lebih aman daripada \Log
            logger()->error('Checkout Error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan sistem. Silakan coba lagi.');
        }
    }

    public function updateCart(Request $request)
    {
        $cart = session()->get('cart');
        if (isset($cart[$request->id])) {
            $newQty = $request->quantity;
            if ($newQty > 0) {
                $cart[$request->id]["quantity"] = $newQty;
            } else {
                unset($cart[$request->id]);
            }
            session()->put('cart', $cart);
            return redirect()->back()->with('success', 'Keranjang diperbarui!');
        }
    }

    public function removeFromCart(Request $request)
    {
        $cart = session()->get('cart');
        if (isset($cart[$request->id])) {
            unset($cart[$request->id]);
            session()->put('cart', $cart);
        }
        return redirect()->back()->with('success', 'Menu dihapus.');
    }
}
