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
        Schema::create('tbl_notification_status', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_id');
            $table->unsignedBigInteger('business_details_id');
            $table->string('off_canvas_status')->nullable();
        $table->boolean('design_is_view')->default(0);
        $table->boolean('prod_is_view')->default(0);
        $table->boolean('prod_design_accepted')->default(0);
        $table->boolean('prod_design_rejected')->default(0);
        $table->boolean('designer_is_view_accepted_design')->default(0);
        $table->boolean('design_is_view_resended')->default(0);
        $table->boolean('prod_is_view_revised')->default(0);
        $table->boolean('prod_is_view_material_received')->default(0);
        $table->boolean('store_is_view')->default(0);
        $table->boolean('purchase_is_view')->default(0);
        $table->boolean('visible_purchase_quality_to_store')->default(0);
        $table->boolean('purchase_order_is_view_po')->default(0);
        $table->boolean('po_is_approved_owner_view')->default(0);
        $table->boolean('purchase_order_is_rejected_view')->default(0);
        $table->boolean('purchase_order_is_accepted_by_view')->default(0);
        $table->boolean('po_send_to_vendor')->default(0);
        $table->boolean('po_send_to_vendor_visible_security')->default(0);
        $table->boolean('quality_po_material_visible')->default(0);
        $table->boolean('security_create_date_pass')->default(0);
        $table->boolean('quality_create_grn')->default(0);
        $table->boolean('received_material_to_quality')->default(0);
        $table->boolean('material_received_from_store')->default(0);
        $table->boolean('production_completed')->default(0);
        $table->boolean('logistics_to_fianance_visible')->default(0);
        $table->boolean('fianance_to_dispatch_visible')->default(0);
        $table->boolean('dispatch_completed')->default(0);
        $table->boolean('prod_store_sr_gr_send_fianance')->default(0);
        $table->boolean('prod_fianance_sr_gr_send_owner')->default(0);
        // $table->boolean('quality_create_grn')->default(0);

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
        //
    }
};
