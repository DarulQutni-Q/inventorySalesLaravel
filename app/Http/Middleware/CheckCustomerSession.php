<?php

namespace App\Http\Middleware;

use App\Models\Customer;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckCustomerSession
{
    /**
     * Area pelanggan memakai customer_id di session, bukan login users.
     * Kalau session hilang atau customernya sudah dihapus, kembalikan ke
     * halaman masuk pelanggan (bukan login admin).
     */
    public function handle(Request $request, Closure $next): Response
    {
        $customerId = $request->session()->get('customer_id');

        if (!$customerId || !Customer::find($customerId)) {
            $request->session()->forget('customer_id');

            return redirect()->route('customer.login');
        }

        return $next($request);
    }
}
