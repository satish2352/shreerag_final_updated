<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // --- businesses ---
        Schema::table('businesses', function (Blueprint $table) {
            if (!$this->indexExists('businesses', 'idx_businesses_org_id')) {
                $table->index('organization_id', 'idx_businesses_org_id');
            }
            if (!$this->indexExists('businesses', 'idx_businesses_is_deleted')) {
                $table->index('is_deleted', 'idx_businesses_is_deleted');
            }
            if (!$this->indexExists('businesses', 'idx_businesses_active_deleted')) {
                $table->index(['is_active', 'is_deleted'], 'idx_businesses_active_deleted');
            }
        });

        // --- businesses_details ---
        Schema::table('businesses_details', function (Blueprint $table) {
            if (!$this->indexExists('businesses_details', 'idx_bd_business_id')) {
                $table->index('business_id', 'idx_bd_business_id');
            }
            if (!$this->indexExists('businesses_details', 'idx_bd_deleted_active')) {
                $table->index(['is_deleted', 'is_active'], 'idx_bd_deleted_active');
            }
        });

        // --- business_application_processes ---
        Schema::table('business_application_processes', function (Blueprint $table) {
            if (!$this->indexExists('business_application_processes', 'idx_bap_business_id')) {
                $table->index('business_id', 'idx_bap_business_id');
            }
            if (!$this->indexExists('business_application_processes', 'idx_bap_design_id')) {
                $table->index('design_id', 'idx_bap_design_id');
            }
            if (!$this->indexExists('business_application_processes', 'idx_bap_production_id')) {
                $table->index('production_id', 'idx_bap_production_id');
            }
            if (!$this->indexExists('business_application_processes', 'idx_bap_requisition_id')) {
                $table->index('requisition_id', 'idx_bap_requisition_id');
            }
            if (!$this->indexExists('business_application_processes', 'idx_bap_deleted_active')) {
                $table->index(['is_deleted', 'is_active'], 'idx_bap_deleted_active');
            }
        });

        // --- designs ---
        Schema::table('designs', function (Blueprint $table) {
            if (!$this->indexExists('designs', 'idx_designs_business_id')) {
                $table->index('business_id', 'idx_designs_business_id');
            }
            if (!$this->indexExists('designs', 'idx_designs_deleted_active')) {
                $table->index(['is_deleted', 'is_active'], 'idx_designs_deleted_active');
            }
        });

        // --- design_revision_for_prod ---
        Schema::table('design_revision_for_prod', function (Blueprint $table) {
            if (!$this->indexExists('design_revision_for_prod', 'idx_drp_business_id')) {
                $table->index('business_id', 'idx_drp_business_id');
            }
            if (!$this->indexExists('design_revision_for_prod', 'idx_drp_design_id')) {
                $table->index('design_id', 'idx_drp_design_id');
            }
            if (!$this->indexExists('design_revision_for_prod', 'idx_drp_deleted_active')) {
                $table->index(['is_deleted', 'is_active'], 'idx_drp_deleted_active');
            }
        });

        // --- estimation ---
        Schema::table('estimation', function (Blueprint $table) {
            if (!$this->indexExists('estimation', 'idx_estimation_business_id')) {
                $table->index('business_id', 'idx_estimation_business_id');
            }
            if (!$this->indexExists('estimation', 'idx_estimation_design_id')) {
                $table->index('design_id', 'idx_estimation_design_id');
            }
            if (!$this->indexExists('estimation', 'idx_estimation_deleted_active')) {
                $table->index(['is_deleted', 'is_active'], 'idx_estimation_deleted_active');
            }
        });

        // --- production ---
        Schema::table('production', function (Blueprint $table) {
            if (!$this->indexExists('production', 'idx_production_business_id')) {
                $table->index('business_id', 'idx_production_business_id');
            }
            if (!$this->indexExists('production', 'idx_production_design_id')) {
                $table->index('design_id', 'idx_production_design_id');
            }
            if (!$this->indexExists('production', 'idx_production_deleted')) {
                $table->index('is_deleted', 'idx_production_deleted');
            }
        });

        // --- production_details ---
        Schema::table('production_details', function (Blueprint $table) {
            if (!$this->indexExists('production_details', 'idx_pd_production_id')) {
                $table->index('production_id', 'idx_pd_production_id');
            }
            if (!$this->indexExists('production_details', 'idx_pd_business_id')) {
                $table->index('business_id', 'idx_pd_business_id');
            }
        });

        // --- requisition ---
        Schema::table('requisition', function (Blueprint $table) {
            if (!$this->indexExists('requisition', 'idx_req_business_id')) {
                $table->index('business_id', 'idx_req_business_id');
            }
            if (!$this->indexExists('requisition', 'idx_req_deleted_active')) {
                $table->index(['is_deleted', 'is_active'], 'idx_req_deleted_active');
            }
        });

        // --- purchase_orders ---
        Schema::table('purchase_orders', function (Blueprint $table) {
            if (!$this->indexExists('purchase_orders', 'idx_po_business_id')) {
                $table->index('business_id', 'idx_po_business_id');
            }
            if (!$this->indexExists('purchase_orders', 'idx_po_is_deleted')) {
                $table->index('is_deleted', 'idx_po_is_deleted');
            }
            if (!$this->indexExists('purchase_orders', 'idx_po_approve_active')) {
                $table->index(['is_approve', 'is_active'], 'idx_po_approve_active');
            }
            if (!$this->indexExists('purchase_orders', 'idx_po_production_id')) {
                $table->index('production_id', 'idx_po_production_id');
            }
        });

        // --- purchase_order_details ---
        Schema::table('purchase_order_details', function (Blueprint $table) {
            if (!$this->indexExists('purchase_order_details', 'idx_pod_purchase_id')) {
                $table->index('purchase_id', 'idx_pod_purchase_id');
            }
            if (!$this->indexExists('purchase_order_details', 'idx_pod_is_deleted')) {
                $table->index('is_deleted', 'idx_pod_is_deleted');
            }
        });

        // --- gatepass ---
        Schema::table('gatepass', function (Blueprint $table) {
            if (!$this->indexExists('gatepass', 'idx_gatepass_bd_id')) {
                $table->index('business_details_id', 'idx_gatepass_bd_id');
            }
            if (!$this->indexExists('gatepass', 'idx_gatepass_is_deleted')) {
                $table->index('is_deleted', 'idx_gatepass_is_deleted');
            }
        });

        // --- grn_tbl ---
        Schema::table('grn_tbl', function (Blueprint $table) {
            if (!$this->indexExists('grn_tbl', 'idx_grn_po_id')) {
                $table->index('purchase_orders_id', 'idx_grn_po_id');
            }
            if (!$this->indexExists('grn_tbl', 'idx_grn_gatepass_id')) {
                $table->index('gatepass_id', 'idx_grn_gatepass_id');
            }
            if (!$this->indexExists('grn_tbl', 'idx_grn_is_deleted')) {
                $table->index('is_deleted', 'idx_grn_is_deleted');
            }
        });

        // --- tbl_notification_status ---
        Schema::table('tbl_notification_status', function (Blueprint $table) {
            if (!$this->indexExists('tbl_notification_status', 'idx_notif_business_id')) {
                $table->index('business_id', 'idx_notif_business_id');
            }
            if (!$this->indexExists('tbl_notification_status', 'idx_notif_bd_id')) {
                $table->index('business_details_id', 'idx_notif_bd_id');
            }
        });

        // --- tbl_customer_product_quantity_tracking ---
        Schema::table('tbl_customer_product_quantity_tracking', function (Blueprint $table) {
            if (!$this->indexExists('tbl_customer_product_quantity_tracking', 'idx_cqt_business_id')) {
                $table->index('business_id', 'idx_cqt_business_id');
            }
            if (!$this->indexExists('tbl_customer_product_quantity_tracking', 'idx_cqt_bap_id')) {
                $table->index('business_application_processes_id', 'idx_cqt_bap_id');
            }
            if (!$this->indexExists('tbl_customer_product_quantity_tracking', 'idx_cqt_production_id')) {
                $table->index('production_id', 'idx_cqt_production_id');
            }
            if (!$this->indexExists('tbl_customer_product_quantity_tracking', 'idx_cqt_deleted_active')) {
                $table->index(['is_deleted', 'is_active'], 'idx_cqt_deleted_active');
            }
        });

        // --- tbl_logistics ---
        Schema::table('tbl_logistics', function (Blueprint $table) {
            if (!$this->indexExists('tbl_logistics', 'idx_logistics_business_id')) {
                $table->index('business_id', 'idx_logistics_business_id');
            }
            if (!$this->indexExists('tbl_logistics', 'idx_logistics_bap_id')) {
                $table->index('business_application_processes_id', 'idx_logistics_bap_id');
            }
            if (!$this->indexExists('tbl_logistics', 'idx_logistics_deleted_active')) {
                $table->index(['is_deleted', 'is_active'], 'idx_logistics_deleted_active');
            }
        });

        // --- tbl_dispatch ---
        Schema::table('tbl_dispatch', function (Blueprint $table) {
            if (!$this->indexExists('tbl_dispatch', 'idx_dispatch_business_id')) {
                $table->index('business_id', 'idx_dispatch_business_id');
            }
            if (!$this->indexExists('tbl_dispatch', 'idx_dispatch_bap_id')) {
                $table->index('business_application_processes_id', 'idx_dispatch_bap_id');
            }
            if (!$this->indexExists('tbl_dispatch', 'idx_dispatch_logistics_id')) {
                $table->index('logistics_id', 'idx_dispatch_logistics_id');
            }
            if (!$this->indexExists('tbl_dispatch', 'idx_dispatch_deleted_active')) {
                $table->index(['is_deleted', 'is_active'], 'idx_dispatch_deleted_active');
            }
        });

        // --- tbl_employees ---
        Schema::table('tbl_employees', function (Blueprint $table) {
            if (!$this->indexExists('tbl_employees', 'idx_emp_org_id')) {
                $table->index('organization_id', 'idx_emp_org_id');
            }
            if (!$this->indexExists('tbl_employees', 'idx_emp_role_id')) {
                $table->index('role_id', 'idx_emp_role_id');
            }
            if (!$this->indexExists('tbl_employees', 'idx_emp_deleted_active')) {
                $table->index(['is_deleted', 'is_active'], 'idx_emp_deleted_active');
            }
        });

        // --- tbl_leaves ---
        Schema::table('tbl_leaves', function (Blueprint $table) {
            if (!$this->indexExists('tbl_leaves', 'idx_leaves_employee_id')) {
                $table->index('employee_id', 'idx_leaves_employee_id');
            }
            if (!$this->indexExists('tbl_leaves', 'idx_leaves_org_id')) {
                $table->index('organization_id', 'idx_leaves_org_id');
            }
            if (!$this->indexExists('tbl_leaves', 'idx_leaves_is_approved')) {
                $table->index('is_approved', 'idx_leaves_is_approved');
            }
            if (!$this->indexExists('tbl_leaves', 'idx_leaves_deleted_active')) {
                $table->index(['is_deleted', 'is_active'], 'idx_leaves_deleted_active');
            }
        });

        // --- admin_view ---
        Schema::table('admin_view', function (Blueprint $table) {
            if (!$this->indexExists('admin_view', 'idx_av_business_id')) {
                $table->index('business_id', 'idx_av_business_id');
            }
            if (!$this->indexExists('admin_view', 'idx_av_bd_id')) {
                $table->index('business_details_id', 'idx_av_bd_id');
            }
            if (!$this->indexExists('admin_view', 'idx_av_status_view')) {
                $table->index(['off_canvas_status', 'is_view'], 'idx_av_status_view');
            }
            if (!$this->indexExists('admin_view', 'idx_av_is_deleted')) {
                $table->index('is_deleted', 'idx_av_is_deleted');
            }
        });

        // --- users ---
        Schema::table('users', function (Blueprint $table) {
            if (!$this->indexExists('users', 'idx_users_org_id')) {
                $table->index('org_id', 'idx_users_org_id');
            }
            if (!$this->indexExists('users', 'idx_users_role_id')) {
                $table->index('role_id', 'idx_users_role_id');
            }
            if (!$this->indexExists('users', 'idx_users_active_deleted')) {
                $table->index(['is_active', 'is_deleted'], 'idx_users_active_deleted');
            }
        });

        // --- vendors ---
        Schema::table('vendors', function (Blueprint $table) {
            if (!$this->indexExists('vendors', 'idx_vendors_active_deleted')) {
                $table->index(['is_active', 'is_deleted'], 'idx_vendors_active_deleted');
            }
        });

        // --- tbl_part_item ---
        Schema::table('tbl_part_item', function (Blueprint $table) {
            if (!$this->indexExists('tbl_part_item', 'idx_part_active_deleted')) {
                $table->index(['is_active', 'is_deleted'], 'idx_part_active_deleted');
            }
        });

        // --- tbl_grn_po_quantity_tracking ---
        Schema::table('tbl_grn_po_quantity_tracking', function (Blueprint $table) {
            if (!$this->indexExists('tbl_grn_po_quantity_tracking', 'idx_grn_qty_po_id')) {
                $table->index('purchase_order_id', 'idx_grn_qty_po_id');
            }
            if (!$this->indexExists('tbl_grn_po_quantity_tracking', 'idx_grn_qty_grn_id')) {
                $table->index('grn_id', 'idx_grn_qty_grn_id');
            }
        });

        // --- tbl_delivery_chalan ---
        Schema::table('tbl_delivery_chalan', function (Blueprint $table) {
            if (!$this->indexExists('tbl_delivery_chalan', 'idx_dc_business_id')) {
                $table->index('business_id', 'idx_dc_business_id');
            }
            if (!$this->indexExists('tbl_delivery_chalan', 'idx_dc_vendor_id')) {
                $table->index('vendor_id', 'idx_dc_vendor_id');
            }
            if (!$this->indexExists('tbl_delivery_chalan', 'idx_dc_deleted_active')) {
                $table->index(['is_deleted', 'is_active'], 'idx_dc_deleted_active');
            }
        });

        // --- tbl_delivery_chalan_item_details ---
        Schema::table('tbl_delivery_chalan_item_details', function (Blueprint $table) {
            if (!$this->indexExists('tbl_delivery_chalan_item_details', 'idx_dcid_chalan_id')) {
                $table->index('delivery_chalan_id', 'idx_dcid_chalan_id');
            }
            if (!$this->indexExists('tbl_delivery_chalan_item_details', 'idx_dcid_part_item_id')) {
                $table->index('part_item_id', 'idx_dcid_part_item_id');
            }
        });
    }

    public function down(): void
    {
        $indexes = [
            'businesses'                              => ['idx_businesses_org_id', 'idx_businesses_is_deleted', 'idx_businesses_active_deleted'],
            'businesses_details'                      => ['idx_bd_business_id', 'idx_bd_deleted_active'],
            'business_application_processes'          => ['idx_bap_business_id', 'idx_bap_design_id', 'idx_bap_production_id', 'idx_bap_requisition_id', 'idx_bap_deleted_active'],
            'designs'                                 => ['idx_designs_business_id', 'idx_designs_deleted_active'],
            'design_revision_for_prod'                => ['idx_drp_business_id', 'idx_drp_design_id', 'idx_drp_deleted_active'],
            'estimation'                              => ['idx_estimation_business_id', 'idx_estimation_design_id', 'idx_estimation_deleted_active'],
            'production'                              => ['idx_production_business_id', 'idx_production_design_id', 'idx_production_deleted'],
            'production_details'                      => ['idx_pd_production_id', 'idx_pd_business_id'],
            'requisition'                             => ['idx_req_business_id', 'idx_req_deleted_active'],
            'purchase_orders'                         => ['idx_po_business_id', 'idx_po_is_deleted', 'idx_po_approve_active', 'idx_po_production_id'],
            'purchase_order_details'                  => ['idx_pod_purchase_id', 'idx_pod_is_deleted'],
            'gatepass'                                => ['idx_gatepass_bd_id', 'idx_gatepass_is_deleted'],
            'grn_tbl'                                 => ['idx_grn_po_id', 'idx_grn_gatepass_id', 'idx_grn_is_deleted'],
            'tbl_notification_status'                 => ['idx_notif_business_id', 'idx_notif_bd_id'],
            'tbl_customer_product_quantity_tracking'  => ['idx_cqt_business_id', 'idx_cqt_bap_id', 'idx_cqt_production_id', 'idx_cqt_deleted_active'],
            'tbl_logistics'                           => ['idx_logistics_business_id', 'idx_logistics_bap_id', 'idx_logistics_deleted_active'],
            'tbl_dispatch'                            => ['idx_dispatch_business_id', 'idx_dispatch_bap_id', 'idx_dispatch_logistics_id', 'idx_dispatch_deleted_active'],
            'tbl_employees'                           => ['idx_emp_org_id', 'idx_emp_role_id', 'idx_emp_deleted_active'],
            'tbl_leaves'                              => ['idx_leaves_employee_id', 'idx_leaves_org_id', 'idx_leaves_is_approved', 'idx_leaves_deleted_active'],
            'admin_view'                              => ['idx_av_business_id', 'idx_av_bd_id', 'idx_av_status_view', 'idx_av_is_deleted'],
            'users'                                   => ['idx_users_org_id', 'idx_users_role_id', 'idx_users_active_deleted'],
            'vendors'                                 => ['idx_vendors_active_deleted'],
            'tbl_part_item'                           => ['idx_part_active_deleted'],
            'tbl_grn_po_quantity_tracking'            => ['idx_grn_qty_po_id', 'idx_grn_qty_grn_id'],
            'tbl_delivery_chalan'                     => ['idx_dc_business_id', 'idx_dc_vendor_id', 'idx_dc_deleted_active'],
            'tbl_delivery_chalan_item_details'        => ['idx_dcid_chalan_id', 'idx_dcid_part_item_id'],
        ];

        foreach ($indexes as $table => $tableIndexes) {
            Schema::table($table, function (Blueprint $blueprint) use ($tableIndexes) {
                foreach ($tableIndexes as $index) {
                    if ($this->indexExists($blueprint->getTable(), $index)) {
                        $blueprint->dropIndex($index);
                    }
                }
            });
        }
    }

    private function indexExists(string $table, string $indexName): bool
    {
        $result = DB::select("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$indexName]);
        return count($result) > 0;
    }
};
