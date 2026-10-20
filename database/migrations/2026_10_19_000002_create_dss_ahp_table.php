<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dss_ahp', function (Blueprint $table) {
            $table->id();
            $table->json('matriks');
            $table->json('bobot');
            $table->decimal('lambda_max', 10, 6)->default(0);
            $table->decimal('ci', 10, 6)->default(0);
            $table->decimal('cr', 10, 6)->default(0);
            $table->boolean('konsisten')->default(false);
            $table->boolean('aktif')->default(false)->index();
            $table->string('catatan', 255)->nullable();
            $table->foreignId('dibuat_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dss_ahp');
    }
};