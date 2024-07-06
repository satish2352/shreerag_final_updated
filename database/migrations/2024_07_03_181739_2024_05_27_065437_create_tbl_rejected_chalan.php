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
        Schema::create('tbl_rejected_chalan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchase_orders_id');
            $table->unsignedBigInteger('grn_id');
            $table->string('chalan_no')->nullable();
            $table->string('reference_no')->nullable();
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
