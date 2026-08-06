<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * T-2026-059 — Trolley-scale the Store -> Purchase requisition write path.
     *
     * Adds 3 columns to requisition_items so the requisition becomes self-describing
     * about HOW its required_quantity/shortage_quantity were computed:
     *
     *   - trolley_qty            the trolley count actually used to compute this row's
     *                            required_quantity at write time. NULL on legacy rows
     *                            (written before this migration, which never applied any
     *                            trolley scaling at all).
     *   - length                 mirrors bom_material_items.length. BOM legitimately holds
     *                            multiple rows per part_item_id at different lengths; this
     *                            lets a BOM row be re-matched to its OWN requisition_items
     *                            row (by part_item_id + length) instead of the previous
     *                            part_item_id-only key, which collapsed distinct-length BOM
     *                            rows for the same part onto a single map entry. NULL for
     *                            manually-added / production-shortage rows (no BOM length).
     *   - is_qty_trolley_scaled  1 = required_quantity/shortage_quantity are the FINAL
     *                            unit-aware (BomTotalCalculator::isPieceUnit), trolley-scaled
     *                            totals (rows written by this task's fixed write path).
     *                            0 (default, and the value every pre-existing row keeps,
     *                            since ADD COLUMN ... DEFAULT 0 does not retroactively
     *                            reinterpret old data) = legacy row written on the OLD raw,
     *                            non-unit-aware, per-1-trolley basis. Read paths MUST branch
     *                            on this flag (see BomTotalCalculator::effectiveRequiredQuantity()
     *                            / effectiveShortageQuantity()) rather than assume every row
     *                            means the same thing — this is what prevents legacy rows
     *                            from silently being mis-treated as already trolley-scaled.
     *
     * No backfill is performed and none is needed: the correct semantic value for every
     * row that existed before this migration IS 0 (they were never trolley-scaled), which
     * is exactly the column default applied to existing rows by MySQL's ADD COLUMN.
     */
    public function up(): void
    {
        Schema::table('requisition_items', function (Blueprint $table) {
            if (!Schema::hasColumn('requisition_items', 'trolley_qty')) {
                $table->integer('trolley_qty')->nullable()->after('mtr_for_01_nos_trolley')
                    ->comment('Trolley count used to compute required_quantity at write time. NULL on legacy pre-T-2026-059 rows.');
            }
            if (!Schema::hasColumn('requisition_items', 'length')) {
                $table->decimal('length', 12, 3)->nullable()->after('trolley_qty')
                    ->comment('Mirrors bom_material_items.length — lets a BOM row be re-matched to its own requisition_items row by part_item_id+length.');
            }
            if (!Schema::hasColumn('requisition_items', 'is_qty_trolley_scaled')) {
                $table->tinyInteger('is_qty_trolley_scaled')->default(0)->after('length')
                    ->comment('1 = required_quantity/shortage_quantity are final unit-aware, trolley-scaled totals (T-2026-059+). 0 = legacy pre-fix row on the old per-1-trolley/non-unit-aware basis.');
            }
        });

        Schema::table('requisition_items', function (Blueprint $table) {
            if (!$this->indexExists('requisition_items', 'ri_req_part_length_idx')) {
                $table->index(['requisition_id', 'part_item_id', 'length'], 'ri_req_part_length_idx');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requisition_items', function (Blueprint $table) {
            if ($this->indexExists('requisition_items', 'ri_req_part_length_idx')) {
                $table->dropIndex('ri_req_part_length_idx');
            }
        });

        Schema::table('requisition_items', function (Blueprint $table) {
            if (Schema::hasColumn('requisition_items', 'is_qty_trolley_scaled')) {
                $table->dropColumn('is_qty_trolley_scaled');
            }
            if (Schema::hasColumn('requisition_items', 'length')) {
                $table->dropColumn('length');
            }
            if (Schema::hasColumn('requisition_items', 'trolley_qty')) {
                $table->dropColumn('trolley_qty');
            }
        });
    }

    /**
     * Portable-enough index existence check (MySQL) so re-running this migration
     * (e.g. via --path re-apply during development) never fatals on a duplicate index.
     */
    private function indexExists(string $table, string $indexName): bool
    {
        $conn = Schema::getConnection();
        $dbName = $conn->getDatabaseName();
        $rows = $conn->select(
            'SELECT COUNT(1) AS cnt FROM information_schema.statistics WHERE table_schema = ? AND table_name = ? AND index_name = ?',
            [$dbName, $table, $indexName]
        );

        return !empty($rows) && (int) $rows[0]->cnt > 0;
    }
};
