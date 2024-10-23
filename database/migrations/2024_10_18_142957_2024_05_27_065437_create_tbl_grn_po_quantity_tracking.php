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
        {
            Schema::create('tbl_grn_po_quantity_tracking', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('purchase_id')->nullable();
                $table->unsignedBigInteger('purchase_order_id')->nullable();
                $table->unsignedBigInteger('purchase_order_details_id')->nullable();
                $table->unsignedBigInteger('grn_id')->nullable();
                $table->string('part_no_id')->nullable();
                $table->string('description')->nullable();
                // $table->string('qc_check_remark');
                $table->string('due_date')->nullable();
                $table->string('quantity')->nullable();
                $table->string('unit')->nullable();
                $table->string('actual_quantity')->nullable();
                $table->string('accepted_quantity')->nullable();
                $table->string('rejected_quantity')->nullable();
                $table->string('rate')->nullable()->nullable();
                $table->string('amount')->nullable()->nullable();
                $table->string('is_deleted')->default(false);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }   
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
