<?php
namespace App\Http\Repository\Organizations\Quality;

use Illuminate\Database\QueryException;
use DB;
use Illuminate\Support\Carbon;
use App\Models\{
    GRNModel,
    PurchaseOrderDetailsModel,
    Gatepass,
    PurchaseOrderModel,
    BusinessApplicationProcesses,
    RejectedChalan,
    PurchaseOrdersModel,
    AdminView,
    NotificationStatus,
    GrnPOQuantityTracking
};
use Config;

class GRNRepository
{

    public function getAll()
    {
        try {
            // $data_output = Gatepass::where('is_checked_by_quality',false)->get();
            $data_output = Gatepass::leftJoin('purchase_orders', function ($join) {
                $join->on('gatepass.purchase_orders_id', '=', 'purchase_orders.purchase_orders_id');
            })
            ->select(
                'gatepass.id',
                'purchase_orders.business_details_id',
                'gatepass.purchase_orders_id',
                'gatepass.gatepass_name', 
                'gatepass.gatepass_date', 
                'gatepass.gatepass_time', 
                'gatepass.is_active'
            )
            ->orderBy('gatepass.updated_at', 'desc')
            ->get();
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getDetailsForPurchase($id)
    {
        return PurchaseOrdersModel::where('id', '=', $id)->first();
    }
    // repository
    // public function storeGRN($request)
    // {
    //     try {
    //         $grn_no = str_replace(array("-", ":"), "", date('Y-m-d') . time());



            
    //         // Fetch the business details ID from the purchase order
    //         $business_details_id = $purchase_orders_details->business_details_id;
    
    //         // Fetch the business application process using business_details_id
    //         $business_application = BusinessApplicationProcesses::where('business_details_id', $business_details_id)->first();
    
    //         if (!$business_application) {
    //             return [
    //                 'msg' => 'Business Application not found.',
    //                 'status' => 'error'
    //             ];
    //         }


    //         $dataOutput = new GRNModel();
    //         $dataOutput->purchase_orders_id = $request->purchase_orders_id;
    //         // $dataOutput->grn_no = $grn_no;
    //         $dataOutput->po_date = $request->po_date;
    //         $dataOutput->grn_date = $request->grn_date;
    //         $dataOutput->remark = $request->remark;
    //         $dataOutput->image = 'null';
    //         $dataOutput->is_approve = '0';
    //         $dataOutput->is_active = '1';
    //         $dataOutput->is_deleted = '0';
    //         $dataOutput->save();
    //         $last_insert_id = $dataOutput->id;
     
    //         foreach ($request->addmore as $index => $item) {
    //             $user_data = PurchaseOrderDetailsModel::where('id', $item['edit_id'])
    //                 ->update([
    //                     // 'qc_check_remark' => $item['qc_check_remark'],
    //                     'actual_quantity' => $item['actual_quantity'],
    //                     'accepted_quantity' => $item['accepted_quantity'],
    //                     'rejected_quantity' => $item['rejected_quantity'],
    //                 ]);
    //         }
    //         $imageName = $last_insert_id . '_' . rand(100000, 999999) . '_image.' . $request->image->getClientOriginalExtension();
    //         $finalOutput = GRNModel::find($last_insert_id);
    //         $finalOutput->image = $imageName;
    //         $finalOutput->save();

         
            
    //         $purchase_orders_details = PurchaseOrderModel::where('purchase_orders_id', $request->purchase_orders_id)->first();
          
           
    //         $business_application = PurchaseOrderModel::where('purchase_orders_id', $purchase_orders_details->purchase_orders_id)->first();
          

    //         if ($business_application) {
    //             $business_application->grn_no = $grn_no;
    //             $business_application->quality_material_sent_to_store_date = date('Y-m-d');
    //             $business_application->quality_status_id = config('constants.QUALITY_DEPARTMENT.PO_CHECKED_OK_GRN_GENRATED_SENT_TO_STORE');
    //             $business_application->save();               
    //         }

          
    //         $updateGatepassTable = Gatepass::where('purchase_orders_id',$request->purchase_orders_id)->first();
    //         $updateGatepassTable->is_checked_by_quality = true;
    //         $updateGatepassTable->save();
    //         $rejected_chalan_data = new RejectedChalan();
    //         $rejected_chalan_data->purchase_orders_id = $request->purchase_orders_id;
    //         $rejected_chalan_data->grn_id = $dataOutput->id;
    //         $rejected_chalan_data->chalan_no = '';
    //         $rejected_chalan_data->reference_no = '';
    //         $rejected_chalan_data->remark = '';
    //         $rejected_chalan_data->save();


    //          // Update the business application process's off_canvas_status
    //          $business_application->off_canvas_status = 27;
    //          $business_application->save();
     
    //          // Prepare data to update admin and notification statuses
    //          $update_data_admin['off_canvas_status'] = 27;
     
    //          // Update AdminView table
    //          AdminView::where('business_details_id', $business_application->business_details_id)
    //              ->update($update_data_admin);
     
    //          // Update NotificationStatus table
    //          NotificationStatus::where('business_details_id', $business_application->business_details_id)
    //              ->update($update_data_admin);
    //         return [
    //             'ImageName' => $imageName,
    //             'status' => 'success'
    //         ];
    //     } catch (\Exception $e) {
            
    //         return [
    //             'msg' => $e->getMessage(),
    //             'status' => 'error'
    //         ];
    //     }
    // }
    public function storeGRN($request)
    {
        try {
            // dd($request);
            // die();
            // Retrieve the gatepass entry based on the provided ID or purchase_orders_id
        $gatepass = Gatepass::where('id', $request->id)->first();
    //    dd($gatepass);
    //    die();
        if (!$gatepass) {
            return [
                'msg' => 'Gatepass not found.',
                'status' => 'error'
            ];
        }
            // Generate a unique GRN number
            $grn_no = str_replace(array("-", ":"), "", date('Y-m-d') . time());
    
            // Fetch purchase orders details
            $purchase_orders_details = PurchaseOrderModel::where('purchase_orders_id', $request->purchase_orders_id)->first();
        
        
           
            if (!$purchase_orders_details) {
                return [
                    'msg' => 'Purchase order not found.',
                    'status' => 'error'
                ];
            }
            $purchase_id = $purchase_orders_details->purchase_orders_id;
            // dd($purchase_orders_details);
            // die();
            // Fetch the business details ID from the purchase order
            $business_details_id = $purchase_orders_details->business_details_id;
       
            // Fetch the business application process using business_details_id
            $business_application = BusinessApplicationProcesses::where('business_details_id', $business_details_id)->first();
            // dd($business_application);
            // die();
            if (!$business_application) {
                return [
                    'msg' => 'Business Application not found.',
                    'status' => 'error'
                ];
            }
    
            // Create a new GRN entry
            $dataOutput = new GRNModel();
            $dataOutput->purchase_orders_id =  $purchase_id;
            $dataOutput->gatepass_id = $gatepass->id; 
            // gatepass_id
            $dataOutput->po_date = $request->po_date;
            $dataOutput->grn_date = $request->grn_date;
            $dataOutput->remark = $request->remark;
            $dataOutput->image = 'null'; // Initial image state
            $dataOutput->is_approve = '0';
            $dataOutput->is_active = '1';
            $dataOutput->is_deleted = '0';
        //  dd($dataOutput);
        //  die();
            $dataOutput->save();
    
            $last_insert_id = $dataOutput->id;
  
            // Update purchase order details with quantities
            foreach ($request->addmore as $item) {
                // dd($request->addmore);
                // die();
                PurchaseOrderDetailsModel::where('id', $item['edit_id'])
                    ->update([
                        'actual_quantity' => $item['actual_quantity'],
                        'accepted_quantity' => $item['accepted_quantity'],
                        'rejected_quantity' => $item['rejected_quantity'],
                    ]);



                    // Create a new GRN tracking entry
    $grnPoTracking = new GrnPOQuantityTracking();

    // Assign the necessary fields from the $item array
    // $grnPoTracking->purchase_id = $last_insert_id; 
    $grnPoTracking->purchase_order_id = $purchase_orders_details->id;

    $grnPoTracking->grn_id = $last_insert_id; 
    $grnPoTracking->purchase_order_details_id = $item['edit_id']; 
    // $grnPoTracking->part_no_id = $item['part_no_id']; 
    $grnPoTracking->description = $item['description']; 
    // $grnPoTracking->due_date = $item['due_date']; 
    $grnPoTracking->quantity = $item['chalan_quantity'];
    $grnPoTracking->actual_quantity = $item['actual_quantity']; 
    // $grnPoTracking->unit = $item['unit']; 
    $grnPoTracking->accepted_quantity = $item['accepted_quantity']; 
    $grnPoTracking->rejected_quantity = $item['rejected_quantity']; 
    $grnPoTracking->is_deleted = false;
    $grnPoTracking->is_active = true; 
    

    // dd($grnPoTracking);
    // die();
    // Save the tracking entry
    $grnPoTracking->save();
            }
    
            // Handle image upload
            if ($request->hasFile('image')) {
                $imageName = $last_insert_id . '_' . rand(100000, 999999) . '_image.' . $request->image->getClientOriginalExtension();
                $finalOutput = GRNModel::find($last_insert_id);
                $finalOutput->image = $imageName;
                $finalOutput->save();
            }
             if ($purchase_orders_details) {
                $purchase_orders_details->grn_no = $grn_no;
                $purchase_orders_details->quality_material_sent_to_store_date = date('Y-m-d');
                $purchase_orders_details->quality_status_id = config('constants.QUALITY_DEPARTMENT.PO_CHECKED_OK_GRN_GENRATED_SENT_TO_STORE');
                $purchase_orders_details->save();               
            }
            
            $business_application->off_canvas_status = 27; // Update the off_canvas_status here
            $business_application->save();
    


// dd($gatepass->id);
// die();
            
       // Update gatepass status
       Gatepass::where('id', $gatepass->id)
       ->update([
           'po_tracking_status' => 4002, // Update the tracking status
           'is_checked_by_quality' => true // Set quality check status
       ]);
            // if ($updateGatepassTable) {
            //     $updateGatepassTable->is_checked_by_quality = true;
            //     $updateGatepassTable->po_tracking_status = 4002;
            //     $updateGatepassTable->save();
            // }
    
          
            // Save rejected chalan data if necessary
            $rejected_chalan_data = new RejectedChalan();
            $rejected_chalan_data->purchase_orders_id = $request->purchase_orders_id;
            $rejected_chalan_data->grn_id = $dataOutput->id;
            $rejected_chalan_data->chalan_no = '';
            $rejected_chalan_data->reference_no = '';
            $rejected_chalan_data->remark = '';
            $rejected_chalan_data->save();
    
            // Prepare data to update admin and notification statuses
            $update_data_admin['off_canvas_status'] = 27;
            $update_data_admin['is_view'] = '0';
            $update_data_business['off_canvas_status'] = 27;
            // Update AdminView table
            AdminView::where('business_details_id', $business_application->business_details_id)
                ->update($update_data_admin);
    
            // Update NotificationStatus table
            NotificationStatus::where('business_details_id', $business_application->business_details_id)
                ->update($update_data_business);
    
            return [
                'ImageName' => $imageName ?? null, // Return the image name if uploaded
                'status' => 'success'
            ];
        } catch (\Exception $e) {
            return [
                'msg' => $e->getMessage(),
                'status' => 'error'
            ];
        }
    }
    
    public function getAllListMaterialSentFromQualityBusinessWise($id)
{
    try {
        $array_to_be_check = [config('constants.QUALITY_DEPARTMENT.PO_CHECKED_OK_GRN_GENRATED_SENT_TO_STORE')];

        $data_output = PurchaseOrdersModel::join('vendors', 'vendors.id', '=', 'purchase_orders.vendor_id')
        ->leftJoin('businesses_details', function($join) {
            $join->on('purchase_orders.business_details_id', '=', 'businesses_details.id');
        })
        ->distinct('businesses_details.id')  
        ->select(
            'purchase_orders.id',
            'purchase_orders.purchase_orders_id',         
            'vendors.vendor_name', 
            'vendors.vendor_company_name', 
            'vendors.vendor_email', 
            'vendors.vendor_address', 
            'vendors.contact_no', 
            'vendors.gst_no', 
            'purchase_orders.is_active'
        )
        // ->where('purchase_orders.business_details_id', $id)
        // ->whereIn('purchase_orders.quality_status_id', $array_to_be_check)
        ->get(); // Added to execute the query and get results
       
        return $data_output;
    } catch (\Exception $e) {
        return $e->getMessage(); // Changed to return the error message string
    }
}


public function getAllRejectedChalanList()
{
    try {
        
        $dataOutputCategory = RejectedChalan::join('grn_tbl', 'grn_tbl.purchase_orders_id', '=', 'tbl_rejected_chalan.purchase_orders_id')
        ->select(
            'tbl_rejected_chalan.purchase_orders_id',
            'grn_tbl.po_date', 
            'grn_tbl.grn_date', 
            'grn_tbl.remark', 
            'tbl_rejected_chalan.is_active'
        )
        ->groupBy('tbl_rejected_chalan.purchase_orders_id', 'grn_tbl.po_date', 'grn_tbl.grn_date', 'grn_tbl.remark', 'tbl_rejected_chalan.is_active')
        ->orderBy('tbl_rejected_chalan.purchase_orders_id', 'desc')
        ->whereNotNull('tbl_rejected_chalan.chalan_no')
        ->get();                
        return $dataOutputCategory;
    } catch (\Exception $e) {
        return $e;
    }
}
}