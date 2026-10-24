<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
        ]);

        $products = Product::with('category')
            ->when($filter['search'] ?? null, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->when($filter['category_id'] ?? null, function ($query, $categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();

        return view('customer.catalog', compact('products', 'categories'));
    }

    public function add(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ], [
            'quantity.min' => 'Jumlah minimal 1.',
        ]);

        $product = Product::findOrFail($data['product_id']);

        // Stok tidak dikurangi di sini; keputusan stok ada di konfirmasi
        // staff, supaya alur pelanggan dan alur stok tetap terpisah.
        if ($product->stock <= 0) {
            return redirect()->route('catalog.index')
                ->with('error', $product->name . ' sedang habis, tidak bisa ditambahkan.');
        }

        $cart = session('cart', []);
        $cart[$product->id] = ($cart[$product->id] ?? 0) + (int) $data['quantity'];

        session(['cart' => $cart]);

        return redirect()->route('cart.index')
            ->with('success', $product->name . ' masuk keranjang.');
    }
}
