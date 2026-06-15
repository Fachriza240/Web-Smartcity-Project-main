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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('thumbnail_path')->nullable();
            $table->text('deskripsi');
            $table->string('kategori')->nullable();
            $table->string('partner')->nullable();
            $table->year('tahun')->nullable();
            $table->json('gallery_paths')->nullable();
            $table->string('dokumen_path')->nullable();
            $table->enum('status', ['Draft', 'Publish'])->default('Draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
