<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * T-2026-060 — Stock Daily Report reconciliation.
 *
 * `production_details` has no column that records the exact moment stock was
 * actually deducted for a "done" row (only `created_at`, which is when the
 * pending placeholder row was first created — often long before the deduction
 * decision — and `updated_at`, which is touched by *any* save on the row).
 * The Stock Daily Report's "issue" ledger leg needs a stable transaction date
 * that is set exactly once, at the moment stock is genuinely deducted, and
 * never moves on unrelated re-saves.
 *
 * `issued_at` is set in the same save() call that already flips
 * quantity_minus_status to 'done' and material_send_production to 1, at each
 * of the 3 real stock-deduction write sites for this table:
 *   1. StoreRepository::updateProductMaterialWiseAddNewReq() — the
 *      "existing entry" branch (~line 470).
 *   2. StoreRepository::updateProductMaterialWiseAddNewReq() — the
 *      "newly created entry" branch (~line 528).
 *   3. StoreController::issueAvailableMaterials() — route
 *      POST /issue-available-materials (~line 1444).
 * All 3 sites deduct ItemStock.quantity and flip the identical
 * quantity_minus_status='done' + material_send_production=1 predicate in the
 * same save/transaction, so `issued_at` is guaranteed to be set exactly once
 * per row, atomically with the real event, regardless of which of the 3
 * routes performed the issuance. Nullable + no default so existing/legacy
 * 'done' rows (written before this migration) are visibly distinguishable;
 * the report falls back to `updated_at` only for those.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('production_details', function (Blueprint $table) {
            $table->timestamp('issued_at')->nullable()->after('quantity_minus_status');
        });

        Schema::table('production_details', function (Blueprint $table) {
            $table->index('issued_at', 'idx_production_details_issued_at');
            $table->index(['quantity_minus_status', 'material_send_production'], 'idx_production_details_issue_predicate');
        });
    }

    public function down(): void
    {
        Schema::table('production_details', function (Blueprint $table) {
            $table->dropIndex('idx_production_details_issued_at');
            $table->dropIndex('idx_production_details_issue_predicate');
        });

        Schema::table('production_details', function (Blueprint $table) {
            $table->dropColumn('issued_at');
        });
    }
};
