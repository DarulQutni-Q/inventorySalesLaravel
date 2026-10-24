<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaleRequest extends FormRequest
{
    /**
     * Staff dan admin boleh membuat penjualan (otorisasi di middleware auth).
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Harga TIDAK divalidasi di sini karena diambil dari database,
     * bukan dari input browser (aturan bisnis).
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'sold_at' => ['nullable', 'date'],
            // Pelanggan opsional: penjualan walk-in boleh tanpa pelanggan.
            'customer_id' => ['nullable', 'exists:customers,id'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'distinct', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'items.required' => 'Minimal satu produk harus ditambahkan.',
            'items.*.product_id.distinct' => 'Produk tidak boleh dipilih dua kali dalam satu transaksi.',
            'items.*.quantity.min' => 'Quantity minimal 1.',
        ];
    }
}
