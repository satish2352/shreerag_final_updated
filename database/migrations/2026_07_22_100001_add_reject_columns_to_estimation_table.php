<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * T-2026-057: Add owner-reject columns to the estimation table.
     *
     * These support the "Reject" branch of the exceed-amount-request owner
     * approval workflow (sibling to the existing "Update Amount" branch which
     * reuses owner_suggested_amount / owner_suggestion_remark / owner_suggested_at
     * / owner_suggested_by — see 2026_04_25_100001_add_exceed_columns_to_estimation_table.php).
     *
     * Dedicated columns (rather than overloading owner_suggested_amount/
     * owner_suggestion_remark/owner_suggested_by) were chosen because:
     *   - owner_suggested_amount is semantically "a proposed new business amount"
     *     which does not apply to a rejection (there is no revised amount).
     *   - The estimation-side list (list-bom-exceed-owner-suggested.blade.php)
     *     needs to unambiguously tell "owner suggested ₹X" apart from
     *     "owner rejected, please revise" in the same table without relying on
     *     a null-amount convention to imply rejection.
     *   - Keeping the two states in separate columns avoids ever clobbering a
     *     genuine owner_suggested_amount value with a rejection remark.
     */
    public function up()
    {
        Schema::table('estimation', function (Blueprint $table) {
            $table->tinyInteger('is_exceed_rejected')->default(0)->after('owner_suggested_by');
            $table->text('exceed_rejected_remark')->nullable()->after('is_exceed_rejected');
            $table->timestamp('exceed_rejected_at')->nullable()->after('exceed_rejected_remark');
            $table->unsignedBigInteger('exceed_rejected_by')->nullable()->after('exceed_rejected_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('estimation', function (Blueprint $table) {
            $table->dropColumn([
                'is_exceed_rejected',
                'exceed_rejected_remark',
                'exceed_rejected_at',
                'exceed_rejected_by',
            ]);
        });
    }
};
