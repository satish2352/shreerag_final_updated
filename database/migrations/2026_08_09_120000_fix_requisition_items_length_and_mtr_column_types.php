<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Corrective migration — production schema drift on `requisition_items`.
 *
 * The columns added by 2026_07_31_120000_add_trolley_scaling_context... and
 * 2026_05_14_000001_add_mtr_for_01_nos_trolley... were never applied through
 * `php artisan migrate` on production (the `migrations` table has no row for
 * them); they were created by hand with the WRONG types:
 *
 *   length                 tinyint(4)     instead of  decimal(12,3)
 *   mtr_for_01_nos_trolley decimal(10,0)  instead of  decimal(15,3)
 *
 * `length` mirrors bom_material_items.length, which is decimal(12,3) and
 * legitimately holds values like 920 / 1325 / 1600 mm. tinyint(4) caps at 127,
 * so with Laravel's `strict => true` connection (STRICT_TRANS_TABLES) every
 * insert of a shortage row whose BOM length exceeds 127 fails with
 * SQLSTATE[22003] "Out of range value for column 'length'". That aborts the
 * whole DB::transaction in StoreController::storeShortageRequisition(), so the
 * requisition is never created and business_application_processes.store_status_id
 * is never set to 1123 — which is exactly the filter the Purchase department's
 * "BOM Received For Purchase" list runs on, hence "No Record Found".
 *
 * decimal(10,0) on mtr_for_01_nos_trolley silently rounds fractional trolley
 * measurements (0.637 -> 1) instead of erroring, corrupting the value Purchase
 * sees on the line item.
 *
 * The original migrations are `Schema::hasColumn`-guarded, so re-running them
 * will NOT repair columns that already exist with the wrong type — this
 * migration issues the ALTERs explicitly.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('requisition_items')) {
            return;
        }

        if (Schema::hasColumn('requisition_items', 'length')) {
            DB::statement(
                "ALTER TABLE `requisition_items`
                 MODIFY `length` DECIMAL(12,3) NULL DEFAULT NULL
                 COMMENT 'Mirrors bom_material_items.length — lets a BOM row be re-matched to its own requisition_items row by part_item_id+length.'"
            );
        }

        if (Schema::hasColumn('requisition_items', 'mtr_for_01_nos_trolley')) {
            DB::statement(
                "ALTER TABLE `requisition_items`
                 MODIFY `mtr_for_01_nos_trolley` DECIMAL(15,3) NULL DEFAULT NULL"
            );
        }
    }

    /**
     * Deliberately not reversed: narrowing `length` back to tinyint(4) would
     * truncate/reject legitimate BOM lengths and re-introduce the defect.
     */
    public function down(): void
    {
        // no-op
    }
};
