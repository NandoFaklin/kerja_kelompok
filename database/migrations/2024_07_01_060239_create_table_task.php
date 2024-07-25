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
        Schema::create('task', function (Blueprint $table) {
            $table->id('id_task');
            $table->string('nama_task');
            $table->string('nama_user');
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
        Schema::dropIfExists('table_task');
    }
};
