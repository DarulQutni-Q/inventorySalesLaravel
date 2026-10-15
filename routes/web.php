<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CategoryController;
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
    Route::resource('categories', CategoryController::class)->only(['index']);
    Route::middleware('admin')->group(function () {
        Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
        Route::resource('categories', CategoryController::class)->except(['index']);
    });
});
