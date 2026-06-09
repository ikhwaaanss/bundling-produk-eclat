<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProduksTable extends Migration
{
    /**
     * Jalankan migrasi - Buat tabel produk
     */
    public function up()
    {
        Schema::create('produks', function (Blueprint $table) {
            $table->id();
            $table->string('kode_produk')->unique();
            $table->string('nama_produk');
            $table->text('deskripsi')->nullable();
            $table->decimal('harga', 10, 2);
            $table->string('kategori');
            $table->integer('stok')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Kembalikan migrasi - Hapus tabel produk
     */
    public function down()
    {
        Schema::dropIfExists('produks');
    }
}
