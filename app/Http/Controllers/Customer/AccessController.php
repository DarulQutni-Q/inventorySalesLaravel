<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class AccessController extends Controller
{
    public function showLogin()
    {
        if (Customer::find(session('customer_id'))) {
            return redirect()->route('catalog.index');
        }

        return view('customer.login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'phone' => ['required', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:255'],
        ], [
            'name.required' => 'Nama wajib diisi.',
            'phone.required' => 'Telepon wajib diisi.',
        ]);

        // Telepon adalah identitas pelanggan: sudah terdaftar dipakai lagi,
        // belum terdaftar dibuat baru. Ini identitas session untuk demo
        // UTS, bukan autentikasi aman: siapa pun yang tahu telepon bisa
        // memakai identitas ini.
        $customer = Customer::firstOrCreate(
            ['phone' => $data['phone']],
            ['name' => $data['name'], 'address' => $data['address'] ?? null],
        );

        session([
            'customer_id' => $customer->id,
            'customer_name' => $customer->name,
        ]);

        return redirect()->route('catalog.index');
    }

    public function logout(Request $request)
    {
        $request->session()->forget(['customer_id', 'customer_name']);

        return redirect()->route('login');
    }
}
