<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Hanya user dengan role admin yang boleh lewat.
     * Pengecekan dilakukan di server (bukan sekadar menyembunyikan
     * tombol di UI) supaya akses langsung via URL tetap ditolak.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403, 'Halaman ini hanya untuk admin.');
        }

        return $next($request);
    }
}
