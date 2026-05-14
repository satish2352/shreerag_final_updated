<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * T-2026-042: Add trolley_qty to designs (and design_revision_for_prod if it exists).
     * Stores the number of trolleys for a BOM order so the modal can scale
     * per-trolley material quantities to the full-order total.
     * Default 1 preserves backward-compatible single-trolley behaviour for legacy rows.
     */
    public function up(): void
    {
        Schema::table('designs', function (Blueprint $table) {
            $table->unsignedInteger('trolley_qty')->nullable()->default(1)->after('bom_image');
        });

        if (Schema::hasTable('design_revision_for_prod')) {
            Schema::table('design_revision_for_prod', function (Blueprint $table) {
                $table->unsignedInteger('trolley_qty')->nullable()->default(1)->after('bom_image');
            });
        }
    }

    public function down(): void
    {
        Schema::table('designs', function (Blueprint $table) {
            $table->dropColumn('trolley_qty');
        });

        if (Schema::hasTable('design_revision_for_prod')) {
            Schema::table('design_revision_for_prod', function (Blueprint $table) {
                $table->dropColumn('trolley_qty');
            });
        }
    }
};
