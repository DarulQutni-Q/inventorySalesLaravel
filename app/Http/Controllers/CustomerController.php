<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerRequest;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
        ]);

        $customers = Customer::when($filter['search'] ?? null, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(CustomerRequest $request)
    {
        Customer::create($request->validated());

        return redirect()->route('customers.index')
            ->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(CustomerRequest $request, Customer $customer)
    {
        $customer->update($request->validated());

        return redirect()->route('customers.index')
            ->with('success', 'Pelanggan berhasil diubah.');
    }

    public function destroy(Customer $customer)
    {
        // Pelanggan yang sudah dipakai transaksi tidak boleh dihapus,
        // mengikuti aturan yang sama seperti produk, kategori, dan supplier.
        if ($customer->sales()->exists() || $customer->orders()->exists()) {
            return redirect()->route('customers.index')
                ->with('error', 'Pelanggan ini sudah dipakai di penjualan atau pesanan, tidak bisa dihapus.');
        }

        $customer->delete();

        return redirect()->route('customers.index')
            ->with('success', 'Pelanggan berhasil dihapus.');
    }
}
