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
        $array_to_be_check = [config('constants.DESIGN_DEPARTMENT.LIST_NEW_REQUIREMENTS_RECEIVED_FOR_DESIGN')];

        $business_received_for_designs = DesignModel::leftJoin('business_application_processes', function ($join) {
            $join->on('designs.business_details_id', '=', 'business_application_processes.business_details_id');
        })
            ->whereIn('business_application_processes.design_status_id', $array_to_be_check)
            ->where('business_application_processes.is_active', true)
            ->where('business_application_processes.is_deleted', 0)
            //   ->distinct('businesses.id')
            ->count();
        $array_to_be_check_send_production = [
            config('constants.DESIGN_DEPARTMENT.LIST_NEW_REQUIREMENTS_RECEIVED_FOR_DESIGN'),
            config('constants.PRODUCTION_DEPARTMENT.LIST_DESIGN_RECEIVED_FOR_PRODUCTION'),
            config('constants.PRODUCTION_DEPARTMENT.LIST_DESIGN_RECIVED_FROM_PRODUCTION_DEPT_REVISED'),
        ];

        $design_sent_for_production = ProductionModel::leftJoin('businesses', function ($join) {
            $join->on('production.business_id', '=', 'businesses.id');
        })
            ->leftJoin('business_application_processes', function ($join) {
                $join->on('production.business_id', '=', 'business_application_processes.business_id');
            })
            ->leftJoin('designs', function ($join) {
                $join->on('production.business_details_id', '=', 'designs.business_id');
            })
            ->whereIn('business_application_processes.production_status_id', $array_to_be_check_send_production)
            ->where('businesses.is_active', true)
            ->where('businesses.is_deleted', 0)
            ->selectRaw('COUNT(DISTINCT businesses.id) as total_count')
            ->value('total_count');


        $accepted_design_production_dept = BusinessApplicationProcesses::where('business_status_id', 1112)->where('design_status_id', 1114)
            ->where('production_status_id', 1114)
            ->where('is_deleted', 0)
            ->where('is_active', 1)->count();
        $rejected_design_production_dept = BusinessApplicationProcesses::leftJoin('production', function ($join) {
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

        return [

            'design_dept_counts' => [
                'business_received_for_designs' => $business_received_for_designs,
                // 'business_design_send_to_product' => $business_design_send_to_product,
                'design_sent_for_production' => $design_sent_for_production,
                'accepted_design_production_dept' => $accepted_design_production_dept,
                'rejected_design_production_dept' => $rejected_design_production_dept,
            ]

        ];
    }
}
