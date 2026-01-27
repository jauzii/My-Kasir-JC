<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\barang; 
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Total Seluruh Stok (Akumulasi semua barang yang ada di gudang saat ini)
        // Menjumlahkan kolom 'Stok' dari semua baris di tabel barang
        $totalStok = barang::sum('Stok');

        // 2. Barang Masuk (Filter 7 hari terakhir)
        // Menghitung jumlah stok dari barang yang baru ditambahkan/dibuat dalam seminggu ini
        $barangMasuk = barang::where('created_at', '>=', Carbon::now()->startOfWeek())
                             ->sum('Stok');

        // 3. Barang Keluar (Data Statis / Placeholder)
        // Karena Anda belum memiliki tabel transaksi keluar, kita biarkan angka manual dulu
        $barangKeluar = 85; 

        // 4. List Barang 
        // Mengambil semua data barang untuk ditampilkan di tabel dashboard jika ada
        $barang = barang::all();

        // Mengirimkan semua variabel ke view 'dashboard'
        return view('dashboard', compact('barang', 'totalStok', 'barangMasuk', 'barangKeluar'));
    }
}