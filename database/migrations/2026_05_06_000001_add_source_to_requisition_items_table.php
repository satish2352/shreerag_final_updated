<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Adds a `source` VARCHAR(50) nullable column to requisition_items.
     *
     * Known source values:
     *   NULL              — rows inserted before this column existed (BOM-derived, initial requisition)
     *   'manual_shortage' — rows added via "+Add More" by the Store user after the first requisition was sent
     *   'production_shortage' — rows automatically created when Production submits a material request
     *                           whose required quantity exceeds available stock. These start as drafts
     *                           (is_sent_to_purchase=0) so the Store user can review/edit before sending.
     *
     * No backfill is performed: existing rows keep source=NULL and are treated as BOM/sent rows,
     * which is correct because they were all backfilled to is_sent_to_purchase=1 in the prior migration.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('requisition_items', 'source')) {
            Schema::table('requisition_items', function (Blueprint $table) {
                $table->string('source', 50)->nullable()->after('is_sent_to_purchase')
                      ->comment('NULL=BOM/initial; manual_shortage=+Add More; production_shortage=Production request draft');
                $table->index('source', 'ri_source_idx');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('requisition_items', 'source')) {
            Schema::table('requisition_items', function (Blueprint $table) {
                $table->dropIndex('ri_source_idx');
                $table->dropColumn('source');
            });
        }
    }
};
