<?php

namespace App\Http\Controllers;

use App\Models\CustomerOrder;
use App\Services\CustomerOrderService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CustomerOrderController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->validate([
            'status' => ['nullable', 'in:pending,confirmed,rejected'],
        ]);

        $orders = CustomerOrder::with('customer')
            ->when($filter['status'] ?? null, fn ($q, $status) => $q->where('status', $status))
            ->latest('ordered_at')
            ->paginate(10)
            ->withQueryString();

        return view('customer-orders.index', compact('orders', 'filter'));
    }

    public function show(CustomerOrder $order)
    {
        $order->load(['customer', 'confirmedBy', 'items.product']);

        return view('customer-orders.show', compact('order'));
    }

    public function confirm(CustomerOrder $order, CustomerOrderService $service)
    {
        try {
            $sale = $service->confirm($order, auth()->id());
        } catch (ValidationException $e) {
            return redirect()->route('customer-orders.show', $order)
                ->with('error', collect($e->errors())->flatten()->implode(' '));
        } catch (\Throwable $e) {
            return redirect()->route('customer-orders.show', $order)
                ->with('error', 'Terjadi kesalahan saat memproses pesanan: ' . $e->getMessage());
        }

        return redirect()->route('sales.show', $sale)
            ->with('success', 'Pesanan ' . $order->order_number . ' dikonfirmasi menjadi penjualan ' . $sale->sale_number . '.');
    }

    public function reject(CustomerOrder $order, CustomerOrderService $service)
    {
        try {
            $service->reject($order);
        } catch (ValidationException $e) {
            return redirect()->route('customer-orders.show', $order)
                ->with('error', collect($e->errors())->flatten()->first());
        }

        return redirect()->route('customer-orders.show', $order)
            ->with('success', 'Pesanan ' . $order->order_number . ' ditolak. Stok tidak berubah.');
    }
}
