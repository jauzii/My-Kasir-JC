<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('barang')) {
            Schema::create('barang', function (Blueprint $table) {
                $table->id();
                $table->string('NamaProduk');
                $table->string('Kategori')->nullable();
                $table->bigInteger('HargaBeli')->default(0);
                $table->bigInteger('HargaJual')->default(0);
                $table->integer('Stok')->default(0);
                $table->timestamp('Updated')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('barang');
    }
};