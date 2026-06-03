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
        Schema::create('dosen', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel users
            $table->foreignId('dosen_id')->constrained('users')->onDelete('cascade');
            
            $table->string('nama');
            $table->string('jabatan')->nullable();
            $table->string('lokasi')->nullable();
            $table->string('website')->nullable(); // LinkedIn / Google Scholar
            $table->string('foto')->nullable(); // path foto
            $table->text('bidang_keahlian')->nullable();
            $table->text('kelompok_riset')->nullable(); 
            $table->longText('publikasi_penelitian')->nullable();
            $table->longText('publikasi_pengabdian')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dosen');
    }
};
