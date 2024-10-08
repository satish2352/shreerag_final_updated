<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('production_details', function (Blueprint $table) {
            // $table->unsignedBigInteger('business_details_id');
            $table->bigIncrements('id');
            $table->unsignedBigInteger('business_id');
            $table->unsignedBigInteger('design_id');
            $table->unsignedBigInteger('business_details_id');
            $table->unsignedBigInteger('material_send_production')->nullable();
            $table->unsignedBigInteger('production_id');
            $table->unsignedBigInteger('part_item_id')->nullable();
            $table->string('quantity')->nullable();
            $table->string('unit')->nullable();
            $table->boolean('is_approve')->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('production');
    }
};
