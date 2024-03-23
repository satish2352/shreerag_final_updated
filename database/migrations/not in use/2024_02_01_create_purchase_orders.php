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
        Schema::create('purchase_orders', function (Blueprint $table) {
                $table->id();
                $table->string('po_date')->nullable();
                $table->string('vendor_id')->nullable();
                $table->string('terms_condition')->nullable();
                $table->string('remark')->nullable();
                $table->string('transport_dispatch')->nullable();
                $table->string('image');
                $table->string('status')->nullable();
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
        Schema::dropIfExists('purchase_orders');
    }
};