<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBundlingHasilsTable extends Migration
{
    /**
     * Jalankan migrasi - Buat tabel hasil bundling
     */
    public function up()
    {
        Schema::create('bundling_hasils', function (Blueprint $table) {
            $table->id();
            $table->json('itemset'); // Menyimpan produk dalam bundel (JSON array)
            $table->integer('jumlah_transaksi'); // Jumlah transaksi yang memiliki itemset ini
            $table->decimal('support', 8, 4); // Support dalam persen
            $table->decimal('confidence', 8, 4)->nullable(); // Confidence jika ada rule
            $table->decimal('lift', 8, 4)->nullable(); // Lift jika ada rule
            $table->text('keterangan')->nullable();
            $table->timestamp('tanggal_analisis');
            $table->timestamps();
        });
    }

    /**
     * Kembalikan migrasi - Hapus tabel hasil bundling
     */
    public function down()
    {
        Schema::dropIfExists('bundling_hasils');
    }
}
