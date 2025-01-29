<?php

namespace App\Http\Controllers\Organizations\Store;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Organizations\Store\AllListServices;
use Session;
use Validator;
use Config;
use DB;
use Carbon;
use App\Models\ {
    Business,
    BusinessApplicationProcesses,
    AdminView,
    PurchaseOrdersModel,
    PurchaseOrderDetailsModel,
    GRNModel,
    NotificationStatus,
    GrnPOQuantityTracking

};

class AllListController extends Controller
{ 
    public function __construct(){
        $this->service = new AllListServices();
    }
  
    public function getAllListDesignRecievedForMaterial(Request $request){
        try {
            $data_output = $this->service->getAllListDesignRecievedForMaterial();
            return view('organizations.store.list.list-accepted-design', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    } 
    public function getAllListDesignRecievedForMaterialBusinessWise($business_id, Request $request)
    {
        try {
            $data_output = $this->service->getAllListDesignRecievedForMaterialBusinessWise($business_id);
            return view('organizations.store.list.list-accepted-design-business-wise', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }
    

    public function getAllListMaterialSentToProduction(Request $request){
        try {
            $data_output = $this->service->getAllListMaterialSentToProduction();
        
            if ($data_output->isNotEmpty()) {
                foreach ($data_output as $data) {
                    $business_details_id = $data->id; 
                    if (!empty($business_details_id)) {
                        $update_data['material_received_from_store'] = '1';
                        NotificationStatus::where('material_received_from_store', '0')
                            ->where('business_details_id', $business_details_id)
                            ->update($update_data);
                    }
                }
            } else {
                return view('organizations.store.list.list-material-sent-to-prod', [
                    'data_output' => [],
                    'message' => 'No data found for designs received for correction'
                ]);
            }
        
            return view('organizations.store.list.list-material-sent-to-prod', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    } 


    
    public function getAllListMaterialSentToPurchase(){

        try {
            $data_output = $this->service->getAllListMaterialSentToPurchase();
            return view('organizations.store.list.list-material-sent-to-purchase', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getAllListMaterialReceivedFromQuality()
    {
        try {
            $data_output = $this->service->getAllListMaterialReceivedFromQuality();
    
            if (is_array($data_output) && count($data_output) > 0 || $data_output instanceof \Illuminate\Support\Collection && $data_output->isNotEmpty()) {
                foreach ($data_output as $data) {
                    $business_id = $data->id; 
                    if (!empty($business_id)) {
                        $update_data['received_material_to_quality'] = '1';
                        NotificationStatus::where('received_material_to_quality', '0')
                            ->where('id', $business_id)
                            ->update($update_data);
                    }
                }
            } else {
                return view('organizations.store.list.list-material-received-from-quality', [
                    'data_output' => [],
                    'message' => 'No data found'
                ]);
            }
    
            return view('organizations.store.list.list-material-received-from-quality', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }
    
  
    public function submitFinalPurchaseOrder($id){
        try {
            $data_output = $this->service->getPurchaseOrderBusinessWise($id);
           
            return view('organizations.store.list.list-material-received-from-quality-businesswise', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getAllListMaterialReceivedFromQualityPOTracking(){

        try {
            $data_output = $this->service->getAllListMaterialReceivedFromQualityPOTracking();
          
           // Check if $data_output is an array or a collection and handle accordingly
           if (is_array($data_output) && count($data_output) > 0 || $data_output instanceof \Illuminate\Support\Collection && $data_output->isNotEmpty()) {
                foreach ($data_output as $data) {
                    $business_id = $data->id; 
                    if (!empty($business_id)) {
                        $update_data['received_material_to_quality'] = '1';
                        NotificationStatus::where('received_material_to_quality', '0')
                            ->where('id', $business_id)
                            ->update($update_data);
                    }
                }
            } else {
                return view('organizations.store.list.list-material-received-from-quality-po-tracking', [
                    'data_output' => [],
                    'message' => 'No data found'
                ]);
            }
            return view('organizations.store.list.list-material-received-from-quality-po-tracking', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getAllListMaterialReceivedFromQualityPOTrackingBusinessWise($id){
        try {
            $data_output = $this->service->getAllListMaterialReceivedFromQualityPOTrackingBusinessWise($id);
           
            return view('organizations.store.list.list-material-received-from-quality-businesswise-po-tracking', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getGRNDetails($purchase_orders_id,$business_details_id, $id)
    {
        try {
            $idtoedit = base64_decode($purchase_orders_id);
            $grn_id = base64_decode($id);
            // dd($grn_id);
            // die();
            $purchase_order_data = PurchaseOrdersModel::where('purchase_orders_id', '=', $idtoedit)->first();
            $grn_data = GRNModel::leftJoin('gatepass', function ($join) {
                $join->on('grn_tbl.gatepass_id', '=', 'gatepass.id');
            })
            ->where('grn_tbl.purchase_orders_id', '=', $idtoedit)->where('grn_tbl.id', '=', $grn_id)
            ->select(
                'grn_tbl.*',
                'gatepass.*',
                'grn_tbl.remark as grn_remark'
            )
            ->first();
            $po_id = $purchase_order_data->id;
            $purchase_order_details_data = GrnPOQuantityTracking::leftJoin('tbl_part_item as part_item_1', 'tbl_grn_po_quantity_tracking.part_no_id', '=', 'part_item_1.id')
    ->leftJoin('purchase_order_details', 'tbl_grn_po_quantity_tracking.purchase_order_details_id', '=', 'purchase_order_details.id')
    ->leftJoin('tbl_part_item as part_item_2', 'purchase_order_details.part_no_id', '=', 'part_item_2.id')
    ->leftJoin('tbl_unit', 'purchase_order_details.unit', '=', 'tbl_unit.id')
    ->where('tbl_grn_po_quantity_tracking.purchase_order_id', $po_id)
    ->where('tbl_grn_po_quantity_tracking.grn_id', $grn_id)
    ->select(
        'tbl_grn_po_quantity_tracking.purchase_order_id',
        'tbl_grn_po_quantity_tracking.part_no_id',
        'tbl_grn_po_quantity_tracking.purchase_order_details_id',
        DB::raw('MAX(tbl_grn_po_quantity_tracking.quantity) as max_quantity'),
        DB::raw('SUM(tbl_grn_po_quantity_tracking.actual_quantity) as sum_actual_quantity'),
        DB::raw('SUM(tbl_grn_po_quantity_tracking.accepted_quantity) as tracking_accepted_quantity'),
        DB::raw('SUM(tbl_grn_po_quantity_tracking.rejected_quantity) as tracking_rejected_quantity'),
        DB::raw('COALESCE(
            (SELECT SUM(t2.actual_quantity) 
             FROM tbl_grn_po_quantity_tracking AS t2 
             WHERE t2.purchase_order_id = tbl_grn_po_quantity_tracking.purchase_order_id
             AND t2.purchase_order_details_id = tbl_grn_po_quantity_tracking.purchase_order_details_id
             AND t2.part_no_id = tbl_grn_po_quantity_tracking.part_no_id), 0) AS sum_grn_actual_quantity'),
             DB::raw('(
            MAX(tbl_grn_po_quantity_tracking.quantity) - 
            SUM(tbl_grn_po_quantity_tracking.actual_quantity)
        ) AS remaining_quantity'),
        // DB::raw('COALESCE(
        //     purchase_order_details.quantity, 0) - 
        //     COALESCE(
        //     (SELECT SUM(t2.actual_quantity) 
        //      FROM tbl_grn_po_quantity_tracking AS t2 
        //      WHERE t2.purchase_order_id = tbl_grn_po_quantity_tracking.purchase_order_id
        //      AND t2.purchase_order_details_id = tbl_grn_po_quantity_tracking.purchase_order_details_id
        //      AND t2.part_no_id = tbl_grn_po_quantity_tracking.part_no_id), 0) AS remaining_quantity'),
        'part_item_2.description as part_description',
        'part_item_2.part_number',
        'tbl_unit.name as unit_name',
        DB::raw('MAX(purchase_order_details.description) as po_description'),
        DB::raw('MAX(purchase_order_details.rate) as po_rate'),
        DB::raw('MAX(purchase_order_details.discount) as po_discount')
    )
    ->groupBy(
        'tbl_grn_po_quantity_tracking.purchase_order_id',
        'tbl_grn_po_quantity_tracking.part_no_id',
        'tbl_grn_po_quantity_tracking.purchase_order_details_id',
        'part_item_2.description',
        'part_item_2.part_number',
        'tbl_unit.name',
        'purchase_order_details.quantity'
    )
    ->get();
            // $purchase_order_details_data = GrnPOQuantityTracking::leftJoin('tbl_part_item', 'tbl_grn_po_quantity_tracking.part_no_id', '=', 'tbl_part_item.id')
            //     ->leftJoin('purchase_order_details', 'tbl_grn_po_quantity_tracking.purchase_order_details_id', '=', 'purchase_order_details.id')
            //     ->leftJoin('tbl_unit', 'tbl_grn_po_quantity_tracking.unit', '=', 'tbl_unit.id')
            //     ->where('tbl_grn_po_quantity_tracking.purchase_order_id', $po_id)
            //     ->where('tbl_grn_po_quantity_tracking.grn_id', $grn_id)
            //     ->select(
            //         'tbl_grn_po_quantity_tracking.purchase_order_id', // Add purchase_order_id to the select statement
            //         'tbl_grn_po_quantity_tracking.part_no_id',
            //         'tbl_grn_po_quantity_tracking.purchase_order_details_id',
            //         DB::raw('MAX(tbl_grn_po_quantity_tracking.quantity) as max_quantity'),
            //         DB::raw('SUM(tbl_grn_po_quantity_tracking.actual_quantity) as sum_actual_quantity'),
            //         DB::raw('SUM(tbl_grn_po_quantity_tracking.accepted_quantity) as tracking_accepted_quantity'),
            //         DB::raw('SUM(tbl_grn_po_quantity_tracking.rejected_quantity) as tracking_rejected_quantity'),
            
            //         DB::raw('(SELECT SUM(t2.actual_quantity) 
            //                   FROM tbl_grn_po_quantity_tracking AS t2 
            //                   WHERE t2.purchase_order_id = tbl_grn_po_quantity_tracking.purchase_order_id
            //                   AND t2.purchase_order_details_id = tbl_grn_po_quantity_tracking.purchase_order_details_id
            //                   AND t2.part_no_id = tbl_grn_po_quantity_tracking.part_no_id) AS sum_grn_actual_quantity'),
            
            //                   DB::raw('(
            //                     purchase_order_details.quantity - 
            //                     (SELECT SUM(t2.actual_quantity) 
            //                      FROM tbl_grn_po_quantity_tracking AS t2 
            //                      WHERE t2.purchase_order_id = tbl_grn_po_quantity_tracking.purchase_order_id
            //                      AND t2.purchase_order_details_id = tbl_grn_po_quantity_tracking.purchase_order_details_id
            //                      AND t2.part_no_id = tbl_grn_po_quantity_tracking.part_no_id)
            //                 ) AS remaining_quantity'),

            //         'tbl_part_item.description as part_description',
            //         'tbl_part_item.part_number',
            //         'tbl_unit.name as unit_name',
            //         DB::raw('MAX(purchase_order_details.description) as po_description'),
            //         DB::raw('MAX(purchase_order_details.rate) as po_rate'),
            //         DB::raw('MAX(purchase_order_details.discount) as po_discount')
            //     )
            //     ->groupBy(
            //         'tbl_grn_po_quantity_tracking.purchase_order_id', // Add this field to group by
            //         'tbl_grn_po_quantity_tracking.part_no_id',
            //         'tbl_grn_po_quantity_tracking.purchase_order_details_id',
            //         'tbl_part_item.id', // Ensure part_item.id is grouped
            //         'tbl_part_item.description',
            //         'tbl_part_item.part_number',
            //         'tbl_unit.name',
            //          'purchase_order_details.quantity'
            //     )
            //     ->get();
           return view('organizations.store.list.list-grn', compact('purchase_order_data', 'purchase_order_details_data', 'grn_data','grn_id'));
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getGRNDetailsPOTracking($purchase_orders_id,$business_details_id, $id)
    {
        try {
            // $purchase_number =  base64_decode($purchase_orders_id);
            // $idtoedit = base64_decode($grn_id);
            // $purchase_order_data = PurchaseOrdersModel::where('purchase_orders_id', '=', $purchase_number)->first();
            // $grn_data = GRNModel::where('id', '=', $idtoedit)->first();
            // $po_id = $grn_data->purchase_orders_id;
            // $po_details = $grn_data->id;
            // $purchase_order_details_data = GrnPOQuantityTracking::where('grn_id', $po_details)
            //     ->get();
            $idtoedit = base64_decode($purchase_orders_id);
         
            $grn_id = base64_decode($id);
           
            $purchase_order_data = PurchaseOrdersModel::where('purchase_orders_id', '=', $idtoedit)->first();
            $grn_data = GRNModel::leftJoin('gatepass', function ($join) {
                $join->on('grn_tbl.gatepass_id', '=', 'gatepass.id');
            })
            ->where('grn_tbl.purchase_orders_id', '=', $idtoedit)->where('grn_tbl.id', '=', $grn_id)
            ->select(
                'grn_tbl.*',
                'gatepass.*',
                'grn_tbl.remark as grn_remark'
            )
            ->first();
            
         
            $po_id = $purchase_order_data->id;
            // $purchase_order_details_data = GrnPOQuantityTracking::leftJoin('tbl_part_item', 'tbl_grn_po_quantity_tracking.part_no_id', '=', 'tbl_part_item.id')
            // ->leftJoin('purchase_order_details', 'tbl_grn_po_quantity_tracking.purchase_order_details_id', '=', 'purchase_order_details.id')
            // ->leftJoin('tbl_unit', 'tbl_grn_po_quantity_tracking.unit', '=', 'tbl_unit.id')
            // ->where('tbl_grn_po_quantity_tracking.purchase_order_id', $po_id)
            // ->where('tbl_grn_po_quantity_tracking.grn_id', $grn_id)
            // ->select(
            //     'tbl_grn_po_quantity_tracking.purchase_order_id', // Add purchase_order_id to the select statement
            //     'tbl_grn_po_quantity_tracking.part_no_id',
            //     'tbl_grn_po_quantity_tracking.purchase_order_details_id',
            //     DB::raw('MAX(tbl_grn_po_quantity_tracking.quantity) as max_quantity'),
            //     DB::raw('SUM(tbl_grn_po_quantity_tracking.actual_quantity) as sum_actual_quantity'),
            //     DB::raw('SUM(tbl_grn_po_quantity_tracking.accepted_quantity) as tracking_accepted_quantity'),
            //     DB::raw('SUM(tbl_grn_po_quantity_tracking.rejected_quantity) as tracking_rejected_quantity'),
        
            //     DB::raw('(SELECT SUM(t2.actual_quantity) 
            //               FROM tbl_grn_po_quantity_tracking AS t2 
            //               WHERE t2.purchase_order_id = tbl_grn_po_quantity_tracking.purchase_order_id
            //               AND t2.purchase_order_details_id = tbl_grn_po_quantity_tracking.purchase_order_details_id
            //               AND t2.part_no_id = tbl_grn_po_quantity_tracking.part_no_id) AS sum_grn_actual_quantity'),
        
            //               DB::raw('(
            //                 purchase_order_details.quantity - 
            //                 (SELECT SUM(t2.actual_quantity) 
            //                  FROM tbl_grn_po_quantity_tracking AS t2 
            //                  WHERE t2.purchase_order_id = tbl_grn_po_quantity_tracking.purchase_order_id
            //                  AND t2.purchase_order_details_id = tbl_grn_po_quantity_tracking.purchase_order_details_id
            //                  AND t2.part_no_id = tbl_grn_po_quantity_tracking.part_no_id)
            //             ) AS remaining_quantity'),

            //     'tbl_part_item.description as part_description',
            //     'tbl_part_item.part_number',
            //     'tbl_unit.name as unit_name',
            //     DB::raw('MAX(purchase_order_details.description) as po_description'),
            //     DB::raw('MAX(purchase_order_details.rate) as po_rate'),
            //     DB::raw('MAX(purchase_order_details.discount) as po_discount')
            // )
            // ->groupBy(
            //     'tbl_grn_po_quantity_tracking.purchase_order_id', // Add this field to group by
            //     'tbl_grn_po_quantity_tracking.part_no_id',
            //     'tbl_grn_po_quantity_tracking.purchase_order_details_id',
            //     'tbl_part_item.id', // Ensure part_item.id is grouped
            //     'tbl_part_item.description',
            //     'tbl_part_item.part_number',
            //     'tbl_unit.name',
            //      'purchase_order_details.quantity'
            // )
            // ->get();

            $purchase_order_details_data = GrnPOQuantityTracking::leftJoin('tbl_part_item as part_item_1', 'tbl_grn_po_quantity_tracking.part_no_id', '=', 'part_item_1.id')
    ->leftJoin('purchase_order_details', 'tbl_grn_po_quantity_tracking.purchase_order_details_id', '=', 'purchase_order_details.id')
    ->leftJoin('tbl_part_item as part_item_2', 'purchase_order_details.part_no_id', '=', 'part_item_2.id')
    ->leftJoin('tbl_unit', 'purchase_order_details.unit', '=', 'tbl_unit.id')
    ->where('tbl_grn_po_quantity_tracking.purchase_order_id', $po_id)
    ->where('tbl_grn_po_quantity_tracking.grn_id', $grn_id)
    ->select(
        'tbl_grn_po_quantity_tracking.purchase_order_id',
        'tbl_grn_po_quantity_tracking.part_no_id',
        'tbl_grn_po_quantity_tracking.purchase_order_details_id',
        DB::raw('MAX(tbl_grn_po_quantity_tracking.quantity) as max_quantity'),
        DB::raw('SUM(tbl_grn_po_quantity_tracking.actual_quantity) as sum_actual_quantity'),
        DB::raw('SUM(tbl_grn_po_quantity_tracking.accepted_quantity) as tracking_accepted_quantity'),
        DB::raw('SUM(tbl_grn_po_quantity_tracking.rejected_quantity) as tracking_rejected_quantity'),
        DB::raw('COALESCE(
            (SELECT SUM(t2.actual_quantity) 
             FROM tbl_grn_po_quantity_tracking AS t2 
             WHERE t2.purchase_order_id = tbl_grn_po_quantity_tracking.purchase_order_id
             AND t2.purchase_order_details_id = tbl_grn_po_quantity_tracking.purchase_order_details_id
             AND t2.part_no_id = tbl_grn_po_quantity_tracking.part_no_id), 0) AS sum_grn_actual_quantity'),
             DB::raw('(
            MAX(tbl_grn_po_quantity_tracking.quantity) - 
            SUM(tbl_grn_po_quantity_tracking.actual_quantity)
        ) AS remaining_quantity'),
        // DB::raw('COALESCE(
        //     purchase_order_details.quantity, 0) - 
        //     COALESCE(
        //     (SELECT SUM(t2.actual_quantity) 
        //      FROM tbl_grn_po_quantity_tracking AS t2 
        //      WHERE t2.purchase_order_id = tbl_grn_po_quantity_tracking.purchase_order_id
        //      AND t2.purchase_order_details_id = tbl_grn_po_quantity_tracking.purchase_order_details_id
        //      AND t2.part_no_id = tbl_grn_po_quantity_tracking.part_no_id), 0) AS remaining_quantity'),
        'part_item_2.description as part_description',
        'part_item_2.part_number',
        'tbl_unit.name as unit_name',
        DB::raw('MAX(purchase_order_details.description) as po_description'),
        DB::raw('MAX(purchase_order_details.rate) as po_rate'),
        DB::raw('MAX(purchase_order_details.discount) as po_discount')
    )
    ->groupBy(
        'tbl_grn_po_quantity_tracking.purchase_order_id',
        'tbl_grn_po_quantity_tracking.part_no_id',
        'tbl_grn_po_quantity_tracking.purchase_order_details_id',
        'part_item_2.description',
        'part_item_2.part_number',
        'tbl_unit.name',
        'purchase_order_details.quantity'
    )
    ->get();
// dd($purchase_order_details_data);
// die();

            return view('organizations.store.list.list-grn-po-tracking', compact('purchase_order_data', 'purchase_order_details_data', 'grn_data'));
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getAllInprocessProductProduction(){
        try {
            $data_output = $this->service->getAllInprocessProductProduction();
           
            return view('organizations.store.list.list-material-received-from-production-inprocess', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }

}