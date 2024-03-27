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
        Schema::create('business_application_processes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('business_id')->nullable();
            $table->date('business_sent_date')->nullable();
            $table->unsignedBigInteger('business_status_id')->nullable();
            $table->unsignedBigInteger('design_id')->nullable();
            $table->date('design_sent_date')->nullable();
            $table->unsignedBigInteger('design_status_id')->nullable();
            $table->unsignedBigInteger('production_id')->nullable();
            $table->date('production_sent_date')->nullable();
            $table->unsignedBigInteger('production_status_id')->nullable();
            $table->date('store_material_recived_date')->nullable();
            $table->date('store_material_sent_date')->nullable();
            $table->unsignedBigInteger('store_status_id')->nullable();

            $table->unsignedBigInteger('requisition_id')->nullable();
            $table->date('purchase_dept_req_sent_date')->nullable();
            $table->unsignedBigInteger('purchase_status_id')->nullable();

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
        Schema::dropIfExists('business_application_processes');
    }
};
