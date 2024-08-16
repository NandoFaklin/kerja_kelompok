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
        // Menggunakan schema builder untuk mengubah enum
        Schema::table('users', function (Blueprint $table) {
            // Mengganti kolom role dengan enum yang diperbarui
            $table->enum('role', ['Mahasiswa', 'Dosen', 'Admin'])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Mengembalikan enum role ke nilai sebelumnya
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['Mahasiswa', 'Dosen'])->change();
        });
    }
};
