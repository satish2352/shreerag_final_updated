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
                $table->unsignedBigInteger('purchase_orders_id');
                $table->unsignedBigInteger('requisition_id');
                $table->unsignedBigInteger('business_id');
                $table->unsignedBigInteger('production_id');
                $table->string('po_date')->nullable();
                $table->string('vendor_id');
                $table->string('terms_condition')->nullable();
                $table->string('remark')->nullable();
                $table->string('transport_dispatch')->nullable();
                $table->string('image');
                // $table->string('status')->nullable();
                $table->string('quote_no');
                // $table->string('client_name');
                // $table->string('phone_number');
                // $table->string('email');
                $table->string('tax');
                $table->string('invoice_date');
                // $table->string('gst_number');
                $table->string('payment_terms');
                // $table->string('client_address');
                $table->string('discount');
                $table->string('note');
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