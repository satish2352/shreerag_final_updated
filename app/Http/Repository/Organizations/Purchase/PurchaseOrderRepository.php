<?php
namespace App\Http\Repository\Organizations\Purchase;

use Illuminate\Database\QueryException;
use DB;
use Illuminate\Support\Carbon;
use App\Models\{
    PurchaseOrdersModel,
    PurchaseOrderDetailsModel,
    Requisition,
    BusinessApplicationProcesses,
    AdminView,
    NotificationStatus
};
use Config;

class PurchaseOrderRepository
{

    public function getDetailsForPurchase($id)
    {
        return PurchaseOrdersModel::where('id', '=', $id)->first();
    }
  
    public function submitBOMToOwner($request)
    {
        // $purchase_orderid = str_replace(array("-", ":"), "", date('Y-m-d') . time());
        // $purchase_orderid = str_replace(array("-", ":"), "", date('Y-m-d') . rand(10, 99));
        $purchase_orderid = date('Y') . mt_rand(100000, 999999);

        try {
            $requistition_id = base64_decode($request->requistition_id);
            $data_for_requistition = Requisition::where('id', $requistition_id)->first();

            $dataOutput = new PurchaseOrdersModel();
            $dataOutput->purchase_orders_id = $purchase_orderid;
            $dataOutput->requisition_id = $requistition_id;
            $dataOutput->vendor_id = $request->vendor_id;
            $dataOutput->business_id = $data_for_requistition->business_id;
            $dataOutput->business_details_id = $data_for_requistition->business_details_id;
            $dataOutput->production_id = $data_for_requistition->production_id;
            $dataOutput->po_date = date('m-d-Y');
            $dataOutput->terms_condition = '';
            $dataOutput->remark = '';
            $dataOutput->image = 'null';
            // $dataOutput->quote_no = $request->quote_no;
            $dataOutput->contact_person_name = $request->contact_person_name;
            $dataOutput->contact_person_number = $request->contact_person_number;
            $dataOutput->tax_type = $request->tax_type;
            $dataOutput->tax_id = $request->tax_id;
            $dataOutput->invoice_date = $request->invoice_date;
            $dataOutput->payment_terms = $request->payment_terms;
            $dataOutput->note = $request->note;
            $dataOutput->transport_dispatch = $request->transport_dispatch;
            $dataOutput->purchase_status_from_purchase = config('constants.PUCHASE_DEPARTMENT.PO_NEW_SENT_TO_HIGHER_AUTH_FOR_APPROVAL');
            $dataOutput->is_approve = '0';
            $dataOutput->is_active = '1';
            $dataOutput->is_deleted = '0';
            if ($request->has('quote_no')) {
                $dataOutput->quote_no = $request->quote_no;
            }
            // if ($request->has('discount')) {
            //     $dataOutput->discount = $request->discount;
            // }
            $dataOutput->save();
            $last_insert_id = $dataOutput->id;
            $businessOutput = BusinessApplicationProcesses::where('business_details_id', $data_for_requistition->business_details_id)->firstOrFail();
            $businessOutput->business_status_id = config('constants.PUCHASE_DEPARTMENT.PO_NEW_SENT_TO_HIGHER_AUTH_FOR_APPROVAL');
            $businessOutput->off_canvas_status = 23;
            $businessOutput->save();
           
              // Update admin view and notification status with the new off canvas status
              $update_data_admin['off_canvas_status'] = 23;
              $update_data_admin['is_view'] = '0';
               $update_data_business['off_canvas_status'] = 23;
            //    $update_data_business['purchase_order_is_view_po'] = '0';
               
              AdminView::where('business_details_id', $businessOutput->business_details_id)
                  // ->where('business_details_id', $production_data->business_details_id) // Corrected the condition here
                  ->update($update_data_admin);
  
              NotificationStatus::where('business_details_id', $businessOutput->business_details_id)
                  ->update($update_data_business);
                  
            foreach ($request->addmore as $index => $item) {
                $designDetails = new PurchaseOrderDetailsModel();
                $designDetails->purchase_id = $last_insert_id;
                $designDetails->part_no_id = $item['part_no_id'];
                $designDetails->description = $item['description'];
                $designDetails->discount = $item['discount'];
                $designDetails->quantity = $item['quantity'];
                $designDetails->unit = $item['unit'];
                $designDetails->hsn_id = $item['hsn_id'];
                $designDetails->actual_quantity = '0';
                $designDetails->accepted_quantity = '0';
                $designDetails->rejected_quantity = '0';
                $designDetails->rate = $item['rate'];
                $designDetails->amount = $item['amount'];
                $designDetails->save();
            }
            return [
                // 'ImageName' => $imageName,
                'status' => 'success'
            ];
        } catch (\Exception $e) {
            
            return [
                'msg' => $e->getMessage(),
                'status' => 'error'
            ];
        }
    }


