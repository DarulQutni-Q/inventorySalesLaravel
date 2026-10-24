<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\CustomerOrder;

class OrderController extends Controller
{
    public function index()
    {
        $orders = CustomerOrder::where('customer_id', session('customer_id'))
            ->latest('ordered_at')
            ->paginate(10);

        return view('customer.orders', compact('orders'));
    }

    public function show(CustomerOrder $order)
    {
        // Pesanan hanya bisa dilihat pemiliknya, dicek di server.
        if ($order->customer_id !== (int) session('customer_id')) {
            abort(403, 'Pesanan ini bukan milik Anda.');
        }

        $order->load('items.product');

        return view('customer.order-show', compact('order'));
    }
}
