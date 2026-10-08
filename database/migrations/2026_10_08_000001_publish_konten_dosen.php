<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['publications', 'hkis'] as $table) {
            DB::table($table)
                ->where('submission_type', 'member')
                ->where('status', 'Draft')
                ->update(['status' => 'Publish', 'updated_at' => now()]);
        }
    }

    public function down(): void
    {
    }
};