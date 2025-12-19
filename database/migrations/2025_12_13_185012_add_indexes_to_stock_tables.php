<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('tbl_item_stock', function (Blueprint $table) {
            $table->index('part_item_id');
            $table->index('updated_at');
            $table->index('quantity');
        });

        Schema::table('tbl_part_item', function (Blueprint $table) {
            $table->index('unit_id');
            $table->index('hsn_id');
            $table->index('group_type_id');
            $table->index('rack_id');
        });
    }

    public function down()
    {
        Schema::table('tbl_item_stock', function (Blueprint $table) {
            $table->dropIndex(['part_item_id']);
            $table->dropIndex(['updated_at']);
            $table->dropIndex(['quantity']);
        });

        Schema::table('tbl_part_item', function (Blueprint $table) {
            $table->dropIndex(['unit_id']);
            $table->dropIndex(['hsn_id']);
            $table->dropIndex(['group_type_id']);
            $table->dropIndex(['rack_id']);
        });
    }
};
