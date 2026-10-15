<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // Filter divalidasi dulu supaya query tetap aman.
        $filter = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
        ]);

        $products = Product::with('category')
            ->when($filter['search'] ?? null, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('sku', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%");
                });
            })
            ->when($filter['category_id'] ?? null, function ($query, $categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->paginate(10)
            ->withQueryString(); // filter tetap ada saat pindah halaman

        $categories = Category::orderBy('name')->get();

        return view('products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('products.create', compact('categories'));
    }

    public function store(ProductRequest $request)
    {
        // Stok awal selalu 0; penambahan stok hanya lewat transaksi pembelian.
        Product::create($request->validated() + ['stock' => 0]);

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function show(Product $product)
    {
        $product->load('category');

        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();

        return view('products.edit', compact('product', 'categories'));
    }

    public function update(ProductRequest $request, Product $product)
    {
        $product->update($request->validated());

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil diubah.');
    }

    public function destroy(Product $product)
    {
        // Produk yang sudah masuk transaksi tidak boleh dihapus permanen.
        if ($product->saleItems()->exists() || $product->purchaseItems()->exists()) {
            return redirect()->route('products.index')
                ->with('error', 'Produk tidak bisa dihapus karena sudah dipakai dalam transaksi.');
        }

        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}
