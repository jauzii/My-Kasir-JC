<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BarangController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;

// Halaman Utama / Login
Route::get('/', function () {
    return view('login');
})->name('login');

// Halaman Register
Route::get('/register', function () {
    return view('register');
})->name('register');

// Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

// Halaman Barang
Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');
Route::post('/barang/simpan', [BarangController::class, 'store'])->name('barang.store');
Route::put('/barang/{id}', [BarangController::class, 'update'])->name('barang.update');
Route::delete('/barang/{id}', [BarangController::class, 'destroy'])->name('barang.destroy');

// Logout
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout');