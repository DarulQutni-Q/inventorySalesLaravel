<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\CustomerOrderService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CartController extends Controller
{
    public function index()
    {
        [$products, $cart, $total] = $this->cartContent();

        return view('customer.cart', compact('products', 'cart', 'total'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'quantity' => ['required', 'array'],
            'quantity.*' => ['required', 'integer', 'min:1'],
        ], [
            'quantity.*.min' => 'Jumlah minimal 1.',
        ]);

        $cart = session('cart', []);
        foreach ($data['quantity'] as $productId => $qty) {
            if (isset($cart[$productId])) {
                $cart[$productId] = (int) $qty;
            }
        }
        session(['cart' => $cart]);

        return redirect()->route('cart.index');
    }

    public function remove(int $productId)
    {
        $cart = session('cart', []);
        unset($cart[$productId]);
        session(['cart' => $cart]);

        return redirect()->route('cart.index');
    }

    public function checkout(CustomerOrderService $service)
    {
        try {
            $order = $service->create(session('cart', []), (int) session('customer_id'));
        } catch (ValidationException $e) {
            return redirect()->route('cart.index')->withErrors($e->errors());
        }

        return redirect()->route('customer.orders.show', $order)
            ->with('success', 'Pesanan ' . $order->order_number . ' dibuat dan menunggu konfirmasi staff.');
    }

    /**
     * Harga selalu dibaca ulang dari database saat halaman dibuka,
     * tidak pernah diambil dari input browser.
     */
    private function cartContent(): array
    {
        $cart = session('cart', []);
        if (!$cart) {
            return [[], [], 0];
        }

        $products = Product::whereIn('id', array_keys($cart))->orderBy('name')->get();
        $total = 0;
        foreach ($products as $product) {
            $total += $product->selling_price * $cart[$product->id];
        }

        return [$products, $cart, $total];
    }
}
