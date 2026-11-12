<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const TABLES = ['publications', 'hkis', 'aktivitas_dosen'];

    public function up(): void
    {
        foreach (self::TABLES as $name) {
            Schema::table($name, function (Blueprint $table) {
                $table->json('sdgs')->nullable();
                $table->json('smart_city_pillars')->nullable();
            });
        }
    }

    public function down(): void
    {
        foreach (self::TABLES as $name) {
            Schema::table($name, function (Blueprint $table) {
                $table->dropColumn(['sdgs', 'smart_city_pillars']);
            });
        }
    }
};
