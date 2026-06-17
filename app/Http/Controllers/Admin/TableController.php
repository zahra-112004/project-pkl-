<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Table;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TableController extends Controller
{
    public function index()
    {
        $tables = Table::latest()->get();
        return view('admin.tables.index', compact('tables'));
    }

    public function store(Request $request)
{
    $request->validate([
        'number' => 'required|numeric|unique:tables,number'
    ]);

    \App\Models\Table::create([
        'number' => $request->number,
        'status' => 'available',
        'qr_token' => Str::random(32) // Mengisi field qr_token secara otomatis
    ]);

    return redirect()->back()->with('success', 'Meja berhasil ditambahkan!');
}

    public function update(Request $request, Table $table)
    {
        $request->validate([
            'number'   => 'required|string|max:20|unique:tables,number,' . $table->id,
            'capacity' => 'required|integer|min:1|max:20',
        ]);

        $table->update([
            'number'   => $request->number,
            'capacity' => $request->capacity,
        ]);

        return back()->with('success', 'Meja berhasil diupdate!');
    }

    public function destroy(Table $table)
    {
        // Hapus validasi pesanan aktif agar meja bisa langsung dihapus
        // Ini berguna jika kamu salah generate nomor meja atau kelebihan meja

        $table->delete();

        return back()->with('success', 'Data Meja ' . $table->number . ' dan Barcode-nya telah dihapus!');
    }

    // Regenerate QR Code
    public function regenerateQr(Table $table)
    {
        $table->update(['qr_token' => Str::uuid()]);
        return back()->with('success', 'QR Code berhasil diperbarui!');
    }

    public function show($id)
    {
        $table = Table::findOrFail($id);

        // Mengambil pesanan yang belum selesai
        $activeOrder = Order::where('table_id', $table->id)
                            ->whereNotIn('status', ['paid'])
                            ->with('orderDetails.menu')
                            ->latest()
                            ->first();

        return view('admin.tables.show', compact('table', 'activeOrder'));
    }

}
