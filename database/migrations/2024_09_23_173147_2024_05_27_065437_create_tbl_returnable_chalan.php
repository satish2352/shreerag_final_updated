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
        Schema::create('tbl_returnable_chalan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('vendor_id')->nullable();
            $table->unsignedBigInteger('business_id')->nullable();
            $table->unsignedBigInteger('transport_id')->nullable();
            $table->unsignedBigInteger('vehicle_id')->nullable();
            $table->string('plant_id')->nullable();
            $table->unsignedBigInteger('tax_id')->nullable();
            $table->string('tax_type')->nullable();
            $table->string('vehicle_number')->nullable();
            $table->string('po_date')->nullable();
            $table->string('dc_date')->nullable();
            $table->string('dc_number')->nullable();
            $table->string('lr_number')->nullable();
            $table->string('remark')->nullable();
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
