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
        Schema::create('tbl_returnable_chalan_item_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('returnable_chalan_id')->nullable();
            $table->unsignedBigInteger('part_item_id')->nullable();
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->unsignedBigInteger('hsn_id')->nullable();
            $table->unsignedBigInteger('process_id')->nullable();
            $table->string('quantity')->nullable();
            $table->string('size')->nullable();
            $table->string('rate')->nullable();
            $table->string('amount')->nullable();
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
