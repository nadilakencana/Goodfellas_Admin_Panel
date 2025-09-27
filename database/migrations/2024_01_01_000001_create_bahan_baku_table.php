<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('bahan_baku', function (Blueprint $table) {
            $table->id();
            $table->string('nama_bahan');
            $table->integer('stok_porsi')->default(0); // stok dalam porsi
            $table->integer('stok_minimum')->default(0);
            $table->decimal('harga_per_porsi', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bahan_baku');
    }
};