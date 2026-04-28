<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Make designs.bom_image nullable so that new records can be saved
     * without a BOM Excel file (the BOM is now captured via the structured
     * BOM Material Items modal introduced in T-2026-002).
     */
    public function up()
    {
        Schema::table('designs', function (Blueprint $table) {
            $table->string('bom_image')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('designs', function (Blueprint $table) {
            $table->string('bom_image')->nullable(false)->change();
        });
    }
};
