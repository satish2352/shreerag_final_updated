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
        Schema::create('grn_tbl', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('purchase_orders_id');
                $table->string('po_date')->nullable();
                $table->string('grn_date')->nullable();
                $table->string('image');
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
        Schema::dropIfExists('grn_tbl');
    }
};