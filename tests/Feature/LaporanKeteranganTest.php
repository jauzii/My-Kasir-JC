<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\barang;
use App\Models\BarangMasuk;
use Carbon\Carbon;

class LaporanKeteranganTest extends TestCase
{
    use RefreshDatabase;

    public function test_laporan_displays_keterangan_for_barang_masuk()
    {
        $user = User::factory()->create();

        $produk = barang::create([
            'NamaProduk' => 'Produk Laporan',
            'Kategori' => 'lain',
            'HargaBeli' => 1000,
            'HargaJual' => 1500,
            'Stok' => 5
        ]);

        BarangMasuk::create([
            'barang_id' => $produk->id,
            'jumlah' => 10,
            'tanggal_masuk' => Carbon::now()->format('Y-m-d H:i:s'),
            'keterangan' => 'Pengiriman Supplier ABC'
        ]);

        $response = $this->actingAs($user)->get(route('laporan.index', [
            'start_date' => Carbon::now()->format('Y-m-d'),
            'end_date' => Carbon::now()->format('Y-m-d')
        ]));

        $response->assertStatus(200);
        $response->assertSeeText('Pengiriman Supplier ABC');
    }
}
