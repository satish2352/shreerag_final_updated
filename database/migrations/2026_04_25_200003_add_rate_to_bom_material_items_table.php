<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * T-2026-002 Revision 4 — Add rate column to bom_material_items.
 * Position: after mtr_for_01_nos_trolley, before unit (per spec).
 */
class AddRateToBomMaterialItemsTable extends Migration
{
    public function up()
    {
        Schema::table('bom_material_items', function (Blueprint $table) {
            $table->decimal('rate', 15, 3)->nullable()->after('mtr_for_01_nos_trolley')
                  ->comment('Per-unit rate; auto-filled from tbl_part_item.basic_rate but user-overridable');
        });
    }

    public function down()
    {
        Schema::table('bom_material_items', function (Blueprint $table) {
            $table->dropColumn('rate');
        });
    }
}
