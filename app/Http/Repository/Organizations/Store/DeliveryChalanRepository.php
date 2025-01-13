<?php
namespace App\Http\Repository\Organizations\Store;

use Illuminate\Database\QueryException;
use DB;
use Illuminate\Support\Carbon;
use App\Models\{
    DeliveryChalan,
    DeliveryChalanItemDetails,
    BusinessApplicationProcesses
};
use Config;

class DeliveryChalanRepository
{



    public function getDetailsForPurchase($id)
    {

        return DeliveryChalan::where('id', '=', $id)->first();

    }
    public function submitBOMToOwner($request)
    {
        try {
            $dataOutput = new DeliveryChalan();
           $dataOutput->vendor_id = $request->vendor_id;
        //   $dataOutput->business_id = $request->business_id;
            $dataOutput->transport_id = $request->transport_id;
            $dataOutput->vehicle_id = $request->vehicle_id;
            $dataOutput->customer_po_no = $request->customer_po_no;
            $dataOutput->plant_id = $request->plant_id;
            $dataOutput->vehicle_number = $request->vehicle_number;
            $dataOutput->po_date = $request->po_date;
            $dataOutput->image = 'null';
           $dataOutput->dc_date = now();

        // Retrieve the last dc_number and increment it
        $lastChalan = DeliveryChalan::orderBy('dc_number', 'desc')->first();
        $dataOutput->dc_number = $lastChalan ? $lastChalan->dc_number + 1 : 1;
            $dataOutput->lr_number = $request->lr_number;

            $dataOutput->tax_type = $request->tax_type;
            $dataOutput->tax_id = $request->tax_id;

            $dataOutput->remark = $request->remark;

            if ($request->has('business_id')) {
                $dataOutput->business_id = $request->business_id;
            }
          
            $dataOutput->save();
            $last_insert_id = $dataOutput->id;
            $imageName = $last_insert_id . '_' . rand(100000, 999999) . '_image.' . $request->image->getClientOriginalExtension();
            $finalOutput = DeliveryChalan::find($last_insert_id);
            $finalOutput->image = $imageName;
            $finalOutput->save();

            foreach ($request->addmore as $index => $item) {
                $designDetails = new DeliveryChalanItemDetails();
                $designDetails->delivery_chalan_id = $last_insert_id;
                $designDetails->part_item_id = $item['part_item_id'];
                $designDetails->unit_id = $item['unit_id'];
                $designDetails->process_id = $item['process_id'];
                $designDetails->hsn_id = $item['hsn_id'];
                $designDetails->quantity = $item['quantity'];
                $designDetails->size = $item['size'];
                // $designDetails->rate = $item['rate'];
                $designDetails->rate = isset($item['rate']) && $item['rate'] !== '' ? $item['rate'] : null; // Handle optional rate
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
            $DeliveryChalan = DeliveryChalan::where('purchase_orders_id', $purchase_order_id)->first();
            if ($DeliveryChalan) {
                // $DeliveryChalan->business_status_id = config('constants.HIGHER_AUTHORITY.LIST_APPROVED_PO_SENT_TO_VENDOR_BY_PURCHASE');
                $DeliveryChalan->purchase_order_mail_submited_to_vendor_date= date('Y-m-d');
                $DeliveryChalan->purchase_status_from_owner = config('constants.PUCHASE_DEPARTMENT.LIST_APPROVED_PO_FROM_HIGHER_AUTHORITY_SENT_TO_VENDOR');
                $DeliveryChalan->purchase_status_from_purchase = config('constants.PUCHASE_DEPARTMENT.LIST_APPROVED_PO_FROM_HIGHER_AUTHORITY_SENT_TO_VENDOR');
                $DeliveryChalan->save();
            }
            return $DeliveryChalan;

        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getById($id) {
        try {
            $designData = DeliveryChalan::leftJoin('tbl_delivery_chalan_item_details as deld1', 'tbl_delivery_chalan.id', '=', 'deld1.delivery_chalan_id')
            // leftJoin('tbl_delivery_chalan_item_details', 'tbl_delivery_chalan.id', '=', 'tbl_delivery_chalan_item_details.delivery_chalan_id')
            ->leftJoin('tbl_hsn as hsn', 'hsn.id', '=', 'deld1.hsn_id')   
            ->select(  'deld1.*',
            'deld1.id as tbl_delivery_chalan_item_details_id',

            //     'tbl_delivery_chalan_item_details.*', 
            // 'tbl_delivery_chalan_item_details.id as tbl_delivery_chalan_item_details_id', 
                'tbl_delivery_chalan.id as purchase_main_id', 
                'tbl_delivery_chalan.vendor_id',
                'tbl_delivery_chalan.transport_id', 
                'tbl_delivery_chalan.vehicle_id', 'tbl_delivery_chalan.business_id',
                'tbl_delivery_chalan.tax_type', 
                'tbl_delivery_chalan.tax_id',
                'tbl_delivery_chalan.po_date','tbl_delivery_chalan.customer_po_no', 
                'tbl_delivery_chalan.vehicle_number',
                'tbl_delivery_chalan.plant_id', 
                'tbl_delivery_chalan.vehicle_number',
                'tbl_delivery_chalan.remark',
                'tbl_delivery_chalan.image',
                'tbl_delivery_chalan.id',
                'hsn.name as hsn_name')
                ->where('tbl_delivery_chalan.id', $id)
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
            $Id = (int)$id;
           
            $purchaseOrder = DeliveryChalan::leftJoin('vendors', function($join) {
                $join->on('tbl_delivery_chalan.vendor_id', '=', 'vendors.id');
            })
            ->leftJoin('tbl_tax', function($join) {
                $join->on('tbl_delivery_chalan.tax_id', '=', 'tbl_tax.id');
            })    
            ->where('tbl_delivery_chalan.id', $Id)   
            ->select('tbl_delivery_chalan.id',
                    'tbl_delivery_chalan.vendor_id',
                    'vendors.vendor_name', 
                    'vendors.vendor_company_name', 
                    'vendors.vendor_email', 
                    'vendors.contact_no', 
                    'vendors.vendor_address', 
                    'vendors.gst_no', 
                    'vendors.quote_no',
                    'tbl_delivery_chalan.tax_type',
                    'tbl_tax.name as tax_number',
                    'tbl_delivery_chalan.vehicle_number',
                    'tbl_delivery_chalan.remark',
                    'tbl_delivery_chalan.dc_number',
                    'tbl_delivery_chalan.image',
                    'tbl_delivery_chalan.dc_date',
                     'tbl_delivery_chalan.remark'
                )
                ->first();
            if (!$purchaseOrder) {
                throw new \Exception('Purchase order not found.');
            }
                $purchaseOrderDetails = DeliveryChalanItemDetails::leftJoin('tbl_part_item', function($join) {
                $join->on('tbl_delivery_chalan_item_details.part_item_id', '=', 'tbl_part_item.id');
            })
            ->leftJoin('tbl_unit', function($join) {
                $join->on('tbl_delivery_chalan_item_details.unit_id', '=', 'tbl_unit.id');
            })    
            ->leftJoin('tbl_process_master', function($join) {
                $join->on('tbl_delivery_chalan_item_details.process_id', '=', 'tbl_process_master.id');
            })      
            ->leftJoin('tbl_hsn', function($join) {
                $join->on('tbl_delivery_chalan_item_details.hsn_id', '=', 'tbl_hsn.id');
            })   
                ->where('delivery_chalan_id', $purchaseOrder->id)
                ->select(
                    'tbl_delivery_chalan_item_details.part_item_id',
                     'tbl_part_item.description',
                     'tbl_part_item.part_number',
                     'tbl_delivery_chalan_item_details.unit_id',
                     'tbl_unit.name',
                     'tbl_delivery_chalan_item_details.process_id',
                     'tbl_process_master.name as process_name',
                     'tbl_delivery_chalan_item_details.hsn_id',
                     'tbl_hsn.name as hsn_name',
                     'tbl_delivery_chalan_item_details.quantity',
                     'tbl_delivery_chalan_item_details.rate',
                     'tbl_delivery_chalan_item_details.size',
                     'tbl_delivery_chalan_item_details.amount',
                     
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
    
            $dataOutputNew = DeliveryChalan::where('id', $edit_id)->first();

            if (!$dataOutputNew) {
                return [
                    'msg' => 'Delivery Chalan not found.',
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
                $designDetails = DeliveryChalanItemDetails::findOrFail($request->input("design_id_" . $i));
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
            $dataOutput = DeliveryChalan::findOrFail($request->purchase_main_id);
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
            $dataOutput->image = $imageName;
            $dataOutput->remark = $request->remark;
            if ($request->has('business_id')) {
                $dataOutput->business_id = $request->business_id;
            }
            $dataOutput->save();
           
            // Add new design details
            if ($request->has('addmore')) {
                foreach ($request->addmore as $key => $item) {
                    $designDetails = new DeliveryChalanItemDetails();
              
                    // Assuming 'delivery_chalan_id' is a foreign key related to 'PurchaseOrderModel'
                    $designDetails->delivery_chalan_id = $request->purchase_main_id; // Set the parent design ID
                    $designDetails->part_item_id = $item['part_item_id'];
                    $designDetails->hsn_id = $item['hsn_id'];
                    $designDetails->process_id = $item['process_id'];
                    $designDetails->quantity = $item['quantity'];
                    $designDetails->unit_id = $item['unit_id'];
                    $designDetails->size = $item['size'];
                    // $designDetails->rate = $item['rate'];
                    $rate = $request->input("rate_" . $i);
                    $designDetails->rate = isset($rate) && $rate !== '' ? $rate : null;
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
        public function deleteById($id){
            try {
                $deleteDataById = DeliveryChalan::find($id);
                if ($deleteDataById) {
                    if (file_exists_view(Config::get('DocumentConstant.DELIVERY_CHALAN_DELETE') . $deleteDataById->image)){
                        removeImage(Config::get('DocumentConstant.DELIVERY_CHALAN_DELETE') . $deleteDataById->image);
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
                $deleteDataById = DeliveryChalanItemDetails::find($id);
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