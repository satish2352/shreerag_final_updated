<?php

namespace App\Http\Controllers\Organizations\Productions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Organizations\Productions\AllListServices;
use Exception;

use App\Models\{
    NotificationStatus,
    PartItem,
    UnitMaster
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
            return view('organizations.productions.product.list_design_received_for_production', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }

    // public function getAllNewRequirementBusinessWise()
    // { //checked
    //     try {
    //         $data_output = $this->service->getAllNewRequirementBusinessWise();

    //         if ($data_output->isNotEmpty()) {
    //             foreach ($data_output as $data) {
    //                 $business_details_id = $data->business_details_id;

    //                 if (!empty($business_details_id)) {
    //                     $update_data['prod_is_view'] = '1';
    //                     NotificationStatus::where('prod_is_view', '0')
    //                         ->where('business_details_id', $business_details_id)
    //                         ->update($update_data);
    //                 }
    //             }
    //         } else {
    //             return view('organizations.estimation.list.list-updated-estimation-send-to-owner_business_wise', [
    //                 'data_output' => [],
    //                 'message' => 'No data found for designs received for correction'
    //             ]);
    //         }
    //         return view('organizations.productions.product.list_design_received_for_production_business_wise', compact('data_output'));
    //     } catch (\Exception $e) {
    //         return $e;
    //     }
    // }

    // public function acceptdesignlist()
    // { //checked
    //     try {
    //         $data_output = $this->service->getAllacceptdesign();
    //         if ($data_output->isNotEmpty()) {
    //             foreach ($data_output as $data) {
    //                 $business_details_id = $data->id;

    //                 if (!empty($business_details_id)) {
    //                     $update_data['prod_design_accepted'] = '1';
    //                     NotificationStatus::where('prod_design_accepted', '0')
    //                         ->where('business_details_id', $business_details_id)
    //                         ->update($update_data);
    //                 }
    //             }
    //         } else {
    //             return view('organizations.productions.product.list-design-accepted', [
    //                 'data_output' => [],
    //                 'message' => 'No data found for designs received for correction'
    //             ]);
    //         }
    //         return view('organizations.productions.product.list-design-accepted', compact('data_output'));
    //     } catch (\Exception $e) {
    //         return $e;
    //     }
    // }

    // public function acceptdesignlistBusinessWise($business_id)
    // {
    //     try {
    //         $data_output = $this->service->acceptdesignlistBusinessWise($business_id);
    //         if ($data_output->isNotEmpty()) {
    //             foreach ($data_output as $data) {
    //                 $business_id = $data->business_details_id;

    //                 if (!empty($business_id)) {
    //                     $update_data['prod_is_view'] = '1';
    //                     NotificationStatus::where('prod_is_view', '0')
    //                         ->where('id', $business_id)
    //                         ->update($update_data);
    //                 }
    //             }
    //         } else {
    //             return view('organizations.productions.product.list-design-accepted-business-wise', [
    //                 'data_output' => [],
    //                 'message' => 'No data found'
    //             ]);
    //         }
    //         return view('organizations.productions.product.list-design-accepted-business-wise', compact('data_output'));
    //     } catch (\Exception $e) {
    //         return $e;
    //     }
    // }
public function getAllNewRequirementBusinessWise()
{
    try {
        $data_output = $this->service->getAllNewRequirementBusinessWise();

        if ($data_output->isEmpty()) {
            return view('organizations.estimation.list.list-updated-estimation-send-to-owner_business_wise', [
                'data_output' => [],
                'message' => 'No data found'
            ]);
        }

        $bdIds = $data_output->pluck('business_details_id')->filter()->unique()->values();

        if ($bdIds->isNotEmpty()) {
            NotificationStatus::where('prod_is_view', 0)
                ->whereIn('business_details_id', $bdIds)
                ->update(['prod_is_view' => 1]);
        }

        return view('organizations.productions.product.list_design_received_for_production_business_wise', compact('data_output'));
    } catch (\Exception $e) {
        return $e;
    }
}
public function acceptdesignlist()
{
    try {
        $data_output = $this->service->getAllacceptdesign();

        if ($data_output->isEmpty()) {
            return view('organizations.productions.product.list-design-accepted', [
                'data_output' => [],
                'message' => 'No data found'
            ]);
        }

        // Prefer this if available:
        $bdIds = $data_output->pluck('business_details_id')->filter()->unique()->values();

        if ($bdIds->isNotEmpty()) {
            NotificationStatus::where('prod_design_accepted', 0)
                ->whereIn('business_details_id', $bdIds)
                ->update(['prod_design_accepted' => 1]);
        }

        return view('organizations.productions.product.list-design-accepted', compact('data_output'));
    } catch (\Exception $e) {
        return $e;
    }
}
public function acceptdesignlistBusinessWise($business_id)
{
    try {
        $data_output = $this->service->acceptdesignlistBusinessWise($business_id);

        if ($data_output->isEmpty()) {
            return view('organizations.productions.product.list-design-accepted-business-wise', [
                'data_output' => [],
                'message' => 'No data found'
            ]);
        }

        $bdIds = $data_output->pluck('business_details_id')->filter()->unique()->values();

        if ($bdIds->isNotEmpty()) {
            NotificationStatus::where('prod_is_view', 0)
                ->whereIn('business_details_id', $bdIds)
                ->update(['prod_is_view' => 1]);
        }

        return view('organizations.productions.product.list-design-accepted-business-wise', compact('data_output'));
    } catch (\Exception $e) {
        return $e;
    }
}

    public function rejectdesignlist()
    { //checked
        try {
            $data_output = $this->service->getAllrejectdesign();
            return view('organizations.productions.product.list-design-rejected', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }

    // public function reviseddesignlist()
    // { //checked
    //     try {
    //         $data_output = $this->service->getAllreviseddesign();
    //         if ($data_output->isNotEmpty()) {
    //             foreach ($data_output as $data) {
    //                 $business_details_id = $data->id;

    //                 if (!empty($business_details_id)) {
    //                     $update_data['prod_is_view_revised'] = '1';
    //                     NotificationStatus::where('prod_is_view_revised', '0')
    //                         ->where('business_details_id', $business_details_id)
    //                         ->update($update_data);
    //                 }
    //             }
    //         } else {
    //             return view('organizations.productions.product.list-design-revised', [
    //                 'data_output' => [],
    //                 'message' => 'No data found for designs received for correction'
    //             ]);
    //         }
    //         return view('organizations.productions.product.list-design-revised', compact('data_output'));
    //     } catch (\Exception $e) {
    //         return $e;
    //     }
    // }
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

        // Your current code uses $data->id as business_details_id
        // This is correct only if the service returns id = business_details_id
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

    public function getAllListMaterialRecievedToProduction()
    { //checked
        try {
            $data_output = $this->service->getAllListMaterialRecievedToProduction();
            return view('organizations.productions.product.list-recived-material', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }
    // public function getAllListMaterialRecievedToProductionBusinessWise($id)
    // { //checked
    //     try {
    //         $data_output = $this->service->getAllListMaterialRecievedToProductionBusinessWise($id);

    //         if ($data_output->isNotEmpty()) {
    //             foreach ($data_output as $data) {
    //                 if (!empty($data->business_details_id)) {
    //                     NotificationStatus::where('material_received_from_store', '0')
    //                         ->where('business_details_id', $data->business_details_id)
    //                         ->update(['material_received_from_store' => '1']);
    //                 }
    //             }
    //         } else {
    //             return view('organizations.productions.product.list-recived-bussinesswise', [
    //                 'data_output' => [],
    //                 'message' => 'No data found for designs received for correction'
    //             ]);
    //         }

    //         return view('organizations.productions.product.list-recived-bussinesswise', compact('data_output'));
    //     } catch (\Exception $e) {
    //         return $e;
    //     }
    // }
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

    public function getAllCompletedProduction()
    {
        try {
            $data_output = $this->service->getAllCompletedProduction();
            return view('organizations.productions.product.list-production-completed', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getAllCompletedProductionSendToLogistics()
    {
        try {
            $data_output = $this->service->getAllCompletedProductionSendToLogistics();
            return view('organizations.productions.product.list-production-completed-send-to-logistics-tracking', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getAllCompletedProductionSendToLogisticsProductWise($id)
    {
        try {
            $editData = $this->service->getAllCompletedProductionSendToLogisticsProductWise($id);
            $dataOutputPartItem = PartItem::where('is_active', true)->get();
            $dataOutputUnitMaster = UnitMaster::where('is_active', true)->get();
            return view('organizations.productions.product.list-production-completed-send-to-logistics-tracking-business-wise', [
                'productDetails' => $editData['productDetails'],
                'dataGroupedById' => $editData['dataGroupedById'],
                'dataOutputPartItem' => $dataOutputPartItem,
                'dataOutputUnitMaster' => $dataOutputUnitMaster,
                'id' => $id
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with(['status' => 'error', 'msg' => $e->getMessage()]);
        }
    }
}
