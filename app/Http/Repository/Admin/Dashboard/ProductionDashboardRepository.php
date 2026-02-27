<?php

namespace App\Http\Repository\Admin\Dashboard;

use App\Models\{
    BusinessApplicationProcesses,
    CustomerProductQuantityTracking
};


class ProductionDashboardRepository
{
    public function getCounts()
    {
        $design_recived_for_production = BusinessApplicationProcesses::where('business_status_id', 1112)->where('estimation_send_to_production', 1152)
            ->where('off_canvas_status', 33)
            ->where('is_deleted', 0)
            ->where('is_active', 1)->count();

        $accepted_and_sent_to_store = BusinessApplicationProcesses::where('business_status_id', 1112)->where('design_status_id', 1114)
            ->where('production_status_id', 1114)
            ->where('is_deleted', 0)
            ->where('is_active', 1)->count();
        $rejected_design_list_sent = BusinessApplicationProcesses::leftJoin('production', function ($join) {
            $join->on('business_application_processes.business_details_id', '=', 'production.business_details_id');
        })
            ->leftJoin('designs', function ($join) {
                $join->on('business_application_processes.business_details_id', '=', 'designs.business_details_id');
            })
            ->leftJoin('businesses_details', function ($join) {
                $join->on('production.business_details_id', '=', 'businesses_details.id');
            })
            ->leftJoin('businesses', function ($join) {
                $join->on('business_application_processes.business_id', '=', 'businesses.id');
            })
            ->where('business_application_processes.production_status_id', 1115)
            ->where('businesses.is_active', true)
            ->where('businesses.is_deleted', 0)
            ->count();
        $corected_design_list_recived = BusinessApplicationProcesses::where('business_status_id', 1116)->where('design_status_id', 1116)
            ->where('production_status_id', 1116)
            ->where('is_deleted', 0)
            ->where('is_active', 1)->count();

        $material_received_for_production = BusinessApplicationProcesses::leftJoin('production', function ($join) {
            $join->on('business_application_processes.business_details_id', '=', 'production.business_details_id');
        })
            ->leftJoin('designs', function ($join) {
                $join->on('business_application_processes.business_details_id', '=', 'designs.business_details_id');
            })
            ->leftJoin('businesses', function ($join) {
                $join->on('business_application_processes.business_id', '=', 'businesses.id');
            })
            ->leftJoin('businesses_details', function ($join) {
                $join->on('business_application_processes.business_details_id', '=', 'businesses_details.id');
            })
            ->leftJoin('design_revision_for_prod', function ($join) {
                $join->on('business_application_processes.business_details_id', '=', 'design_revision_for_prod.business_details_id');
            })
            ->leftJoin('purchase_orders', function ($join) {
                $join->on('business_application_processes.business_details_id', '=', 'purchase_orders.business_details_id');
            })
            ->where('business_application_processes.off_canvas_status', 17)
            ->where('production.production_status_quantity_tracking', 'incomplete')
            ->where('businesses.is_deleted', 0)
            ->where('business_application_processes.is_active', 1)
            ->count();

        $production_completed_prod_dept = CustomerProductQuantityTracking::leftJoin('tbl_logistics', function ($join) {
            $join->on('tbl_customer_product_quantity_tracking.id', '=', 'tbl_logistics.quantity_tracking_id');
        })
            ->leftJoin('businesses', function ($join) {
                $join->on('tbl_customer_product_quantity_tracking.business_id', '=', 'businesses.id');
            })
            ->where('tbl_customer_product_quantity_tracking.quantity_tracking_status', 3001)
            ->where('businesses.is_active', true)
            ->where('businesses.is_deleted', 0)
            ->count();
        return [

            'production_dept_counts' =>  [
                'design_recived_for_production' => $design_recived_for_production,
                'accepted_and_sent_to_store' => $accepted_and_sent_to_store,
                'rejected_design_list_sent' => $rejected_design_list_sent,
                'corected_design_list_recived' => $corected_design_list_recived,
                'material_received_for_production' => $material_received_for_production,
                'production_completed_prod_dept' => $production_completed_prod_dept,
            ]


        ];
    }
}
