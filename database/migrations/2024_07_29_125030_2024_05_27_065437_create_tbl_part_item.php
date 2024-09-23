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
        Schema::create('tbl_part_item', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('part_number');
            $table->text('description');
            $table->text('extra_description')->nullable();
            $table->unsignedBigInteger('unit_id');
            $table->unsignedBigInteger('hsn_id');
            $table->unsignedBigInteger('group_type_id');
            $table->string('basic_rate');
            $table->string('opening_stock');
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
