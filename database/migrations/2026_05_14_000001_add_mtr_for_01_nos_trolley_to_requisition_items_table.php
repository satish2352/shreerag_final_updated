<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add mtr_for_01_nos_trolley to requisition_items.
     *
     * This column stores the "Mtr for 01 Nos Trolley" value that store users
     * can set on manually-added shortage rows. BOM-derived rows already carry
     * this value from bom_material_items; persisting it here lets the Purchase
     * department see the trolley measurement on every requisition line item.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('requisition_items', 'mtr_for_01_nos_trolley')) {
            Schema::table('requisition_items', function (Blueprint $table) {
                $table->decimal('mtr_for_01_nos_trolley', 15, 3)
                      ->nullable()
                      ->after('rate');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('requisition_items', 'mtr_for_01_nos_trolley')) {
            Schema::table('requisition_items', function (Blueprint $table) {
                $table->dropColumn('mtr_for_01_nos_trolley');
            });
        }
    }
};
