<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('barang')->insert([
            [
                'NamaProduk' => 'Laptop ASUS VivoBook',
                'Kategori'   => 'Elektronik',
                'HargaBeli'  => 7500000,
                'HargaJual'  => 8500000,
                'Stok'       => 10,
                'Updated'    => Carbon::now(),
            ],
            [
                'NamaProduk' => 'Mouse Logitech',
                'Kategori'   => 'Aksesoris',
                'HargaBeli'  => 150000,
                'HargaJual'  => 220000,
                'Stok'       => 50,
                'Updated'    => Carbon::now(),
            ],
            [
                'NamaProduk' => 'Keyboard Mechanical',
                'Kategori'   => 'Aksesoris',
                'HargaBeli'  => 450000,
                'HargaJual'  => 600000,
                'Stok'       => 20,
                'Updated'    => Carbon::now(),
            ],
        ]);
    }
}
