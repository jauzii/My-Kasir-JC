<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangKeluar;
use App\Models\BarangMasuk; // catatan barang masuk
use Carbon\Carbon;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index() {
        $barang = Barang::all();
        return view('barang', compact('barang'));
    }

    public function store(Request $request) 
    {
        $request->validate([
            'NamaProduk' => 'required|string|max:255',
            'Kategori'   => 'required',
            'HargaBeli'  => 'required|numeric',
            'HargaJual'  => 'required|numeric',
            'Stok'       => 'required|integer'
        ]);

        $new = Barang::create([
            'NamaProduk' => $request->NamaProduk,
            'Kategori'   => $request->Kategori,
            'HargaBeli'  => $request->HargaBeli,
            'HargaJual'  => $request->HargaJual,
            'Stok'       => $request->Stok   
        ]);

        // Jika ada stok awal (>0) dan tabel `barang_masuk` tersedia, simpan catatan masuk
        if ($new->Stok > 0 && \Illuminate\Support\Facades\Schema::hasTable('barang_masuk')) {
            try {
                BarangMasuk::create([
                    'barang_id' => $new->id,
                    'jumlah' => $new->Stok,
                    'tanggal_masuk' => Carbon::now(),
                    'keterangan' => 'Stok awal saat pembuatan produk'
                ]);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::warning('Gagal mencatat barang_masuk (store): ' . $e->getMessage());
            }
        }

        return redirect('/barang')->with('success', 'Barang Berhasil Ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'NamaProduk' => 'required|string|max:255',
            'Kategori'   => 'required',
            'HargaBeli'  => 'required|numeric',
            'HargaJual'  => 'required|numeric',
            'Stok'       => 'required|integer'
        ]);

        $barang = Barang::findOrFail($id);

        $oldStok = $barang->Stok;
        $newStok = (int) $request->Stok;

        $barang->update([
            'NamaProduk' => $request->NamaProduk,
            'Kategori'   => $request->Kategori,
            'HargaBeli'  => $request->HargaBeli,
            'HargaJual'  => $request->HargaJual,
            'Stok'       => $newStok
        ]);

        // Jika stok bertambah, catat sebagai BarangMasuk (jika tabel ada)
        if ($newStok > $oldStok && \Illuminate\Support\Facades\Schema::hasTable('barang_masuk')) {
            try {
                BarangMasuk::create([
                    'barang_id' => $barang->id,
                    'jumlah' => $newStok - $oldStok,
                    'tanggal_masuk' => Carbon::now(),
                    'keterangan' => 'Penambahan stok via edit produk'
                ]);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::warning('Gagal mencatat barang_masuk (update): ' . $e->getMessage());
            }
        }

        return redirect('/barang')->with('success', 'Barang Berhasil Diupdate');
    }

    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);
        $barang->delete();

        return redirect('/barang')->with('success', 'Barang Berhasil Dihapus');
    }

    /* --- FITUR BARANG KELUAR (PRODUK) --- */

    public function keluarIndex()
    {
        $barang = Barang::all(); 
        return view('produk', compact('barang'));
    }

    /**
     * Riwayat Barang Keluar (list + filter tanggal)
     */
    public function keluarHistory(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Default: last 30 days
        try {
            $startDate = $startDate ? Carbon::parse($startDate)->format('Y-m-d') : Carbon::now()->subDays(30)->format('Y-m-d');
        } catch (\Exception $e) {
            $startDate = Carbon::now()->subDays(30)->format('Y-m-d');
        }
        try {
            $endDate = $endDate ? Carbon::parse($endDate)->format('Y-m-d') : Carbon::now()->format('Y-m-d');
        } catch (\Exception $e) {
            $endDate = Carbon::now()->format('Y-m-d');
        }

        // Jika tabel tidak ada, beri koleksi kosong
        if (!\Illuminate\Support\Facades\Schema::hasTable('barang_keluar')) {
            $riwayat = collect();
        } else {
            $riwayat = BarangKeluar::with('barang')
                ->whereDate('tanggal_keluar', '>=', $startDate)
                ->whereDate('tanggal_keluar', '<=', $endDate)
                ->orderBy('tanggal_keluar', 'desc')
                ->get();
        }

        return view('produk_riwayat', compact('riwayat', 'startDate', 'endDate'));
    }

    /**
     * Export CSV untuk riwayat barang keluar
     */
    public function keluarExport(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        try {
            $startDate = $startDate ? Carbon::parse($startDate)->format('Y-m-d') : Carbon::now()->subDays(30)->format('Y-m-d');
        } catch (\Exception $e) {
            $startDate = Carbon::now()->subDays(30)->format('Y-m-d');
        }
        try {
            $endDate = $endDate ? Carbon::parse($endDate)->format('Y-m-d') : Carbon::now()->format('Y-m-d');
        } catch (\Exception $e) {
            $endDate = Carbon::now()->format('Y-m-d');
        }

        if (!\Illuminate\Support\Facades\Schema::hasTable('barang_keluar')) {
            return redirect()->route('barang.keluar.history')->with('error', 'Tabel riwayat barang keluar belum tersedia.');
        }

        $query = BarangKeluar::with('barang')
            ->whereDate('tanggal_keluar', '>=', $startDate)
            ->whereDate('tanggal_keluar', '<=', $endDate)
            ->orderBy('tanggal_keluar', 'desc');

        $fileName = 'riwayat_barang_keluar_' . $startDate . '_to_' . $endDate . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
        ];

        $callback = function() use ($query) {
            $output = fopen('php://output', 'w');
            // Headings
            fputcsv($output, ['Tanggal', 'Nama Barang', 'Kategori', 'Jumlah', 'Keterangan']);

            foreach ($query->cursor() as $row) {
                $tanggal = $row->tanggal_keluar;
                $nama = optional($row->barang)->NamaProduk ?? 'Barang Dihapus';
                $kategori = optional($row->barang)->Kategori ?? '-';
                $jumlah = $row->jumlah;
                $keterangan = $row->keterangan ?? '';

                fputcsv($output, [$tanggal, $nama, $kategori, $jumlah, $keterangan]);
            }

            fclose($output);
        };

        return response()->stream($callback, 200, $headers);
    }
    public function keluarStore(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barang,id', 
            'jumlah'    => 'required|integer|min:1',
            'tujuan'    => 'required|string|max:255'
        ]);

        $item = Barang::findOrFail($request->barang_id);

        // Cek kecukupan stok sebelum dikurangi
        if ($item->Stok < $request->jumlah) {
            return back()->with('error', 'Gagal! Stok ' . $item->NamaProduk . ' tidak mencukupi.');
        }

        // Sinkronisasi Database: Update stok
        $item->Stok = $item->Stok - $request->jumlah;
        $item->save(); // Menggunakan save() untuk memastikan perubahan tersimpan

        // Simpan catatan transaksi barang keluar ke tabel `barang_keluar` (jika tersedia)
        try {
            BarangKeluar::create([
                'barang_id' => $item->id,
                'jumlah' => $request->jumlah,
                'tanggal_keluar' => Carbon::now(),
                'keterangan' => $request->tujuan ?? null,
            ]);
        } catch (\Exception $e) {
            // Jika pencatatan gagal, tidak menggagalkan pengurangan stok—tapi catat peringatan
            \Illuminate\Support\Facades\Log::warning('Gagal mencatat barang_keluar: ' . $e->getMessage());
        }

        return redirect('/produk')->with('success', 'Transaksi Berhasil: Stok ' . $item->NamaProduk . ' telah dikurangi.');
    }

    /**
     * Catat Barang Masuk (tambah stok) via modal/form
     */
    public function masukStore(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'jumlah' => 'required|integer|min:1',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $item = Barang::findOrFail($request->barang_id);

        // Tambah stok
        $item->Stok = $item->Stok + (int) $request->jumlah;
        $item->save();

        // Simpan catatan barang_masuk
        try {
            BarangMasuk::create([
                'barang_id' => $item->id,
                'jumlah' => $request->jumlah,
                'tanggal_masuk' => Carbon::now(),
                'keterangan' => $request->keterangan ?? null,
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('Gagal mencatat barang_masuk (masukStore): ' . $e->getMessage());
        }

        return redirect('/produk')->with('success', 'Stok ' . $item->NamaProduk . ' berhasil ditambahkan.');
    }
}