<?php

namespace App\Http\Controllers\Organizations\Logistics;

use App\Http\Controllers\Controller;
use App\Http\Services\Organizations\Logistics\AllListServices;
use Exception;
use App\Models\{
    NotificationStatus
};

class AllListController extends Controller
{

    protected $service;

    public function __construct()
    {
        $this->service = new AllListServices();
    }
    public function getAllCompletedProduction()
    {
        try {
            $data_output = $this->service->getAllCompletedProduction();

            if ($data_output->isEmpty()) {
                return view('organizations.logistics.logisticsdept.list-production-completed', [
                    'data_output' => [],
                    'message' => 'No data found'
                ]);
            }

            $bdIds = $data_output->pluck('business_details_id')->filter()->unique()->values();

            if ($bdIds->isNotEmpty()) {
                NotificationStatus::where('production_completed', 0)
                    ->whereIn('business_details_id', $bdIds)
                    ->update(['production_completed' => 1]);
            }

            return view('organizations.logistics.logisticsdept.list-production-completed', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getAllLogistics()
    {
        try {
            $data_output = $this->service->getAllLogistics();
            return view('organizations.logistics.logisticsdept.list-logistics', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getAllListSendToFiananceByLogistics()
    {
        try {
            $data_output = $this->service->getAllListSendToFiananceByLogistics();
            return view('organizations.logistics.logisticsdept.list-send-to-fianance-by-logistics', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }
}
