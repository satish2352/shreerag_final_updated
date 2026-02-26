<?php

namespace App\Http\Controllers\Organizations\Dispatch;

use App\Http\Controllers\Controller;
use App\Http\Services\Organizations\Dispatch\AllListServices;
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
    public function getAllReceivedFromFianance()
    {
        try {
            $data_output = $this->service->getAllReceivedFromFianance();

            if ($data_output->isEmpty()) {
                return view('organizations.dispatch.dispatchdept.list-business-received-from-fianance', [
                    'data_output' => [],
                    'message' => 'No data found'
                ]);
            }

            $bdIds = $data_output->pluck('business_details_id')->filter()->unique()->values();

            if ($bdIds->isNotEmpty()) {
                NotificationStatus::where('fianance_to_dispatch_visible', 0)
                    ->whereIn('business_details_id', $bdIds)
                    ->update(['fianance_to_dispatch_visible' => 1]);
            }

            return view('organizations.dispatch.dispatchdept.list-business-received-from-fianance', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getAllDispatch()
    {
        try {
            $data_output = $this->service->getAllDispatch();
            return view('organizations.dispatch.dispatchdept.list-dispatch', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getAllDispatchClosedProduct()
    {
        try {
            $data_output = $this->service->getAllDispatchClosedProduct();
            return view('organizations.dispatch.dispatchdept.list-dispatch-closed-productwise', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }
}
