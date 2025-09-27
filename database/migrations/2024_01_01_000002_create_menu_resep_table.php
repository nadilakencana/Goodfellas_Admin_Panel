<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('menu_resep', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_menu');
            $table->unsignedBigInteger('id_bahan_baku');
            $table->integer('porsi_diperlukan')->default(1); // berapa porsi bahan baku untuk 1 porsi menu
            $table->timestamps();

            $table->foreign('id_menu')->references('id')->on('menu')->onDelete('cascade');
            $table->foreign('id_bahan_baku')->references('id')->on('bahan_baku')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('menu_resep');
    }
};