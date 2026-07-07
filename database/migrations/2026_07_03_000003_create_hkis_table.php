<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hkis', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_sertifikat')->unique();
            $table->date('tgl_terbit');
            $table->string('judul_sertifikat');
            $table->enum('jenis_sertifikat', [
                'Hak Cipta',
                'Paten',
                'Paten Sederhana',
                'Merek',
                'Desain Industri',
                'Desain Tata Letak Sirkuit Terpadu',
                'Rahasia Dagang',
                'Varietas Tanaman',
            ]);
            $table->string('pencipta');           // nama pencipta/pemegang
            // user_id = dosen member (nullable = non-member)
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('recommended_by')->nullable(); // manual non-member
            $table->enum('submission_type', ['member', 'non_member'])->default('non_member');
            $table->string('file_sertifikat')->nullable();  // upload PDF sertifikat
            $table->enum('status', ['Draft', 'Publish'])->default('Draft');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hkis');
    }
};
