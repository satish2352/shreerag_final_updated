<?php
namespace App\Http\Repository\Organizations\Purchase;

use Illuminate\Database\QueryException;
use DB;
use Illuminate\Support\Carbon;
use App\Models\{
    PurchaseOrdersModel,
    PurchaseOrderDetailsModel,
    Requisition,
    BusinessApplicationProcesses
};
use Config;

class PurchaseOrderRepository
{



    public function getDetailsForPurchase($id)
    {

        return PurchaseOrdersModel::where('id', '=', $id)->first();

    }
    // repository
    public function submitBOMToOwner($request)
    {
        $purchase_orderid = str_replace(array("-", ":"), "", date('Y-m-d') . time());
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
            $dataOutput->transport_dispatch = '';
            $dataOutput->image = 'null';
            $dataOutput->quote_no = $request->quote_no;
            // $dataOutput->status = $request->status;
            // $dataOutput->client_name = $request->client_name;
            // $dataOutput->phone_number = $request->phone_number;
            // $dataOutput->email = $request->email;
            $dataOutput->tax_type = $request->tax_type;
            $dataOutput->tax_id = $request->tax_id;
            $dataOutput->invoice_date = $request->invoice_date;
            // $dataOutput->gst_number = $request->gst_number;
            $dataOutput->payment_terms = $request->payment_terms;
            // $dataOutput->client_address = $request->client_address;
            // $dataOutput->discount = $request->discount;
            $dataOutput->note = $request->note;
            $dataOutput->purchase_status_from_purchase = config('constants.PUCHASE_DEPARTMENT.PO_NEW_SENT_TO_HIGHER_AUTH_FOR_APPROVAL');
            $dataOutput->is_approve = '0';
            $dataOutput->is_active = '1';
            $dataOutput->is_deleted = '0';

            if ($request->has('quote_no')) {
                $dataOutput->quote_no = $request->quote_no;
            }
            if ($request->has('discount')) {
                $dataOutput->discount = $request->discount;
            }
            $dataOutput->save();
            $last_insert_id = $dataOutput->id;

            // Update related BusinessApplicationProcesses record
            $businessOutput = BusinessApplicationProcesses::where('business_id', $data_for_requistition->business_id)->firstOrFail();
            $businessOutput->business_status_id = config('constants.PUCHASE_DEPARTMENT.PO_NEW_SENT_TO_HIGHER_AUTH_FOR_APPROVAL');
            $businessOutput->save();

               
            // Save data into DesignDetailsModel
            foreach ($request->addmore as $index => $item) {
                $designDetails = new PurchaseOrderDetailsModel();

                $designDetails->purchase_id = $last_insert_id;
                $designDetails->part_no_id = $item['part_no_id'];
                $designDetails->description = $item['description'];
                // $designDetails->qc_check_remark = '';
                $designDetails->due_date = $item['due_date'];
                $designDetails->quantity = $item['quantity'];
                $designDetails->unit = $item['unit'];
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


    public function submitAndSentEmailToTheVendorFinalPurchaseOrder($purchase_order_id) {
        try {
            
            $purchaseOrdersModel = PurchaseOrdersModel::where('purchase_orders_id', $purchase_order_id)->first();
            
            if ($purchaseOrdersModel) {
                // $purchaseOrdersModel->business_status_id = config('constants.HIGHER_AUTHORITY.LIST_APPROVED_PO_SENT_TO_VENDOR_BY_PURCHASE');
                $purchaseOrdersModel->purchase_order_mail_submited_to_vendor_date= date('Y-m-d');
                $purchaseOrdersModel->purchase_status_from_owner = config('constants.PUCHASE_DEPARTMENT.LIST_APPROVED_PO_FROM_HIGHER_AUTHORITY_SENT_TO_VENDOR');
                $purchaseOrdersModel->purchase_status_from_purchase = config('constants.PUCHASE_DEPARTMENT.LIST_APPROVED_PO_FROM_HIGHER_AUTHORITY_SENT_TO_VENDOR');
                $purchaseOrdersModel->save();
            }
            return $purchaseOrdersModel;

        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getById($id) {
        try {
            $designData = PurchaseOrdersModel::leftJoin('purchase_order_details', 'purchase_orders.id', '=', 'purchase_order_details.purchase_id')
                ->select('purchase_order_details.*', 'purchase_order_details.id as purchase_order_details_id', 'purchase_orders.id as purchase_main_id', 'purchase_orders.vendor_id', 'purchase_orders.quote_no', 'purchase_orders.tax_type', 'purchase_orders.tax_id','purchase_orders.invoice_date','purchase_orders.quote_no','purchase_orders.note',  'purchase_orders.payment_terms','purchase_orders.discount')
                ->where('purchase_orders.purchase_orders_id', $id)
                ->get();
               
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
                $designDetails->due_date = $request->input("due_date_" . $i);
                $designDetails->quantity = $request->input("quantity_" . $i);
                $designDetails->unit = $request->input("unit_" . $i);
                $designDetails->rate = $request->input("rate_" . $i);
                $designDetails->amount = $request->input("amount_" . $i);
                $designDetails->save();
            }
    
            // Update main design data
            $dataOutput = PurchaseOrdersModel::findOrFail($request->purchase_main_id);
            $dataOutput->vendor_id = $request->vendor_id;
            // $dataOutput->quote_no = $request->quote_no;
            $dataOutput->tax_type = $request->tax_type;
            $dataOutput->tax_id = $request->tax_id;
            $dataOutput->invoice_date = $request->invoice_date;
            $dataOutput->payment_terms = $request->payment_terms;
            // $dataOutput->discount = $request->discount;
            $dataOutput->note = $request->note;

            if ($request->has('quote_no')) {
                $dataOutput->quote_no = $request->quote_no;
            }
            if ($request->has('discount')) {
                $dataOutput->discount = $request->discount;
            }
            $dataOutput->save();

           
            
           
            // Add new design details
            if ($request->has('addmore')) {
                foreach ($request->addmore as $key => $item) {
                    $designDetails = new PurchaseOrderDetailsModel();
              
                    // Assuming 'purchase_id' is a foreign key related to 'PurchaseOrderModel'
                    $designDetails->purchase_id = $request->purchase_main_id; // Set the parent design ID
                    $designDetails->part_no_id = $item['part_no_id'];
                    $designDetails->description = $item['description'];
                    $designDetails->due_date = $item['due_date'];
                    $designDetails->quantity = $item['quantity'];
                    $designDetails->unit = $item['unit'];
                    $designDetails->rate = $item['rate'];
                    $designDetails->amount = $item['amount'];
                    $designDetails->actual_quantity = '0';
                    $designDetails->accepted_quantity = '0';
                    $designDetails->rejected_quantity = '0';
                  
                    $designDetails->save();
                    
                 

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