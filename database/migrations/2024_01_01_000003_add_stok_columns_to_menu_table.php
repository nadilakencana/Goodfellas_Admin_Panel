<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('menu', function (Blueprint $table) {
            $table->integer('stok_tersedia')->default(0);
            $table->integer('stok_minimum')->default(0);
            $table->enum('tipe_stok', ['bahan_baku', 'manual'])->default('manual');
        });
    }

    public function down()
    {
        Schema::table('menu', function (Blueprint $table) {
            $table->dropColumn(['stok_tersedia', 'stok_minimum', 'tipe_stok']);
        });
    }
};