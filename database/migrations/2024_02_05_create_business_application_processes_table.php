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
        Schema::create('business_application_processes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('business_id')->nullable();
            $table->unsignedBigInteger('business_details_id');
            $table->date('business_sent_date')->nullable();
            // $table->date('owner_po_action_date')->nullable();
            $table->unsignedBigInteger('business_status_id')->nullable();
            $table->unsignedBigInteger('design_id')->nullable();
            $table->date('design_sent_date')->nullable();
            $table->unsignedBigInteger('design_status_id')->nullable();
            $table->unsignedBigInteger('production_id')->nullable();
            $table->date('production_sent_date')->nullable();
            $table->unsignedBigInteger('production_status_id')->nullable();
            $table->date('store_material_recived_date')->nullable();
            $table->date('store_material_sent_date')->nullable();
            $table->unsignedBigInteger('store_status_id')->nullable();
            $table->unsignedBigInteger('requisition_id')->nullable();
            $table->date('purchase_dept_req_sent_date')->nullable();
            $table->unsignedBigInteger('purchase_status_from_purchase')->nullable();
            $table->string('purchase_order_id')->nullable();
            $table->date('purchase_order_submited_to_owner_date')->nullable();

            // $table->date('purchase_order_mail_submited_to_vendor_date')->nullable();
            // $table->date('store_material_recived_for_grn_date')->nullable();
            // $table->unsignedBigInteger('security_status_id')->nullable();
            // $table->date('security_material_recived_date')->nullable();
            $table->unsignedBigInteger('quality_status_id')->nullable();
            // $table->date('quality_material_sent_to_store_date')->nullable();
            // $table->unsignedBigInteger('grn_no')->nullable();
            $table->unsignedBigInteger('store_receipt_no')->nullable();
            // $table->date('finanace_store_receipt_generate_date')->nullable();
            // $table->unsignedBigInteger('finanace_store_receipt_status_id')->nullable();
            $table->string('logistics_status_id')->nullable();
            $table->string('dispatch_status_id')->nullable();
            
            $table->boolean('is_approve')->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_deleted')->default(false);
            $table->boolean('design_is_view')->default(0);
            $table->boolean('design_is_view_rejected')->default(0);
            $table->boolean('design_is_view_resended')->default(0);
            $table->boolean('prod_is_view')->default(0);
            $table->boolean('prod_is_view_revised')->default(0);
            $table->boolean('prod_is_view_material_received')->default(0);
            $table->boolean('store_is_view')->default(0);
            $table->boolean('purchase_is_view')->default(0);
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
        Schema::dropIfExists('business_application_processes');
    }
};
