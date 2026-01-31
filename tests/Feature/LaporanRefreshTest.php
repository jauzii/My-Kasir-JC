<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\barang;
use App\Models\BarangMasuk;
use Carbon\Carbon;

class LaporanRefreshTest extends TestCase
{
    use RefreshDatabase;

    public function test_refresh_endpoint_returns_html_rows_including_masuk()
    {
        $user = User::factory()->create();

        $produk = barang::create([
            'NamaProduk' => 'Produk Refresh',
            'Kategori' => 'lain',
            'HargaBeli' => 1000,
            'HargaJual' => 1500,
            'Stok' => 5
        ]);

        BarangMasuk::create([
            'barang_id' => $produk->id,
            'jumlah' => 3,
            'tanggal_masuk' => Carbon::now()->format('Y-m-d H:i:s'),
            'keterangan' => 'RefTest'
        ]);

        $res = $this->actingAs($user)->get(route('laporan.refresh', [
            'start_date' => Carbon::now()->format('Y-m-d'),
            'end_date' => Carbon::now()->format('Y-m-d')
        ]));

        $res->assertStatus(200);
        $res->assertSee('RefTest');
        $res->assertSee('Barang Masuk');
    }
}
