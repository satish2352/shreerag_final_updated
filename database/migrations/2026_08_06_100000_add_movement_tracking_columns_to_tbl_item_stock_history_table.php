<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * T-2026-060 — Stock Daily Report reconciliation.
 *
 * `tbl_item_stock_history` previously carried a single ambiguous `quantity`
 * column that meant "delta applied" when written by
 * InventoryRepository::addAll() but "absolute new quantity" when written by
 * InventoryRepository::updateAll() — impossible to use as a real ledger.
 *
 * This migration adds an explicit, unambiguous schema on top of the existing
 * (untouched, kept for backward compatibility) `quantity` column:
 *   - movement_type   : what kind of event produced this row.
 *   - quantity_delta  : signed change applied to tbl_item_stock.quantity by
 *                       this event (NULL for legacy rows written before this
 *                       migration, whose true delta cannot be reconstructed).
 *   - balance_after   : tbl_item_stock.quantity immediately after this event
 *                       (NULL for legacy rows, same reasoning).
 *   - remark          : free-text context, used by the
 *                       `stock:reconcile-opening-balance` artisan command to
 *                       explain synthetic "opening reconciliation" entries.
 *
 * Existing rows (if any, in environments where this table is not empty) are
 * left untouched and default to movement_type='legacy_unspecified' so they
 * remain visibly distinguishable from rows written by the fixed code paths
 * going forward. This migration is purely additive and backward-compatible —
 * no existing column is dropped or renamed.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tbl_item_stock_history', function (Blueprint $table) {
            $table->string('movement_type', 50)
                ->default('legacy_unspecified')
                ->after('part_item_id')
                ->comment('manual_addition|manual_adjustment_set|item_creation_opening_stock|opening_reconciliation|legacy_unspecified');

            $table->decimal('quantity_delta', 15, 3)
                ->nullable()
                ->after('quantity')
                ->comment('Signed delta applied to tbl_item_stock.quantity by this event. NULL for legacy rows.');

            $table->decimal('balance_after', 15, 3)
                ->nullable()
                ->after('quantity_delta')
                ->comment('tbl_item_stock.quantity immediately after this event. NULL for legacy rows.');

            $table->string('remark', 500)
                ->nullable()
                ->after('balance_after');
        });

        Schema::table('tbl_item_stock_history', function (Blueprint $table) {
            $table->index(['part_item_id', 'created_at'], 'idx_item_stock_history_part_created');
            $table->index('movement_type', 'idx_item_stock_history_movement_type');
        });
    }

    public function down(): void
    {
        Schema::table('tbl_item_stock_history', function (Blueprint $table) {
            $table->dropIndex('idx_item_stock_history_part_created');
            $table->dropIndex('idx_item_stock_history_movement_type');
        });

        Schema::table('tbl_item_stock_history', function (Blueprint $table) {
            $table->dropColumn(['movement_type', 'quantity_delta', 'balance_after', 'remark']);
        });
    }
};
