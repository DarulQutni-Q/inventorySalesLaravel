<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaleRequest;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Sale;
use App\Services\SaleService;
use Illuminate\Validation\ValidationException;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        // Filter tanggal dan status divalidasi dulu supaya query tetap aman.
        $filter = $request->validate([
            'dari' => ['nullable', 'date'],
            'sampai' => ['nullable', 'date', 'after_or_equal:dari'],
            'status' => ['nullable', 'in:completed,cancelled'],
        ]);

        $sales = Sale::with('user')
            ->withCount('items')
            ->when($filter['dari'] ?? null, fn ($q, $dari) => $q->whereDate('sold_at', '>=', $dari))
            ->when($filter['sampai'] ?? null, fn ($q, $sampai) => $q->whereDate('sold_at', '<=', $sampai))
            ->when($filter['status'] ?? null, fn ($q, $status) => $q->where('status', $status))
            ->latest('sold_at')
            ->paginate(10)
            ->withQueryString();
        return view('sales.index', compact('sales', 'filter'));

    }

    public function create(SaleService $service)
    {
        // Hanya tampilkan produk yang stoknya masih ada.
        $products = Product::where('stock', '>', 0)->orderBy('name')->get();
        $productsJson = $products->map(fn ($p) => [
            'id' => $p->id,
            'sku' => $p->sku,
            'name' => $p->name,
            'price' => $p->selling_price,
            'stock' => $p->stock,
        ])->values()->toArray();

        $nextNumber = $service->generateNumber();

        return view('sales.create', compact('products', 'productsJson', 'nextNumber'));
    }

    public function store(SaleRequest $request, SaleService $service)
    {
        $sale = $service->create($request->validated(), auth()->id());

        return redirect()->route('sales.index')
            ->with('success', 'Penjualan ' . $sale->sale_number . ' berhasil disimpan.');
    }

    public function show(Sale $sale)
    {
        $sale->load(['user', 'items.product']);

        return view('sales.show', compact('sale'));
    }

    public function cancel(Sale $sale, SaleService $service)
    {
        try {
            $service->cancel($sale);
        } catch (ValidationException $e) {
            return redirect()->route('sales.show', $sale)
                ->with('error', collect($e->errors())->flatten()->first());
        }

        return redirect()->route('sales.show', $sale)
            ->with('success', 'Penjualan dibatalkan, stok dikembalikan.');
    }
}
