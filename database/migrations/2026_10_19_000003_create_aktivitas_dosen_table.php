<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aktivitas_dosen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('jenis', 20);
            $table->string('judul', 255);
            $table->unsignedSmallInteger('tahun');
            $table->string('skala', 20)->nullable();
            $table->string('peran', 20)->default('anggota');
            $table->string('sub_jenis', 40)->nullable();
            $table->string('bidang_smart_city', 30)->nullable();
            $table->string('mitra', 150)->nullable();
            $table->boolean('berkelanjutan')->default(false);
            $table->text('keterangan')->nullable();
            $table->string('status', 10)->default('Publish');
            $table->timestamps();

            $table->index(['user_id', 'jenis']);
            $table->index('tahun');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aktivitas_dosen');
    }
};