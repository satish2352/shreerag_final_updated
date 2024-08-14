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
                $table->unsignedBigInteger('business_details_id');
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
                $table->string('tax_type');
                $table->string('tax_id');
                $table->string('invoice_date');
                // $table->string('gst_number');
                $table->string('payment_terms');
                // $table->string('client_address');
                $table->string('discount');
                $table->string('note');

                $table->date('owner_po_action_date')->nullable();
                $table->string('purchase_status_from_owner')->nullable();
                $table->unsignedBigInteger('purchase_status_from_purchase')->nullable();
                $table->date('purchase_order_mail_submited_to_vendor_date')->nullable();
                $table->date('store_material_recived_for_grn_date')->nullable();
                $table->unsignedBigInteger('security_status_id')->nullable();
                $table->date('security_material_recived_date')->nullable();
                $table->unsignedBigInteger('quality_status_id')->nullable();
                $table->date('quality_material_sent_to_store_date')->nullable();
                $table->string('grn_no')->nullable();
                $table->unsignedBigInteger('store_status_id')->nullable();
                $table->unsignedBigInteger('store_receipt_no')->nullable();
                $table->date('finanace_store_receipt_generate_date')->nullable();
                $table->unsignedBigInteger('finanace_store_receipt_status_id')->nullable();

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