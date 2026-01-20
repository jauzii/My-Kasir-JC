<?php

namespace App\Http\Controllers;

use App\Models\barang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index() {
        $barang = barang::all();
        return view('barang', compact('barang'));
    }

    // Ini fungsi yang dicari oleh Route::post('/barang/simpan', [BarangController::class, 'store'])
    public function store(Request $request) 
    {
        $request->validate([
            'NamaProduk' => 'required|string|max:255',
            'Kategori'   => 'required',
            'HargaBeli'  => 'required|numeric',
            'HargaJual'  => 'required|numeric',
            'Stok'       => 'required|integer'
        ]);

        barang::create([
            'NamaProduk' => $request->NamaProduk,
            'Kategori'   => $request->Kategori,
            'HargaBeli'  => $request->HargaBeli,
            'HargaJual'  => $request->HargaJual,
            'Stok'       => $request->Stok   
        ]);

        return redirect('/barang')->with('success', 'Barang Berhasil Ditambahkan');
    }
}