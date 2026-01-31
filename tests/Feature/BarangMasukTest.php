<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\barang;
use App\Models\BarangMasuk;

class BarangMasukTest extends TestCase
{
    use RefreshDatabase;

    public function test_updating_stok_creates_barang_masuk_record()
    {
        // buat user
        $user = User::factory()->create();

        // buat produk awal
        $produk = barang::create([
            'NamaProduk' => 'Test Item',
            'Kategori' => 'makanan',
            'HargaBeli' => 1000,
            'HargaJual' => 1500,
            'Stok' => 10
        ]);

        // Pastikan belum ada catatan barang_masuk (karena Stok awal tercatat pada create di controller, tapi di sini kita bypass controller)
        $this->assertDatabaseCount('barang_masuk', 0);

        // update stok via route (naikkan 5)
        $response = $this->actingAs($user)->put(route('barang.update', ['id' => $produk->id]), [
            'NamaProduk' => $produk->NamaProduk,
            'Kategori' => $produk->Kategori,
            'HargaBeli' => $produk->HargaBeli,
            'HargaJual' => $produk->HargaJual,
            'Stok' => 15
        ]);

        $response->assertRedirect('/barang');

        // Pastikan ada catatan barang_masuk dengan jumlah 5
        $this->assertDatabaseHas('barang_masuk', [
            'barang_id' => $produk->id,
            'jumlah' => 5
        ]);
    }
}
