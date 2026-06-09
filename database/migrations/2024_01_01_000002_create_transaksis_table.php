<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransaksisTable extends Migration
{
    /**
     * Jalankan migrasi - Buat tabel transaksi
     */
    public function up()
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_transaksi')->unique();
            $table->date('tanggal_transaksi');
            $table->string('nama_pelanggan');
            $table->text('alamat')->nullable();
            $table->decimal('total_harga', 12, 2);
            $table->decimal('diskon', 12, 2)->default(0);
            $table->decimal('harga_akhir', 12, 2);
            $table->enum('status', ['selesai', 'batal'])->default('selesai');
            $table->timestamps();
        });
    }

    /**
     * Kembalikan migrasi - Hapus tabel transaksi
     */
    public function down()
    {
        Schema::dropIfExists('transaksis');
    }
}
