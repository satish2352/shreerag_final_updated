<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * T-2026-059 iteration 3 — module_tester's coverage-audit Gap 2 fix (chosen option:
     * structural fix via a unique index, not just a documented limitation).
     *
     * `requisition.business_details_id` had NO unique constraint at the DB layer, even
     * though every write path (`StoreController::storeShortageRequisition()`,
     * `StoreRepository`) uses an identical `Requisition::where('business_details_id', ...)
     * ->first()` find-or-create guard before ever creating a new row — i.e. the
     * application already treats this column as unique-per-project by convention, but
     * nothing enforced it. Because neither read uses a locking SELECT, two
     * near-simultaneous "first send" requests for the SAME project (a genuine TOCTOU
     * race under MySQL's default REPEATABLE READ isolation, not reachable via any
     * current UI action but reachable via direct DB access or a future code path) could
     * both see "no requisition yet" and both insert their own row — after which
     * `AllListRepository::getAllListMaterialSentToPurchase()`'s per-project
     * `MAX(requisition.id)` design would silently and PERMANENTLY hide the OLDER
     * requisition's entire item set from the "sent to purchase" listing, reproducing
     * the exact "rows silently dropped" defect class this task exists to fix.
     *
     * Verified safe before writing this migration: a direct COUNT/GROUP BY query against
     * the real dev DB confirmed ZERO existing business_details_id values with more than
     * one requisition row, so this ADD UNIQUE INDEX cannot fail against current data.
     * Also confirmed compatible with the one place a requisition row is ever soft-deleted
     * (`BusinessRepository`'s whole-business soft-delete cascades
     * `is_deleted=1` onto its requisition row): the find-or-create guard above does NOT
     * filter on is_deleted, so it already treats a soft-deleted requisition as "exists"
     * and never attempts to insert a second row for that business_details_id even in
     * that case — this unique index does not change or restrict that existing behavior,
     * it only closes the race window for the normal (never-yet-created) case.
     */
    public function up(): void
    {
        if (!$this->indexExists('requisition', 'requisition_business_details_id_unique')) {
            Schema::table('requisition', function (Blueprint $table) {
                $table->unique('business_details_id', 'requisition_business_details_id_unique');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if ($this->indexExists('requisition', 'requisition_business_details_id_unique')) {
            Schema::table('requisition', function (Blueprint $table) {
                $table->dropUnique('requisition_business_details_id_unique');
            });
        }
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
