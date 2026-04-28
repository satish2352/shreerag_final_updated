<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPartUnitIdsToBomMaterialItemsTable extends Migration
{
    public function up()
    {
        Schema::table('bom_material_items', function (Blueprint $table) {
            // FK to tbl_part_item.id — nullable so existing rows are not broken
            $table->unsignedBigInteger('part_item_id')->nullable()->after('estimation_id')
                  ->comment('FK tbl_part_item.id — master reference');
            // FK to tbl_unit.id — nullable so existing rows are not broken
            $table->unsignedBigInteger('unit_id')->nullable()->after('unit')
                  ->comment('FK tbl_unit.id — master reference');

            // Index for part_item lookups
            $table->index('part_item_id', 'idx_bom_part_item_id');
            $table->index('unit_id', 'idx_bom_unit_id');
        });
    }

    public function down()
    {
        Schema::table('bom_material_items', function (Blueprint $table) {
            $table->dropIndex('idx_bom_part_item_id');
            $table->dropIndex('idx_bom_unit_id');
            $table->dropColumn(['part_item_id', 'unit_id']);
        });
    }
}
