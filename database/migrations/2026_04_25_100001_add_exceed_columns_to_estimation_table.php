<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add exceed-amount workflow columns to the estimation table.
     *
     * These 6 columns support the owner-approval flow when an estimator submits
     * a total_estimation_amount that exceeds the business total_amount limit.
     * No new tables are created; the existing estimation table is extended.
     */
    public function up()
    {
        Schema::table('estimation', function (Blueprint $table) {
            $table->tinyInteger('is_exceed_pending')->default(0)->after('is_approved_estimation');
            $table->text('exceed_remark')->nullable()->after('is_exceed_pending');
            $table->decimal('owner_suggested_amount', 15, 2)->nullable()->after('exceed_remark');
            $table->text('owner_suggestion_remark')->nullable()->after('owner_suggested_amount');
            $table->timestamp('owner_suggested_at')->nullable()->after('owner_suggestion_remark');
            $table->unsignedBigInteger('owner_suggested_by')->nullable()->after('owner_suggested_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('estimation', function (Blueprint $table) {
            $table->dropColumn([
                'is_exceed_pending',
                'exceed_remark',
                'owner_suggested_amount',
                'owner_suggestion_remark',
                'owner_suggested_at',
                'owner_suggested_by',
            ]);
        });
    }
};
