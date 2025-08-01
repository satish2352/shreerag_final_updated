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
        Schema::create('estimation', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('business_details_id');
            $table->unsignedBigInteger('business_id');
            $table->unsignedBigInteger('design_id');
             $table->unsignedBigInteger('total_estimation_amount')->nullable();
            $table->unsignedBigInteger('is_approved_estimation')->nullable();
            $table->string('production_status_quantity_tracking')->nullable();
            $table->string('store_status_quantity_tracking')->nullable();
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
        Schema::dropIfExists('estimation');
    }
};
