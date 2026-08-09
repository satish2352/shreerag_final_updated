<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Indexes that production is missing.
 *
 * Measured against a production snapshot (2026-08-09) rather than guessed. Most of this
 * schema is already sensibly indexed — the transactional tables top out around 1,300 rows
 * and their query patterns are covered. Two real gaps remain:
 *
 * 1. `tbl_area` — 652,587 rows and NOT ONE INDEX, not even a primary key. It is joined
 *    twice (state + city) on the user list and the HR employee list, and scanned whole to
 *    populate the state/city dropdowns on registration and employee add/edit. Measured on
 *    the snapshot:
 *
 *      user/employee list join   9.049 s  ->  0.002 s
 *      state dropdown            0.297 s  ->  0.003 s
 *      city dropdown             0.306 s  ->  0.0003 s
 *
 *    `location_id` is NOT NULL and verified unique across all 652,587 rows, so it is the
 *    natural primary key and the join target. (location_type, parent_id) serves both
 *    dropdown queries — only 741 of the 652k rows are states or cities.
 *
 * 2. `requisition.business_details_id` — no index at all, even though EVERY requisition
 *    write path starts with `Requisition::where('business_details_id', ...)->first()`.
 *    Made UNIQUE rather than a plain key, because that is what
 *    2026_07_31_130000_add_unique_index_to_requisition_business_details_id.php intended;
 *    that migration was never recorded as run on production (see the schema-drift note in
 *    2026_08_09_120000_fix_requisition_items_length_and_mtr_column_types.php — no 2026
 *    migration is). Beyond the lookup cost it closes the TOCTOU race described in that
 *    migration, where two concurrent "first send" requests for one project could each
 *    insert a requisition and permanently hide the older one's items from the
 *    "sent to purchase" listing. Verified against the snapshot: zero business_details_id
 *    values currently have more than one requisition row, so the UNIQUE cannot fail.
 *
 * Deliberately NOT added: a (requisition_id, part_item_id, length) composite on
 * requisition_items. Every query in the codebase already leads with requisition_id, which
 * is indexed, and EXPLAIN on the snapshot resolves those lookups to a single row. It would
 * be write overhead for no measured read gain at present size.
 *
 * Every statement is guarded so this migration is safe to re-run and safe on a database
 * where some of these already exist (e.g. local dev, which did run the 2026 migrations).
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('tbl_area')) {
            if (!$this->indexExists('tbl_area', 'PRIMARY')) {
                DB::statement('ALTER TABLE `tbl_area` ADD PRIMARY KEY (`location_id`)');
            }
            if (!$this->indexExists('tbl_area', 'idx_area_type_parent')) {
                DB::statement('ALTER TABLE `tbl_area` ADD KEY `idx_area_type_parent` (`location_type`, `parent_id`)');
            }
        }

        if (Schema::hasTable('requisition')
            && !$this->indexExists('requisition', 'requisition_business_details_id_unique')
            && !$this->indexExists('requisition', 'idx_req_business_details_id')
        ) {
            // Refuse to add the UNIQUE if the data would violate it — better a clear,
            // actionable failure than a half-applied migration.
            $dupes = DB::table('requisition')
                ->select('business_details_id')
                ->groupBy('business_details_id')
                ->havingRaw('COUNT(*) > 1')
                ->pluck('business_details_id');

            if ($dupes->isNotEmpty()) {
                throw new \RuntimeException(
                    'Cannot add the unique index: requisition has more than one row for business_details_id '
                    . $dupes->implode(', ') . '. Resolve those duplicates first.'
                );
            }

            DB::statement('ALTER TABLE `requisition` ADD UNIQUE `requisition_business_details_id_unique` (`business_details_id`)');
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('requisition') && $this->indexExists('requisition', 'requisition_business_details_id_unique')) {
            DB::statement('ALTER TABLE `requisition` DROP INDEX `requisition_business_details_id_unique`');
        }

        if (Schema::hasTable('tbl_area')) {
            if ($this->indexExists('tbl_area', 'idx_area_type_parent')) {
                DB::statement('ALTER TABLE `tbl_area` DROP INDEX `idx_area_type_parent`');
            }
            // The primary key is intentionally NOT dropped: a 652k-row table with no
            // primary key is a defect in its own right, not a state worth restoring.
        }
    }

    private function indexExists(string $table, string $index): bool
    {
        return count(DB::select(
            'SHOW INDEX FROM `' . $table . '` WHERE Key_name = ?',
            [$index]
        )) > 0;
    }
};
