<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('stok_log', function (Blueprint $table) {
            $table->id();
            $table->enum('tipe', ['menu', 'bahan_baku']);
            $table->unsignedBigInteger('id_item'); // id menu atau id bahan_baku
            $table->enum('tipe_transaksi', ['masuk', 'keluar', 'adjustment']);
            $table->decimal('jumlah_sebelum', 10, 2);
            $table->decimal('jumlah_perubahan', 10, 2);
            $table->decimal('jumlah_sesudah', 10, 2);
            $table->text('keterangan')->nullable();
            $table->unsignedBigInteger('id_order')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('stok_log');
    }
};