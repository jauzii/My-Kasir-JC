<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BarangController;

// 1. Rute untuk halaman Login (Halaman Utama)
Route::get('/', function () {
    return view('login');
});

// 2. Rute untuk halaman Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
});

// 3. Rute untuk halaman Barang (Menampilkan data/form)
Route::get('/barang', function () {
    // 1. Ambil data dari database (asumsi nama model Anda adalah 'Barang')
    // Jika belum ada database, biarkan kosong dulu: $barang = [];
    $barang = [];

    // 2. Kirim variabel ke view agar @foreach bisa membacanya
    return view('barang', compact('barang'));
});

// 4. Rute untuk memproses data Barang (Biasanya dari form POST)
// Pastikan nama function di Controller sudah diisi, misalnya 'store'
Route::post('/barang/simpan', [BarangController::class, 'store'])->name('barang.store');