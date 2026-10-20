<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dss_hasil', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->foreignId('ahp_id')->nullable()->constrained('dss_ahp')->nullOnDelete();
            $table->json('poin_mentah')->nullable();
            $table->json('skor_normal')->nullable();
            $table->decimal('skor_akhir', 6, 2)->default(0);
            $table->unsignedInteger('peringkat')->default(0);
            $table->string('kategori', 40)->nullable();
            $table->string('kekuatan_utama', 100)->nullable();
            $table->string('cluster', 60)->nullable();
            $table->string('rekomendasi_peran', 255)->nullable();
            $table->json('gap')->nullable();
            $table->json('kompetensi')->nullable();
            $table->timestamp('dihitung_pada')->nullable();
            $table->timestamps();

            $table->index('skor_akhir');
            $table->index('peringkat');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dss_hasil');
    }
};