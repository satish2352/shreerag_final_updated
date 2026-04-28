<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBomMaterialItemsTable extends Migration
{
    public function up()
    {
        Schema::create('bom_material_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('business_id')->comment('FK businesses.id');
            $table->unsignedBigInteger('business_details_id')->comment('FK businesses_details.id');
            $table->unsignedBigInteger('design_id')->comment('FK designs.id');
            $table->unsignedBigInteger('estimation_id')->nullable()->comment('Filled when estimation dept edits');
            $table->integer('serial_no')->default(1)->comment('Display order');
            $table->string('product_description', 500);
            $table->decimal('length', 12, 3)->nullable();
            $table->decimal('quantity', 12, 3);
            $table->decimal('total_in_mm', 15, 3)->nullable();
            $table->decimal('mtr_for_01_nos_trolley', 15, 3)->nullable();
            $table->string('unit', 50)->nullable();
            $table->unsignedBigInteger('created_by')->comment('FK users.id — last editor');
            $table->integer('created_dept_role_id')->default(3)->comment('3=design, 15=estimation');
            $table->tinyInteger('is_active')->default(1);
            $table->tinyInteger('is_deleted')->default(0);
            $table->timestamps();

            // Indexes
            $table->index(['business_details_id', 'design_id', 'is_deleted'], 'idx_bom_bd_d_del');
            $table->index(['business_id', 'is_deleted'], 'idx_bom_bus_del');
        });
    }

    public function down()
    {
        Schema::dropIfExists('bom_material_items');
    }
}