    public function submitAndSentEmailToTheVendorFinalPurchaseOrder($purchase_order_id, $business_id) {
        try {
            
            $purchaseOrdersModel = PurchaseOrdersModel::where('purchase_orders_id', $purchase_order_id)->first();
            $business_application = BusinessApplicationProcesses::where('business_details_id', $business_id)->first();

            if ($purchaseOrdersModel) {
                // $purchaseOrdersModel->business_status_id = config('constants.HIGHER_AUTHORITY.LIST_APPROVED_PO_SENT_TO_VENDOR_BY_PURCHASE');
                $purchaseOrdersModel->purchase_order_mail_submited_to_vendor_date= date('Y-m-d');
                $purchaseOrdersModel->purchase_status_from_owner = config('constants.PUCHASE_DEPARTMENT.LIST_APPROVED_PO_FROM_HIGHER_AUTHORITY_SENT_TO_VENDOR');
                $purchaseOrdersModel->purchase_status_from_purchase = config('constants.PUCHASE_DEPARTMENT.LIST_APPROVED_PO_FROM_HIGHER_AUTHORITY_SENT_TO_VENDOR');
                $purchaseOrdersModel->save();
            }
           // Check if the business application exists
        if ($business_application) {
            $business_application->off_canvas_status = 25;
            $business_application->save();


            $update_data_admin['off_canvas_status'] = 25;
            $update_data_admin['is_view'] = '0';
            $update_data_business['off_canvas_status'] = 25;
            $update_data_business['po_send_to_vendor'] = '0';           
           AdminView::where('business_details_id', $business_application->business_details_id)
                ->update($update_data_admin);
            
            // Update the NotificationStatus table for the given business details
            NotificationStatus::where('business_details_id', $business_application->business_details_id)
                ->update($update_data_business);
        }
 
            return $purchaseOrdersModel;

        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getById($id) {
        try {
            // $designData = PurchaseOrdersModel::leftJoin('purchase_order_details', 'purchase_orders.id', '=', 'purchase_order_details.purchase_id')
            // ->leftJoin('purchase_order_details', 'tbl_hsn.id', '=', 'purchase_order_details.hsn_id')
            // ->select('purchase_order_details.*', 'purchase_order_details.id as purchase_order_details_id', 'purchase_orders.id as purchase_main_id', 'purchase_orders.vendor_id', 'purchase_orders.quote_no','purchase_orders.contact_person_name','purchase_orders.contact_person_number', 'purchase_orders.tax_type',
            //  'purchase_orders.tax_id','purchase_orders.invoice_date','purchase_orders.note', 
            //   'purchase_orders.payment_terms', 'purchase_orders.transport_dispatch')
            //     ->where('purchase_orders.purchase_orders_id', $id)
            //     ->get();

            $designData = PurchaseOrdersModel::leftJoin('purchase_order_details as pod1', 'purchase_orders.id', '=', 'pod1.purchase_id')
            ->leftJoin('tbl_hsn as hsn', 'hsn.id', '=', 'pod1.hsn_id')
            ->select(
                'pod1.*',
                'pod1.id as purchase_order_details_id',
                'purchase_orders.id as purchase_main_id',
                'purchase_orders.vendor_id',
                'purchase_orders.quote_no',
                'purchase_orders.contact_person_name',
                'purchase_orders.contact_person_number',
                'purchase_orders.tax_type',
                'purchase_orders.tax_id',
                'purchase_orders.invoice_date',
                'purchase_orders.note',
                'purchase_orders.payment_terms',
                'purchase_orders.transport_dispatch',
                'hsn.name as hsn_name'
            )
            ->where('purchase_orders.purchase_orders_id', $id)
            ->get();

            //    dd( $designData);
            //    die();
            if ($designData->isEmpty()) {
                return null;
            } else {
                return $designData;
            }
        } catch (\Exception $e) {
            return [
                'msg' => 'Failed to get by id Citizen Volunteer.',
                'status' => 'error',
                'error' => $e->getMessage(), 
            ];
        }
    }
    public function updateAll($request){
       
        try {
            
            // Update existing design details
            for ($i = 0; $i <= $request->design_count; $i++) {
                $designDetails = PurchaseOrderDetailsModel::findOrFail($request->input("design_id_" . $i));
                $designDetails->part_no_id = $request->input("part_no_id_" . $i);
                $designDetails->description = $request->input("description_" . $i);
                $designDetails->discount = $request->input("discount_" . $i);
                $designDetails->quantity = $request->input("quantity_" . $i);
                $designDetails->unit = $request->input("unit_" . $i);
                $designDetails->hsn_id = $request->input("hsn_id_" . $i);
                $designDetails->rate = $request->input("rate_" . $i);
                $designDetails->amount = $request->input("amount_" . $i);
                $designDetails->save();
             
            }
          
            // Update main design data
            $dataOutput = PurchaseOrdersModel::findOrFail($request->purchase_main_id);
            $dataOutput->vendor_id = $request->vendor_id;
            // $dataOutput->quote_no = $request->quote_no;
            $dataOutput->contact_person_name = $request->contact_person_name;
            $dataOutput->contact_person_number = $request->contact_person_number;
            $dataOutput->tax_type = $request->tax_type;
            $dataOutput->tax_id = $request->tax_id;
            $dataOutput->invoice_date = $request->invoice_date;
            $dataOutput->payment_terms = $request->payment_terms;
            // $dataOutput->discount = $request->discount;
            $dataOutput->note = $request->note;
            $dataOutput->purchase_status_from_owner = NULL;
            $dataOutput->transport_dispatch = $request->transport_dispatch;
            if ($request->has('quote_no')) {
                $dataOutput->quote_no = $request->quote_no;
            }
            // if ($request->has('discount')) {
            //     $dataOutput->discount = $request->discount;
            // }
           
            $dataOutput->save();

         
            
           
            // Add new design details
            if ($request->has('addmore')) {
              
                foreach ($request->addmore as $key => $item) {
                    $addPurchaseItem = new PurchaseOrderDetailsModel();
              
                    // Assuming 'purchase_id' is a foreign key related to 'PurchaseOrderModel'
                    $addPurchaseItem->purchase_id = $request->purchase_main_id; // Set the parent design ID
                    $addPurchaseItem->part_no_id = $item['part_no_id'];
                    $addPurchaseItem->description = $item['description'];
                    $addPurchaseItem->discount = $item['discount'];
                    $addPurchaseItem->quantity = $item['quantity'];
                    $addPurchaseItem->unit = $item['unit'];
                    $addPurchaseItem->hsn_id = $item['hsn_id'];
                    $addPurchaseItem->rate = $item['rate'];
                    $addPurchaseItem->amount = $item['amount'];
                    $addPurchaseItem->actual_quantity = '0';
                    $addPurchaseItem->accepted_quantity = '0';
                    $addPurchaseItem->rejected_quantity = '0';
                    
                    $addPurchaseItem->save();
                    
                  

                }
            }
            
            
            // $previousImage = $dataOutput->image;
           
            $last_insert_id = $dataOutput->id;

            $return_data['last_insert_id'] = $last_insert_id;
            // $return_data['image'] = $previousImage;
            return  $return_data;
    
            // Returning success message
            return [
                'msg' => 'Data updated successfully.',
                'status' => 'success',
                'designDetails' => $request->all()
            ];
        } catch (\Exception $e) {
            return [
                'msg' => 'Failed to update data.',
                'status' => 'error',
                'error' => $e->getMessage()
            ];
        }
    }

    public function deleteByIdAddmore($id){
        try {
            $rti = PurchaseOrderDetailsModel::find($id);
            if ($rti) {
                $rti->delete();           
                return $rti;
            } else {
                return null;
            }
        } catch (\Exception $e) {
            return $e;
        }
    }


    // New Functions for the application list PO need to be check 
    public function listAllApprovedPOToBeChecked($id)
    {
      try {
        
        $array_not_to_be_check = [
          config('constants.HIGHER_AUTHORITY.LIST_PO_TO_BE_APPROVE_FROM_PURCHASE'),
          config('constants.HIGHER_AUTHORITY.HALF_APPROVED_PO_FROM_PURCHASE')
        
        ];
        $array_to_be_check = [config('constants.PUCHASE_DEPARTMENT.PO_NEW_SENT_TO_HIGHER_AUTH_FOR_APPROVAL')];
  
        $data_output = BusinessApplicationProcesses::leftJoin('production', function ($join) {
          $join->on('business_application_processes.business_id', '=', 'production.business_id');
        })
          ->leftJoin('designs', function ($join) {
            $join->on('business_application_processes.business_id', '=', 'designs.business_id');
          })
          ->leftJoin('businesses', function ($join) {
            $join->on('business_application_processes.business_id', '=', 'businesses.id');
          })
          ->leftJoin('design_revision_for_prod', function ($join) {
            $join->on('business_application_processes.business_id', '=', 'design_revision_for_prod.business_id');
          })
          ->leftJoin('purchase_orders', function($join) {
            $join->on('business_application_processes.business_id', '=', 'purchase_orders.business_id');
          })
  
          ->where('business_application_processes.business_id', $id)
          ->whereIn('purchase_orders.purchase_status_from_purchase', $array_to_be_check)
          ->orWhereIn('business_application_processes.business_status_id', $array_not_to_be_check)
          ->whereNull('purchase_orders.grn_no')
          ->whereNull('purchase_orders.store_receipt_no')
          // ->groupBy('purchase_orders.business_id')
          // ->groupBy('business_application_processes.purchase_order_id')
          // ->groupBy('businesses.id')
          ->where('businesses.is_active', true)
          ->select(
            'purchase_orders.purchase_orders_id as purchase_order_id',
            'businesses.id',
            'businesses.title',
            'businesses.descriptions',
            'businesses.remarks',
            'businesses.is_active',
            'production.business_id',
            'design_revision_for_prod.reject_reason_prod',
            'designs.bom_image',
            'designs.design_image'
          )->get();
        return $data_output;
      } catch (\Exception $e) {
  
        return $e;
      }
    }



}