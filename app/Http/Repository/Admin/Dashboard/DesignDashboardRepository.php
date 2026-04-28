<?php

namespace App\Http\Repository\Admin\Dashboard;

use App\Models\{
    BusinessApplicationProcesses,
    DesignModel,
    ProductionModel
};

class DesignDashboardRepository
{
    public function getCounts()
    {
        // 1. New requirements received — awaiting design work
        $array_to_be_check = [config('constants.DESIGN_DEPARTMENT.LIST_NEW_REQUIREMENTS_RECEIVED_FOR_DESIGN')];
        $business_received_for_designs = DesignModel::leftJoin('business_application_processes',
                'designs.business_details_id', '=', 'business_application_processes.business_details_id')
            ->whereIn('business_application_processes.design_status_id', $array_to_be_check)
            ->where('business_application_processes.is_active', true)
            ->where('business_application_processes.is_deleted', 0)
            ->count();

        // 2. Designs submitted to estimation (design+BOM uploaded, sent to estimation dept)
        $designs_sent_to_estimation = BusinessApplicationProcesses::where('off_canvas_status', 12)
            ->where('is_active', 1)->where('is_deleted', 0)->count();

        // 3. Designs sent to production (from estimation onwards)
        $array_to_be_check_send_production = [
            config('constants.DESIGN_DEPARTMENT.LIST_NEW_REQUIREMENTS_RECEIVED_FOR_DESIGN'),
            config('constants.PRODUCTION_DEPARTMENT.LIST_DESIGN_RECEIVED_FOR_PRODUCTION'),
            config('constants.PRODUCTION_DEPARTMENT.LIST_DESIGN_RECIVED_FROM_PRODUCTION_DEPT_REVISED'),
        ];
        $design_sent_for_production = ProductionModel::leftJoin('businesses',
                'production.business_id', '=', 'businesses.id')
            ->leftJoin('business_application_processes',
                'production.business_id', '=', 'business_application_processes.business_id')
            ->leftJoin('designs',
                'production.business_details_id', '=', 'designs.business_id')
            ->whereIn('business_application_processes.production_status_id', $array_to_be_check_send_production)
            ->where('businesses.is_active', true)
            ->where('businesses.is_deleted', 0)
            ->selectRaw('COUNT(DISTINCT businesses.id) as total_count')
            ->value('total_count');

        // 4. Accepted by production
        $accepted_design_production_dept = BusinessApplicationProcesses::where('business_status_id', 1112)
            ->where('design_status_id', 1114)
            ->where('production_status_id', 1114)
            ->where('is_deleted', 0)->where('is_active', 1)->count();

        // 5. Rejected by production — needs correction
        $rejected_design_production_dept = BusinessApplicationProcesses::leftJoin('production',
                'business_application_processes.business_details_id', '=', 'production.business_details_id')
            ->leftJoin('designs',
                'business_application_processes.business_details_id', '=', 'designs.business_details_id')
            ->leftJoin('businesses_details',
                'production.business_details_id', '=', 'businesses_details.id')
            ->leftJoin('businesses',
                'business_application_processes.business_id', '=', 'businesses.id')
            ->where('business_application_processes.production_status_id', 1115)
            ->where('businesses.is_active', true)
            ->where('businesses.is_deleted', 0)
            ->count();

        // 6. Corrected design resubmitted to estimation after production rejection
        $corrected_design_sent = BusinessApplicationProcesses::where('off_canvas_status', 14)
            ->where('is_active', 1)->where('is_deleted', 0)->count();

        return [
            'design_dept_counts' => [
                'business_received_for_designs'   => $business_received_for_designs,
                'designs_sent_to_estimation'       => $designs_sent_to_estimation,
                'design_sent_for_production'       => $design_sent_for_production,
                'accepted_design_production_dept'  => $accepted_design_production_dept,
                'rejected_design_production_dept'  => $rejected_design_production_dept,
                'corrected_design_sent'            => $corrected_design_sent,
            ]
        ];
    }
}
