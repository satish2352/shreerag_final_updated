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
        Schema::create('purchase_order_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('purchase_id');
            $table->string('part_no_id');
            $table->string('description');
            // $table->string('qc_check_remark');
            $table->string('discount');
            $table->string('quantity');
            $table->string('unit');
            $table->unsignedBigInteger('hsn_id');
            $table->string('actual_quantity');
            $table->string('accepted_quantity');
            $table->string('rejected_quantity');
            $table->string('rate');
            $table->string('amount');
            $table->string('is_deleted')->default(false);
            $table->boolean('is_active')->default(true);
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
        Schema::dropIfExists('purchase_order_details');
    }
};
