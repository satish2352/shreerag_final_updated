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
            $dataOutput->business_id = $data_for_requistition->business_id;
            $dataOutput->production_id = $data_for_requistition->production_id;
            $dataOutput->po_date = date('m-d-Y');
            $dataOutput->vendor_id = '';
            $dataOutput->terms_condition = '';
            $dataOutput->remark = '';
            $dataOutput->transport_dispatch = '';
            $dataOutput->image = 'null';
            $dataOutput->status = $request->status;
            $dataOutput->client_name = $request->client_name;
            $dataOutput->phone_number = $request->phone_number;
            $dataOutput->email = $request->email;
            $dataOutput->tax = $request->tax;
            $dataOutput->invoice_date = $request->invoice_date;
            $dataOutput->gst_number = $request->gst_number;
            $dataOutput->payment_terms = $request->payment_terms;
            $dataOutput->client_address = $request->client_address;
            $dataOutput->discount = $request->discount;
            $dataOutput->note = $request->note;
            $dataOutput->is_approve = '0';
            $dataOutput->is_active = '1';
            $dataOutput->is_deleted = '0';
            $dataOutput->save();
            $last_insert_id = $dataOutput->id;

            // Save data into DesignDetailsModel
            foreach ($request->addmore as $index => $item) {
                $designDetails = new PurchaseOrderDetailsModel();

                $designDetails->purchase_id = $last_insert_id;
                $designDetails->part_no = $item['part_no'];
                $designDetails->description = $item['description'];
                $designDetails->qc_check_remark = '';
                $designDetails->due_date = $item['due_date'];
                $designDetails->hsn_no = $item['hsn'];
                $designDetails->quantity = $item['quantity'];
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
                $purchaseOrdersModel->purchase_status_from_purchase = config('constants.PUCHASE_DEPARTMENT.LIST_APPROVED_PO_FROM_HIGHER_AUTHORITY_SENT_TO_VENDOR');
                $purchaseOrdersModel->save();
            }
            
            return $purchaseOrdersModel;

        } catch (\Exception $e) {
          dd($e);
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