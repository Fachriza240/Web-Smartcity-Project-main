<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('publications', function (Blueprint $table) {
            $table->unsignedSmallInteger('tahun')->change();
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->unsignedSmallInteger('tahun')->nullable()->change();
        });

        Schema::table('teams', function (Blueprint $table) {
            $table->string('linkedin', 500)->nullable()->change();
        });

        Schema::table('partners', function (Blueprint $table) {
            $table->string('website', 500)->nullable()->change();
        });
    }

    public function down(): void
    {
        foreach (['teams' => 'linkedin', 'partners' => 'website'] as $table => $column) {
            foreach (DB::table($table)->select($column)->cursor() as $row) {
                if (mb_strlen((string) $row->{$column}) > 255) {
                    throw new RuntimeException(
                        "Rollback dibatalkan: {$table}.{$column} berisi URL lebih dari 255 karakter."
                    );
                }
            }
        }

        foreach (['publications', 'projects'] as $table) {
            $invalid = DB::table($table)
                ->whereNotNull('tahun')
                ->where('tahun', '!=', 0)
                ->where(
                    fn ($query) => $query
                        ->where('tahun', '<', 1901)
                        ->orWhere('tahun', '>', 2155)
                )
                ->exists();

            if ($invalid) {
                throw new RuntimeException(
                    "Rollback dibatalkan: {$table}.tahun tidak sesuai rentang tipe YEAR MySQL."
                );
            }
        }

        Schema::table('publications', function (Blueprint $table) {
            $table->year('tahun')->change();
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->year('tahun')->nullable()->change();
        });

        Schema::table('teams', function (Blueprint $table) {
            $table->string('linkedin', 255)->nullable()->change();
        });

        Schema::table('partners', function (Blueprint $table) {
            $table->string('website', 255)->nullable()->change();
        });
    }
};