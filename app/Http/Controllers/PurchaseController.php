<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Services\PurchaseService;
use Illuminate\Validation\ValidationException;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        // Filter tanggal dan status divalidasi dulu supaya query tetap aman.
        $filter = $request->validate([
            'dari' => ['nullable', 'date'],
            'sampai' => ['nullable', 'date', 'after_or_equal:dari'],
            'status' => ['nullable', 'in:completed,cancelled'],
        ]);

        $purchases = Purchase::with(['supplier', 'user'])->withCount('items')
            ->when($filter['dari'] ?? null, fn ($q, $dari) => $q->whereDate('purchased_at', '>=', $dari))
            ->when($filter['sampai'] ?? null, fn ($q, $sampai) => $q->whereDate('purchased_at', '<=', $sampai))
            ->when($filter['status'] ?? null, fn ($q, $status) => $q->where('status', $status))
            ->latest('purchased_at')
            ->paginate(10)
            ->withQueryString(); // filter tetap ada saat pindah halaman

        return view('purchases.index', compact('purchases', 'filter'));
    }

    public function create(PurchaseService $service)
    {
        $suppliers = Supplier::orderBy('name')->get();
        $products = Product::orderBy('name')->get();
        $productsJson = $products->map(fn ($p) => [
            'id'    => $p->id,
            'sku'   => $p->sku,
            'name'  => $p->name,
            'price' => $p->purchase_price,
        ])->values()->toArray();
        $nextNumber = $service->generateNumber();

        return view('purchases.create', compact('suppliers', 'products', 'productsJson', 'nextNumber'));
    }

    public function store(PurchaseRequest $request, PurchaseService $service)
    {
        $purchase = $service->create($request->validated(), auth()->id());

        return redirect()->route('purchases.index')
            ->with('success', 'Pembelian ' . $purchase->purchase_number . ' berhasil disimpan.');
    }

    public function show(Purchase $purchase)
    {
        $purchase->load(['supplier', 'user', 'items.product']);

        return view('purchases.show', compact('purchase'));
    }

    public function cancel(Purchase $purchase, PurchaseService $service)
    {
        try {
            $service->cancel($purchase);
        } catch (ValidationException $e) {
            return redirect()->route('purchases.show', $purchase)
                ->with('error', collect($e->errors())->flatten()->first());
        }

        return redirect()->route('purchases.show', $purchase)
            ->with('success', 'Pembelian dibatalkan, stok dikembalikan.');
    }
}
