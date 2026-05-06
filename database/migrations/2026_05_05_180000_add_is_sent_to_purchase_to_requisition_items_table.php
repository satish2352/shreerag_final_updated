<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Adds is_sent_to_purchase TINYINT(1) NOT NULL DEFAULT 0 to requisition_items.
     * Backfills all existing rows to 1 (they were inserted before the draft-state
     * workflow existed, so they are all considered "already sent to purchase").
     */
    public function up(): void
    {
        if (!Schema::hasColumn('requisition_items', 'is_sent_to_purchase')) {
            Schema::table('requisition_items', function (Blueprint $table) {
                $table->tinyInteger('is_sent_to_purchase')->default(0)->after('is_deleted');
                $table->index('is_sent_to_purchase', 'ri_is_sent_idx');
            });

            // Backfill: all rows that existed before this migration are already sent.
            DB::table('requisition_items')->update(['is_sent_to_purchase' => 1]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('requisition_items', 'is_sent_to_purchase')) {
            Schema::table('requisition_items', function (Blueprint $table) {
                $table->dropIndex('ri_is_sent_idx');
                $table->dropColumn('is_sent_to_purchase');
            });
        }
    }
};
