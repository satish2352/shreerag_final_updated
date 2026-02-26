<?php

namespace App\Http\Controllers\Organizations\Estimation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Organizations\Estimation\AllListServices;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use Carbon\Carbon;
use Exception;

use App\Models\{
    BusinessApplicationProcesses,
    NotificationStatus,
    PartItem,
    UnitMaster,
    AdminView
};

class AllListController extends Controller
{

    protected $service;

    public function __construct()
    {
        $this->service = new AllListServices();
    }

    public function getAllNewRequirement(Request $request)
    { //checked
        try {
            $data_output = $this->service->getAllNewRequirement();

            return view('organizations.estimation.list.list-new-requirements-received-for-estimation', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getAllNewRequirementBusinessWise($business_id)
    {
        try {
            $data_output = $this->service->getAllNewRequirementBusinessWise($business_id);

            if ($data_output->isEmpty()) {
                return view('organizations.estimation.list.list_design_received_for_estimation_business_wise', [
                    'data_output' => [],
                    'message' => 'No data found'
                ]);
            }

            $bdIds = $data_output->pluck('business_details_id')->filter()->unique()->values();

            if ($bdIds->isNotEmpty()) {
                NotificationStatus::where('estimation_view', 0)
                    ->whereIn('business_details_id', $bdIds)
                    ->update(['estimation_view' => 1]);

                AdminView::where('is_view', 0)
                    ->whereIn('business_details_id', $bdIds)
                    ->update(['is_view' => 1]);
            }

            return view('organizations.estimation.list.list_design_received_for_estimation_business_wise', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getAllEstimationSendToOwnerForApproval(Request $request)
    { //checked
        try {
            $data_output = $this->service->getAllEstimationSendToOwnerForApproval();

            return view('organizations.estimation.list.list-updated-estimation-send-to-owner', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }


    public function getAllEstimationSendToOwnerForApprovalBusinessWise($business_id)
    {
        try {
            $data_output = $this->service->getAllEstimationSendToOwnerForApprovalBusinessWise($business_id);

            if ($data_output->isEmpty()) {
                return view('organizations.estimation.list.list-updated-estimation-send-to-owner_business_wise', [
                    'data_output' => [],
                    'message' => 'No data found'
                ]);
            }

            $bdIds = $data_output->pluck('business_details_id')->filter()->unique()->values();

            if ($bdIds->isNotEmpty()) {
                NotificationStatus::where('estimation_view', 0)
                    ->whereIn('business_details_id', $bdIds)
                    ->update(['estimation_view' => 1]);
            }

            return view('organizations.estimation.list.list-updated-estimation-send-to-owner_business_wise', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }


    public function reviseddesignlist()
    {
        try {
            $data_output = $this->service->getAllreviseddesign();

            if ($data_output->isEmpty()) {
                return view('organizations.productions.product.list-design-revised', [
                    'data_output' => [],
                    'message' => 'No data found'
                ]);
            }

            // NOTE: In your current code you use $data->id as business_details_id
            // Make sure $data->id really represents business_details_id.
            $bdIds = $data_output->pluck('id')->filter()->unique()->values();

            if ($bdIds->isNotEmpty()) {
                NotificationStatus::where('prod_is_view_revised', 0)
                    ->whereIn('business_details_id', $bdIds)
                    ->update(['prod_is_view_revised' => 1]);
            }

            return view('organizations.productions.product.list-design-revised', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getSendToProductionList()
    {
        try {
            $data_output = $this->service->getSendToProductionList();

            return view('organizations.estimation.list.list-send-to-production', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getAllListMaterialRecievedToProduction()
    {
        try {
            $data_output = $this->service->getAllListMaterialRecievedToProduction();
            return view('organizations.productions.product.list-recived-material', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getAllListMaterialRecievedToProductionBusinessWise($id)
    {
        try {
            $data_output = $this->service->getAllListMaterialRecievedToProductionBusinessWise($id);

            if ($data_output->isEmpty()) {
                return view('organizations.productions.product.list-recived-bussinesswise', [
                    'data_output' => [],
                    'message' => 'No data found'
                ]);
            }

            $bdIds = $data_output->pluck('business_details_id')->filter()->unique()->values();

            if ($bdIds->isNotEmpty()) {
                NotificationStatus::where('material_received_from_store', 0)
                    ->whereIn('business_details_id', $bdIds)
                    ->update(['material_received_from_store' => 1]);
            }

            return view('organizations.productions.product.list-recived-bussinesswise', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }
}
