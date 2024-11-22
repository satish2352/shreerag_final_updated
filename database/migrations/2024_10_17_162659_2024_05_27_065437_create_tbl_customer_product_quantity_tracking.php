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
        Schema::create('tbl_customer_product_quantity_tracking', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('business_id');
            $table->unsignedBigInteger('business_details_id');
            $table->unsignedBigInteger('business_application_processes_id');
            $table->unsignedBigInteger('production_id');
            $table->string('completed_quantity')->nullable();
            $table->unsignedBigInteger('quantity_tracking_status')->nullable();
            // $table->unsignedBigInteger('production_status')->nullable();
            //   $table->unsignedBigInteger('logistics_status')->nullable();
            //   $table->unsignedBigInteger('logistics_send_fianance_status')->nullable();
            //   $table->unsignedBigInteger('fianance_status')->nullable();
            //   $table->unsignedBigInteger('dispatch_status')->nullable();
              $table->unsignedBigInteger('dispatch_completed_status')->nullable();
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
        //
    }
};
