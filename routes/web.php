<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? view('home')
        : redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Dashboard (diisi penuh di fitur berikutnya)
    Route::get('/dashboard', function () {
        return view('home');
    })->name('dashboard');

    // Master data: lihat untuk semua user login, ubah hanya untuk admin.
    // Route admin didaftarkan dulu supaya /products/create tidak tertangkap
    // oleh /products/{product} (route show yang didaftarkan setelahnya).
    Route::middleware('admin')->group(function () {
        Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
        Route::resource('categories', CategoryController::class)->except(['index']);
        Route::resource('products', ProductController::class)->except(['index', 'show']);
        Route::resource('suppliers', SupplierController::class)->except(['index']);
    });
    Route::resource('categories', CategoryController::class)->only(['index']);
    Route::resource('products', ProductController::class)->only(['index', 'show']);
    Route::resource('suppliers', SupplierController::class)->only(['index']);
});
