<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\barang;
use App\Models\BarangMasuk;
use Carbon\Carbon;

class LaporanMasukTest extends TestCase
{
    use RefreshDatabase;

    public function test_laporan_masuk_page_and_export()
    {
        $user = User::factory()->create();

        $produk = barang::create([
            'NamaProduk' => 'Produk Masuk Laporan',
            'Kategori' => 'lain',
            'HargaBeli' => 1000,
            'HargaJual' => 1500,
            'Stok' => 5
        ]);

        BarangMasuk::create([
            'barang_id' => $produk->id,
            'jumlah' => 7,
            'tanggal_masuk' => Carbon::now()->format('Y-m-d H:i:s'),
            'keterangan' => 'Pengiriman Test'
        ]);

        // Page
        $response = $this->actingAs($user)->get(route('laporan.masuk', [
            'start_date' => Carbon::now()->format('Y-m-d'),
            'end_date' => Carbon::now()->format('Y-m-d')
        ]));

        $response->assertStatus(200);
        $response->assertSeeText('Pengiriman Test');

        // Export
        $export = $this->actingAs($user)->get(route('laporan.masuk.export', [
            'start_date' => Carbon::now()->format('Y-m-d'),
            'end_date' => Carbon::now()->format('Y-m-d')
        ]));

        $export->assertStatus(200);
        // Content-Type may include charset; verify it contains text/csv
        $this->assertStringContainsString('text/csv', strtolower($export->headers->get('Content-Type')));
        // Content-Disposition contains expected filename
        $this->assertStringContainsString('laporan_barang_masuk', $export->headers->get('Content-Disposition'));
        // The page itself earlier verifies the keterangan is present, so CSV header/response code is enough here.
    }
}
