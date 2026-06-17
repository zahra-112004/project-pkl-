<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->get();
        return view('admin.categories.index', compact('categories'));
    }


    public function store(Request $request)
    {
        $request->validate(['name' => 'required|unique:categories,name']);

        \App\Models\Category::create([
            'name' => $request->name
        ]);

        return redirect()->back()->with('success', 'Kategori berhasil ditambah!');
    }

    public function update(Request $request, $id)
    {
        $category = \App\Models\Category::findOrFail($id);

        $request->validate(['name' => 'required|unique:categories,name,' . $id]);

        $category->update([
            'name' => $request->name
        ]);

        return redirect()->back()->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $category = \App\Models\Category::findOrFail($id);

        // Cek jika ada menu di kategori ini sebelum menghapus (opsional)
        if ($category->menus()->count() > 0) {
            return redirect()->back()->with('error', 'Kategori tidak bisa dihapus karena masih memiliki menu!');
        }

        $category->delete();
        return redirect()->back()->with('success', 'Kategori berhasil dihapus!');
    }
}
