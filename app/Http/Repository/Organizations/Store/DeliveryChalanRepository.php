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
          $dataOutput->business_id = $request->business_id;
            $dataOutput->transport_id = $request->transport_id;
            $dataOutput->vehicle_id = $request->vehicle_id;
            $dataOutput->plant_id = $request->plant_id;
            $dataOutput->vehicle_number = $request->vehicle_number;
            $dataOutput->po_date = $request->po_date;
            $dataOutput->dc_date = $request->dc_date;
            $dataOutput->dc_number = $request->dc_number;
            $dataOutput->lr_number = $request->lr_number;
            $dataOutput->remark = $request->remark;
          
            $dataOutput->save();
            $last_insert_id = $dataOutput->id;

            foreach ($request->addmore as $index => $item) {
                $designDetails = new DeliveryChalanItemDetails();
                $designDetails->delivery_chalan_id = $last_insert_id;
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
    // public function getById($id) {
    //     try {
    //         $designData = DeliveryChalan::leftJoin('purchase_order_details', 'purchase_orders.id', '=', 'purchase_order_details.purchase_id')
    //             ->select('purchase_order_details.*', 'purchase_order_details.id as purchase_order_details_id', 'purchase_orders.id as purchase_main_id', 'purchase_orders.vendor_id', 'purchase_orders.quote_no', 'purchase_orders.tax_type', 'purchase_orders.tax_id','purchase_orders.invoice_date','purchase_orders.quote_no','purchase_orders.note',  'purchase_orders.payment_terms','purchase_orders.discount')
    //             ->where('purchase_orders.purchase_orders_id', $id)
    //             ->get();
               
    //         if ($designData->isEmpty()) {
    //             return null;
    //         } else {
    //             return $designData;
    //         }
    //     } catch (\Exception $e) {
    //         return [
    //             'msg' => 'Failed to get by id Citizen Volunteer.',
    //             'status' => 'error',
    //             'error' => $e->getMessage(), 
    //         ];
    //     }
    // }

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
                    'vendors.vendor_address', 
                    'vendors.gst_no', 
                    'vendors.quote_no',
                    'tbl_delivery_chalan.tax_type',
                    'tbl_tax.name as tax_number',
                    'tbl_delivery_chalan.vehicle_number'
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
            // Update existing design details
            for ($i = 0; $i <= $request->design_count; $i++) {
                $designDetails = DeliveryChalanItemDetails::findOrFail($request->input("design_id_" . $i));
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
            $dataOutput = DeliveryChalan::findOrFail($request->purchase_main_id);
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
                    $designDetails = new DeliveryChalanItemDetails();
              
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
            $rti = DeliveryChalanItemDetails::find($id);
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