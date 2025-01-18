<?php

namespace App\Http\Controllers\Organizations\Security;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Validator;
use Config;
use Carbon;
use App\Models\ {
    Business, 
    DesignModel,
    BusinessApplicationProcesses,
    ProductionModel,
    DesignRevisionForProd,
    NotificationStatus,
    PurchaseOrdersModel
    
    };

class AllListController extends Controller
{ 
    
    // public function getAllListMaterialRecieved(Request $request){
    //     try {
    //         $array_to_be_check_security = [config('constants.SECURIY_DEPARTMENT.LIST_PO_TO_BE_CHECKED')];
    //         $array_to_be_purchase = [ config('constants.PUCHASE_DEPARTMENT.LIST_APPROVED_PO_FROM_HIGHER_AUTHORITY_SENT_TO_VENDOR')];

    //         $data_output= BusinessApplicationProcesses::leftJoin('production', function($join) {
    //             $join->on('business_application_processes.business_details_id', '=', 'production.business_details_id');
    //           })
             
    //           ->leftJoin('purchase_orders', function($join) {
    //             $join->on('business_application_processes.business_details_id', '=', 'purchase_orders.business_details_id');
    //           })

    //           ->leftJoin('businesses', function($join) {
    //             $join->on('business_application_processes.business_id', '=', 'businesses.id');
    //           })
    //           ->leftJoin('businesses_details', function($join) {
    //             $join->on('production.business_details_id', '=', 'businesses_details.id');
    //         })
    //         ->whereIn('purchase_orders.purchase_status_from_owner',$array_to_be_check_security)
    //         ->whereIn('purchase_orders.purchase_status_from_purchase',$array_to_be_purchase)
    //           ->where('purchase_orders.purchase_orders_id', 'like', '%' . $request->purchase_orders_id . '%')
    //           ->where('businesses.is_active',true)
    //           ->select(
    //               'businesses_details.id',
    //               'businesses.title',
    //               'businesses_details.product_name',
    //               'businesses_details.description',
    //               'businesses.remarks',
    //               'businesses.is_active',
    //               'production.business_id',
    //               'production.id as productionId',
    //               'purchase_orders.purchase_orders_id'
    //                         )
    //           ->get();
    //           if ($data_output->isNotEmpty()) {
    //             foreach ($data_output as $data) {
    //                 $business_id = $data->id; 
    //                 if (!empty($business_id)) {
    //                     $update_data['po_send_to_vendor_visible_security'] = '1';
    //                     NotificationStatus::where('po_send_to_vendor_visible_security', '0')
    //                         ->where('id', $business_id)
    //                         ->update($update_data);
    //                 }
    //             }
    //         } else {
    //             return view('organizations.security.list.list-recived-material', [
    //                 'data_output' => [],
    //                 'message' => 'No data found'
    //             ]);
    //         }
    //         return view('organizations.security.list.list-recived-material', compact('data_output'));
    //     } catch (\Exception $e) {
    //         return $e;
    //     }
    // } 
    public function getAllListMaterialRecieved(Request $request)
{
    try {
        $array_to_be_check_security = [config('constants.SECURIY_DEPARTMENT.LIST_PO_TO_BE_CHECKED')];
        $array_to_be_purchase = [config('constants.PUCHASE_DEPARTMENT.LIST_APPROVED_PO_FROM_HIGHER_AUTHORITY_SENT_TO_VENDOR')];

        $data_output = PurchaseOrdersModel::leftJoin('production', function ($join) {
            $join->on('purchase_orders.business_details_id', '=', 'production.business_details_id');
        })
        ->leftJoin('gatepass', function ($join) { // Ensure 'gatepass' table exists
            $join->on('purchase_orders.purchase_orders_id', '=', 'gatepass.purchase_orders_id');
        })
        ->leftJoin('businesses', function ($join) {
            $join->on('purchase_orders.business_id', '=', 'businesses.id');
        })
        ->leftJoin('businesses_details', function ($join) {
            $join->on('purchase_orders.business_details_id', '=', 'businesses_details.id');
        })
        ->whereIn('purchase_orders.purchase_status_from_owner', $array_to_be_check_security)
        ->whereIn('purchase_orders.purchase_status_from_purchase', $array_to_be_purchase)
        ->when($request->purchase_orders_id, function ($query, $purchase_orders_id) {
            $query->where('purchase_orders.purchase_orders_id', 'like', '%' . $purchase_orders_id . '%');
        })
        ->where('businesses.is_active', true)
        ->select(
            // 'gatepass.id as gatepass_id',
             'purchase_orders.id as gatepass_id',
            'businesses_details.id as business_details_id',
            'businesses.title',
            'businesses_details.product_name',
            'businesses_details.description',
            'businesses.remarks',
            'businesses.is_active',
            'production.business_id',
            'production.id as productionId',
            'purchase_orders.purchase_orders_id'
        )
        ->get();

        if ($data_output->isNotEmpty()) {
            foreach ($data_output as $data) {
                $business_id = $data->business_details_id; // Correct column used
                if (!empty($business_id)) {
                    $update_data['po_send_to_vendor_visible_security'] = '1';
                    NotificationStatus::where('po_send_to_vendor_visible_security', '0')
                        ->where('id', $business_id)
                        ->update($update_data);
                }
            }
        } else {
            return view('organizations.security.list.list-recived-material', [
                'data_output' => [],
                'message' => 'No data found'
            ]);
        }

        return view('organizations.security.list.list-recived-material', compact('data_output'));
    } catch (\Exception $e) {
        \Log::error('Error in getAllListMaterialRecieved: ' . $e->getMessage());
        return response()->view('errors.general', ['message' => 'An error occurred while processing your request.'], 500);
    }
}


}