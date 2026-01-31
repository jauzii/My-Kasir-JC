<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    /**
     * Memastikan Laravel mencari tabel 'barang', bukan 'barangs'.
     * Ini akan memperbaiki error QueryException yang muncul di gambar Anda.
     */
    protected $table = 'barang';

    protected $fillable = [
        'NamaProduk',
        'Kategori',
        'HargaBeli',
        'HargaJual',
        'Stok',
        'Updated'
    ];

    /**
     * Nonaktifkan timestamps otomatis (created_at & updated_at) agar tidak error.
     * Gunakan ini karena tabel Anda menggunakan kolom 'Updated' secara manual.
     */
    public $timestamps = false;
}