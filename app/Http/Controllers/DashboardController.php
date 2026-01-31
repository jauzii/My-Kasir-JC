<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\barang; 
use App\Models\BarangKeluar;
use App\Models\BarangMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Total Seluruh Stok (Akumulasi semua barang yang ada di gudang saat ini)
        // Menjumlahkan kolom 'Stok' dari semua baris di tabel barang
        try {
            if (Schema::hasTable('barang')) {
                $totalStok = barang::sum('Stok');
            } else {
                $totalStok = 0;
            }
        } catch (\Exception $e) {
            Log::warning('DashboardController: gagal menghitung totalStok: ' . $e->getMessage());
            $totalStok = 0;
        }

        // 2. Barang Masuk (hitung TOTAL hari ini)
        try {
            if (Schema::hasTable('barang_masuk')) {
                // Ambil total jumlah barang masuk yang tercatat untuk hari ini
                $barangMasuk = BarangMasuk::whereDate('tanggal_masuk', Carbon::today())->sum('jumlah');
            } else {
                // Fallback: jumlah barang yang ditambahkan ke sistem hari ini (jika tidak ada tabel barang_masuk)
                $barangMasuk = barang::whereDate('created_at', Carbon::today())->sum('Stok');
            }
        } catch (\Exception $e) {
            Log::warning('DashboardController: gagal menghitung barangMasuk: ' . $e->getMessage());
            $barangMasuk = 0;
        }

        // 3. Barang Keluar (Hitung jumlah keluar hari ini jika tabel tersedia)
        try {
            if (Schema::hasTable('barang_keluar')) {
                $barangKeluar = BarangKeluar::whereDate('tanggal_keluar', Carbon::today())->sum('jumlah');
            } else {
                $barangKeluar = 0; // fallback
            }
        } catch (\Exception $e) {
            Log::warning('DashboardController: gagal menghitung barangKeluar: ' . $e->getMessage());
            $barangKeluar = 0;
        }

        // 4. Produk Stok Rendah (count of products with stok <= 10)
        try {
            $produkLowStock = barang::where('Stok', '<=', 10)->count();
        } catch (\Exception $e) {
            Log::warning('DashboardController: gagal menghitung produkLowStock: ' . $e->getMessage());
            $produkLowStock = 0;
        }

        // 5. List Barang 
        // Mengambil semua data barang untuk ditampilkan di tabel dashboard jika ada
        try {
            if (Schema::hasTable('barang')) {
                $barang = barang::all();
            } else {
                $barang = collect();
            }
        } catch (\Exception $e) {
            Log::warning('DashboardController: gagal mengambil list barang: ' . $e->getMessage());
            $barang = collect();
        }

        // Mengirimkan semua variabel ke view 'dashboard'
        return view('dashboard', compact('barang', 'totalStok', 'barangMasuk', 'barangKeluar', 'produkLowStock'));
    }

    /**
     * API: Ringkasan singkat untuk dashboard (JSON).
     * Dipakai oleh polling JS untuk memperbarui angka tanpa reload.
     */
    public function summary()
    {
        try {
            $barangMasuk = Schema::hasTable('barang_masuk')
                ? BarangMasuk::whereDate('tanggal_masuk', Carbon::today())->sum('jumlah')
                : barang::whereDate('created_at', Carbon::today())->sum('Stok');

            $barangKeluar = Schema::hasTable('barang_keluar')
                ? BarangKeluar::whereDate('tanggal_keluar', Carbon::today())->sum('jumlah')
                : 0;

            return response()->json([
                'barangMasuk' => (int) $barangMasuk,
                'barangKeluar' => (int) $barangKeluar,
            ]);
        } catch (\Exception $e) {
            Log::warning('DashboardController::summary gagal: ' . $e->getMessage());
            return response()->json(['barangMasuk' => 0, 'barangKeluar' => 0], 500);
        }
    }
}