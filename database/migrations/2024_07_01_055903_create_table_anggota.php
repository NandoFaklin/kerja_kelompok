<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('anggota', function (Blueprint $table) {
            $table->id('id_anggota');
            $table->string('anggota'); // Kolom untuk array anggota (gunakan VARCHAR atau TEXT)
            $table->unsignedBigInteger('id_kelompok');
            $table->foreign('id_kelompok')->references('id_kelompok')->on('kelompok');
            // Tambah kolom lain yang kamu butuhkan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_anggota');
    }
};
