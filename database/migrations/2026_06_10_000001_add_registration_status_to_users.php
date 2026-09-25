<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('users') && !Schema::hasColumn('users', 'registration_status')) {
            Schema::table('users', function (Blueprint $table) {
                $table->enum('registration_status', ['pending','approved','rejected'])->default('approved')->after('role');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'registration_status')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('registration_status');
            });
        }
    }
};
