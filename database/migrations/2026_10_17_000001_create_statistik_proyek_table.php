<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('statistik_proyek', function (Blueprint $table) {
            $table->id();
            $table->string('tipe', 20)->index();
            $table->string('nama', 150);
            $table->unsignedInteger('urutan')->default(0);
            $table->timestamps();

            $table->unique(['tipe', 'nama']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('statistik_proyek');
    }
};