<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add new enum value 'content_creator' to users.role
        // Use raw statement for compatibility across DBs
        if (Schema::hasTable('users')) {
            // For MySQL / MariaDB
            DB::statement("ALTER TABLE `users` MODIFY `role` ENUM('admin','dosen','content_creator') NOT NULL DEFAULT 'dosen'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('users')) {
            DB::statement("ALTER TABLE `users` MODIFY `role` ENUM('admin','dosen') NOT NULL DEFAULT 'dosen'");
        }
    }
};
