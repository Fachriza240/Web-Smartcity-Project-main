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
        Schema::create('publications', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('penulis');
            $table->year('tahun');
            $table->text('abstrak');
            $table->enum('kategori', ['Jurnal', 'Conference', 'Buku', 'Paten', 'Laporan Penelitian']);
            $table->string('penerbit')->nullable();
            $table->string('doi')->nullable();
            $table->string('pdf_path');
            $table->string('thumbnail_path')->nullable();
            $table->enum('status', ['Draft', 'Publish'])->default('Draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('publications');
    }
};
