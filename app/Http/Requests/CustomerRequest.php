<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomerRequest extends FormRequest
{
    /**
     * Otorisasi ditangani middleware admin di route, jadi selalu true di sini.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            // Telepon unik karena dipakai form masuk pelanggan untuk mencari
            // customer lama tanpa duplikat.
            'phone' => ['required', 'string', 'max:30', Rule::unique('customers', 'phone')->ignore($this->route('customer'))],
            'email' => ['nullable', 'email', 'max:150'],
            'address' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama pelanggan wajib diisi.',
            'phone.required' => 'Telepon wajib diisi.',
            'phone.unique' => 'Telepon ini sudah terdaftar untuk pelanggan lain.',
            'email.email' => 'Format email tidak valid.',
        ];
    }
}
