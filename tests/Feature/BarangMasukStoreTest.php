<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\barang;
use App\Models\BarangMasuk;

class BarangMasukStoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_masuk_store_increases_stock_and_creates_record()
    {
        $user = User::factory()->create();

        $produk = barang::create([
            'NamaProduk' => 'Test Masuk',
            'Kategori' => 'lain',
            'HargaBeli' => 1000,
            'HargaJual' => 1500,
            'Stok' => 10
        ]);

        $response = $this->actingAs($user)->post(route('barang.masuk.store'), [
            'barang_id' => $produk->id,
            'jumlah' => 20,
            'keterangan' => 'Pengiriman Supplier'
        ]);

        $response->assertRedirect('/produk');

        $this->assertDatabaseHas('barang_masuk', [
            'barang_id' => $produk->id,
            'jumlah' => 20
        ]);

        $this->assertDatabaseHas('barang', [
            'id' => $produk->id,
            'Stok' => 30
        ]);
    }
}
