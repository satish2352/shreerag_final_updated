<?php

namespace App\Http\Repository\Admin\Dashboard;

use App\Models\{
    BusinessApplicationProcesses
};

class EstimationDashboardRepository
{
    public function getCounts()
    {
        $design_received = BusinessApplicationProcesses::leftJoin('estimation', function ($join) {
            $join->on('business_application_processes.business_details_id', '=', 'estimation.business_details_id');
        })
            ->leftJoin('businesses_details', function ($join) {
                $join->on('business_application_processes.business_details_id', '=', 'businesses_details.id');
            })
            ->whereNull('business_application_processes.bom_estimation_send_to_owner')
            ->where('business_application_processes.design_send_to_estimation', 1113)
            ->where('business_application_processes.design_status_id', 1113)
            ->count();

        $estimation_accepted_bom = BusinessApplicationProcesses::leftJoin('businesses', function ($join) {
            $join->on('business_application_processes.business_id', '=', 'businesses.id');
        })
            ->where('business_application_processes.owner_bom_accepted', 1150)
            ->whereNull('business_application_processes.estimation_send_to_production')
            ->count();

        $estimation_rejected_bom = BusinessApplicationProcesses::leftJoin('businesses', function ($join) {
            $join->on('business_application_processes.business_id', '=', 'businesses.id');
        })
            ->where('business_application_processes.owner_bom_rejected', 1151)
            ->whereNull('business_application_processes.owner_bom_accepted')
            ->count();

        $estimation_send_tp_production = BusinessApplicationProcesses::leftJoin('businesses', function ($join) {
            $join->on('business_application_processes.business_id', '=', 'businesses.id');
        })
            ->where('business_application_processes.estimation_send_to_production', 1152)
            ->count();

        return [

            'estimation_counts' => [
                'design_received' => $design_received,
                'estimation_accepted_bom' => $estimation_accepted_bom,
                'estimation_rejected_bom' => $estimation_rejected_bom,
                'estimation_send_tp_production' => $estimation_send_tp_production
            ]
        ];
    }
}
