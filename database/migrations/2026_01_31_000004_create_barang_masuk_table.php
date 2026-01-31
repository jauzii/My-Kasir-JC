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
        if (! Schema::hasTable('barang_masuk')) {
            Schema::create('barang_masuk', function (Blueprint $table) {
                $table->id();
                $table->foreignId('barang_id')->constrained('barang')->onDelete('cascade');
                $table->integer('jumlah');
                $table->dateTime('tanggal_masuk')->nullable();
                $table->string('keterangan')->nullable();
                $table->timestamps();
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
        Schema::dropIfExists('barang_masuk');
    }
};