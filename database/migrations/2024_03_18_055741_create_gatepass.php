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
        Schema::create('gatepass', function (Blueprint $table) {
            $table->id();            
            $table->string('purchase_orders_id');
            $table->string('gatepass_name');
            $table->string('gatepass_date');
            $table->string('gatepass_time');
            $table->text('remark');
            $table->boolean('is_checked_by_quality')->default(false);
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
        Schema::dropIfExists('gatepass');
    }
};