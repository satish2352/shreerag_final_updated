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
        Schema::create('tbl_logistics', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_id');
            $table->unsignedBigInteger('business_details_id');
            $table->unsignedBigInteger('business_application_processes_id');
            $table->unsignedBigInteger('quantity_tracking_id')->nullable();
            $table->unsignedBigInteger('vehicle_type_id')->nullable();
            $table->unsignedBigInteger('transport_name_id')->nullable();
            $table->string('from_place')->nullable();
            $table->string('to_place')->nullable();
            $table->string('truck_no')->nullable();
            $table->string('remark')->nullable();
            $table->boolean('is_approve')->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_deleted')->default(false);
            $table->softDeletes();
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
