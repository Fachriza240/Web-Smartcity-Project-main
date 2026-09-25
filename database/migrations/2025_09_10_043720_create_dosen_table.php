<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dosen', function (Blueprint $table) {
            $table->id();

            $table->foreignId('dosen_id')->constrained('users')->onDelete('cascade');

            $table->string('nama');
            $table->string('jabatan')->nullable();
            $table->string('lokasi')->nullable();
            $table->string('website')->nullable();
            $table->string('foto')->nullable();
            $table->text('bidang_keahlian')->nullable();
            $table->text('kelompok_riset')->nullable();
            $table->longText('publikasi_penelitian')->nullable();
            $table->longText('publikasi_pengabdian')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dosen');
    }
};
