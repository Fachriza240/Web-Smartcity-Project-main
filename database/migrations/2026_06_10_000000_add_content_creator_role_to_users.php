<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->setRoles(['admin', 'dosen', 'content_creator']);
    }

    public function down(): void
    {
        $this->setRoles(['admin', 'dosen']);
    }

    private function setRoles(array $roles): void
    {
        if (! Schema::hasTable('users')) {
            return;
        }

        if (in_array(DB::getDriverName(), ['mysql', 'mariadb'], true)) {
            $list = implode(',', array_map(fn ($role) => "'{$role}'", $roles));
            DB::statement("ALTER TABLE `users` MODIFY `role` ENUM({$list}) NOT NULL DEFAULT 'dosen'");

            return;
        }

        Schema::table('users', function (Blueprint $table) use ($roles) {
            $table->enum('role', $roles)->default('dosen')->change();
        });
    }
};
