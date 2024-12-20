<?php
namespace App\Http\Repository\Organizations\Store;

use Illuminate\Database\QueryException;
use DB;
use Illuminate\Support\Carbon;
use App\Models\{
    ReturnableChalan,
    ReturnableChalanItemDetails,
    BusinessApplicationProcesses
};
use Config;

class ReturnableChalanRepository 
{



    public function getDetailsForPurchase($id)
    {

        return ReturnableChalan::where('id', '=', $id)->first();

    }
    public function submitBOMToOwner($request)
    {
        try {
            $dataOutput = new ReturnableChalan();
           $dataOutput->vendor_id = $request->vendor_id;
        //   $dataOutput->business_id = $request->business_id;
            $dataOutput->transport_id = $request->transport_id;
            $dataOutput->vehicle_id = $request->vehicle_id;
            $dataOutput->customer_po_no = $request->customer_po_no;
            $dataOutput->plant_id = $request->plant_id;
            $dataOutput->tax_id = $request->tax_id;
            $dataOutput->tax_type = $request->tax_type;
            $dataOutput->vehicle_number = $request->vehicle_number;
            $dataOutput->po_date = $request->po_date;
            $dataOutput->image = 'null';
            $dataOutput->dc_date = now();
            $lastChalan = ReturnableChalan::orderBy('dc_number', 'desc')->first();
            $dataOutput->dc_number = $lastChalan ? $lastChalan->dc_number + 1 : 1;
            $dataOutput->lr_number = $request->lr_number;
            $dataOutput->remark = $request->remark;
            if ($request->has('business_id')) {
                $dataOutput->business_id = $request->business_id;
            }
            $dataOutput->save();
            $last_insert_id = $dataOutput->id;
            $imageName = $last_insert_id . '_' . rand(100000, 999999) . '_image.' . $request->image->getClientOriginalExtension();
            $finalOutput = ReturnableChalan::find($last_insert_id);
            $finalOutput->image = $imageName;
            $finalOutput->save();

            foreach ($request->addmore as $index => $item) {
                $designDetails = new ReturnableChalanItemDetails();
                $designDetails->returnable_chalan_id = $last_insert_id;
                $designDetails->part_item_id = $item['part_item_id'];
                $designDetails->unit_id = $item['unit_id'];
                $designDetails->process_id = $item['process_id'];
                $designDetails->hsn_id = $item['hsn_id'];
                $designDetails->quantity = $item['quantity'];
                $designDetails->size = $item['size'];
                $designDetails->rate = $item['rate'];
                $designDetails->amount = $item['amount'];
                $designDetails->save();
            }
            return [
                'ImageName' => $imageName,
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
            $ReturnableChalan = ReturnableChalan::where('purchase_orders_id', $purchase_order_id)->first();
            if ($ReturnableChalan) {
                // $ReturnableChalan->business_status_id = config('constants.HIGHER_AUTHORITY.LIST_APPROVED_PO_SENT_TO_VENDOR_BY_PURCHASE');
                $ReturnableChalan->purchase_order_mail_submited_to_vendor_date= date('Y-m-d');
                $ReturnableChalan->purchase_status_from_owner = config('constants.PUCHASE_DEPARTMENT.LIST_APPROVED_PO_FROM_HIGHER_AUTHORITY_SENT_TO_VENDOR');
                $ReturnableChalan->purchase_status_from_purchase = config('constants.PUCHASE_DEPARTMENT.LIST_APPROVED_PO_FROM_HIGHER_AUTHORITY_SENT_TO_VENDOR');
                $ReturnableChalan->save();
            }
            return $ReturnableChalan;

        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getById($id) {
        try {
            $designData = ReturnableChalan::leftJoin('tbl_returnable_chalan_item_details', 'tbl_returnable_chalan.id', '=', 'tbl_returnable_chalan_item_details.returnable_chalan_id')
                ->select('tbl_returnable_chalan_item_details.*', 'tbl_returnable_chalan_item_details.id as tbl_returnable_chalan_item_details_id', 
                'tbl_returnable_chalan.id as purchase_main_id', 'tbl_returnable_chalan.vendor_id','tbl_returnable_chalan.transport_id', 'tbl_returnable_chalan.vehicle_id', 'tbl_returnable_chalan.business_id','tbl_returnable_chalan.tax_type', 'tbl_returnable_chalan.tax_id','tbl_returnable_chalan.po_date','tbl_returnable_chalan.customer_po_no', 
                'tbl_returnable_chalan.vehicle_number','tbl_returnable_chalan.plant_id', 'tbl_returnable_chalan.vehicle_number','tbl_returnable_chalan.remark','tbl_returnable_chalan.image','tbl_returnable_chalan.id')
                ->where('tbl_returnable_chalan.id', $id)
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
    public function getPurchaseOrderDetails($id)
    {
        
        try {

            $return_id = $id;
            $purchaseOrder = ReturnableChalan::join('vendors', 'vendors.id', '=', 'tbl_returnable_chalan.vendor_id')
            ->join('tbl_tax', 'tbl_tax.id', '=', 'tbl_returnable_chalan.tax_id')
                ->select(
                    'tbl_returnable_chalan.id',
                    'tbl_returnable_chalan.vendor_id',
                    'vendors.vendor_name', 
                    'vendors.vendor_company_name', 
                    'vendors.vendor_email', 
                    'vendors.vendor_address', 
                    'vendors.gst_no', 
                    'vendors.quote_no',
                    'tbl_returnable_chalan.tax_type',
                    'tbl_tax.name as tax_number',
                    'tbl_returnable_chalan.vehicle_number',
                    'tbl_returnable_chalan.dc_number',
                    'tbl_returnable_chalan.image',
                    'tbl_returnable_chalan.dc_date'
                )
                ->where('tbl_returnable_chalan.id', $return_id)
                ->first();
                // dd($purchaseOrder);
                // die();
            if (!$purchaseOrder) {
                throw new \Exception('Purchase order not found.');
            }
                $purchaseOrderDetails = ReturnableChalanItemDetails::join('tbl_part_item', 'tbl_part_item.id', '=', 'tbl_returnable_chalan_item_details.part_item_id')
                ->join('tbl_unit', 'tbl_unit.id', '=', 'tbl_returnable_chalan_item_details.unit_id')
                ->join('tbl_process_master', 'tbl_process_master.id', '=', 'tbl_returnable_chalan_item_details.process_id')
                ->join('tbl_hsn', 'tbl_hsn.id', '=', 'tbl_returnable_chalan_item_details.hsn_id')
                ->where('returnable_chalan_id', $purchaseOrder->id)
                ->select(
                    'tbl_returnable_chalan_item_details.part_item_id',
                     'tbl_part_item.description',
                     'tbl_part_item.part_number',
                     'tbl_returnable_chalan_item_details.unit_id',
                     'tbl_unit.name',
                     'tbl_returnable_chalan_item_details.process_id',
                     'tbl_process_master.name as process_name',
                     'tbl_returnable_chalan_item_details.hsn_id',
                     'tbl_hsn.name as hsn_name',
                     'tbl_returnable_chalan_item_details.quantity',
                     'tbl_returnable_chalan_item_details.rate',
                     'tbl_returnable_chalan_item_details.size',
                     'tbl_returnable_chalan_item_details.amount',
                )
                ->get();
            return [
                'purchaseOrder' => $purchaseOrder,
                'purchaseOrderDetails' => $purchaseOrderDetails,
            ];
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    public function updateAll($request){
       
        try {
            $return_data = array();
            $edit_id = $request->id;
    // dd($request);
    // die();
            $dataOutputNew = ReturnableChalan::where('id', $edit_id)->first();

            if (!$dataOutputNew) {
                return [
                    'msg' => 'Returnable Chalan not found.',
                    'status' => 'error',
                ];
            }
       
          $imageName = $dataOutputNew->image; 

        if ($request->hasFile('image')) {
            $imageName = $dataOutputNew->id . '_' . rand(100000, 999999) . '.' . $request->file('image')->getClientOriginalExtension();
            $dataOutputNew->image = $imageName;  
        }
        $dataOutputNew->save();

            for ($i = 0; $i <= $request->design_count; $i++) {
                $designDetails = ReturnableChalanItemDetails::findOrFail($request->input("design_id_" . $i));
                $designDetails->part_item_id = $request->input("part_item_id_" . $i);
                $designDetails->hsn_id = $request->input("hsn_id_" . $i);
                $designDetails->process_id = $request->input("process_id_" . $i);
                $designDetails->quantity = $request->input("quantity_" . $i);
                $designDetails->unit_id = $request->input("unit_id_" . $i);
                $designDetails->size = $request->input("size_" . $i);
                $designDetails->rate = $request->input("rate_" . $i);
                $designDetails->amount = $request->input("amount_" . $i);           
                $designDetails->save();
            }
           
            // Update main design data
            $dataOutput = ReturnableChalan::findOrFail($request->purchase_main_id);
            $dataOutput->vendor_id = $request->vendor_id;
            $dataOutput->tax_type = $request->tax_type;
            $dataOutput->tax_id = $request->tax_id;
            // $dataOutput->business_id = $request->business_id;
            $dataOutput->transport_id = $request->transport_id;
            $dataOutput->vehicle_id = $request->vehicle_id;
            $dataOutput->customer_po_no = $request->customer_po_no;
            $dataOutput->plant_id = $request->plant_id;
            $dataOutput->vehicle_number = $request->vehicle_number;
            $dataOutput->po_date = $request->po_date;
            $dataOutput->lr_number = $request->lr_number;
            $dataOutput->remark = $request->remark;
            $dataOutput->image = $imageName;
            if ($request->has('business_id')) {
                $dataOutput->business_id = $request->business_id;
            }
            $dataOutput->save();
            // Add new design details
            if ($request->has('addmore')) {
                foreach ($request->addmore as $key => $item) {
                    $designDetails = new ReturnableChalanItemDetails();
              
                    // Assuming 'returnable_chalan_id' is a foreign key related to 'PurchaseOrderModel'
                    $designDetails->returnable_chalan_id = $request->purchase_main_id; // Set the parent design ID
                    $designDetails->part_item_id = $item['part_item_id'];
                    $designDetails->hsn_id = $item['hsn_id'];
                    $designDetails->process_id = $item['process_id'];
                    $designDetails->quantity = $item['quantity'];
                    $designDetails->unit_id = $item['unit_id'];
                    $designDetails->size = $item['size'];
                    $designDetails->rate = $item['rate'];
                    $designDetails->amount = $item['amount'];
                    // $designDetails->actual_quantity = '0';
                    // $designDetails->accepted_quantity = '0';
                    // $designDetails->rejected_quantity = '0';
                  
                    $designDetails->save();
                    
                 

                }
            }
            
            
            // $previousImage = $dataOutput->image;
           
            $last_insert_id = $dataOutput->id;

            $return_data['last_insert_id'] = $last_insert_id;
            $return_data['image'] = $imageName;
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
        public function deleteById($id){
            try {
                $deleteDataById = ReturnableChalan::find($id);
                if ($deleteDataById) {
                    if (file_exists_view(Config::get('DocumentConstant.RETURNABLE_CHALAN_ADD') . $deleteDataById->image)){
                        removeImage(Config::get('DocumentConstant.RETURNABLE_CHALAN_ADD') . $deleteDataById->image);
                    }
                    $deleteDataById->delete();
                    
                    return $deleteDataById;
                    
                } else {
                    return null;
                }
            } catch (\Exception $e) {
                return $e;
            }
    }
        public function deleteByIdAddmore($id){
            try {
                $deleteDataById = ReturnableChalanItemDetails::find($id);
                $deleteDataById->delete();
                return $deleteDataById;
            
            } catch (\Exception $e) {
                return $e;
            }    }

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