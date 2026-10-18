<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('posts')
            ->where('status', 'edited')
            ->update(['status' => 'draft', 'is_edit' => true]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Edited posts remain drafts if this data normalization is rolled back.
    }
};
