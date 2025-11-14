<?php
namespace App\Http\Repository\Organizations\Store;

use Illuminate\Database\QueryException;
use DB;
use Illuminate\Support\Carbon;
use App\Models\{
    ReturnableChalan,
    ReturnableChalanItemDetails,
    BusinessApplicationProcesses,
    ItemStock
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
        //     $dataOutput->transport_id = $request->transport_id;
            $dataOutput->vehicle_id = $request->vehicle_id;
            $dataOutput->plant_id = $request->plant_id;
            $dataOutput->tax_id = $request->tax_id;
            $dataOutput->tax_type = $request->tax_type;
            // $dataOutput->vehicle_number = $request->vehicle_number;
            $dataOutput->po_date = $request->po_date;
            $dataOutput->dc_date = now();
            $lastChalan = ReturnableChalan::orderBy('dc_number', 'desc')->first();
            $dataOutput->dc_number = $lastChalan ? $lastChalan->dc_number + 1 : 1;
            $dataOutput->lr_number = $request->lr_number;
            $dataOutput->remark = $request->remark;
             if ($request->has('transport_id')) {
                $dataOutput->transport_id = $request->transport_id;
            }
            if ($request->has('vehicle_number')) {
                $dataOutput->vehicle_number = $request->vehicle_number;
            }
            if ($request->has('business_id')) {
                $dataOutput->business_id = $request->business_id;
            }
            if ($request->has('customer_po_no')) {
                $dataOutput->customer_po_no = $request->customer_po_no;
            }
            $dataOutput->save();
            $last_insert_id = $dataOutput->id;
            foreach ($request->addmore as $index => $item) {
                $designDetails = new ReturnableChalanItemDetails();
                $designDetails->returnable_chalan_id = $last_insert_id;
                $designDetails->part_item_id = $item['part_item_id'];
                $designDetails->unit_id = $item['unit_id'];
                $designDetails->process_id = $item['process_id'];
                $designDetails->hsn_id = $item['hsn_id'];
                $designDetails->quantity = $item['quantity'];
                $designDetails->size = $item['size'];
                $designDetails->rate = isset($item['rate']) && $item['rate'] !== '' ? $item['rate'] : null; // Handle optional rate
                $designDetails->amount = $item['amount'];
                $designDetails->save();

            $partItemId = $item['part_item_id'];
            $itemStock = ItemStock::where('part_item_id', $partItemId)->first();

            if ($itemStock) {
                if ($itemStock->quantity >= $item['quantity']) {
                    $itemStock->quantity -= $item['quantity'];
                    $itemStock->save();
                } else {
                    throw new \Exception("Not enough stock for part item ID: " . $partItemId);
                }
            } else {
                throw new \Exception("Item stock not found for part item ID: " . $partItemId);
            }
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
    public function getById($id) {
        try {
            $designData = ReturnableChalan::leftJoin('tbl_returnable_chalan_item_details as retd1', 'tbl_returnable_chalan.id', '=', 'retd1.returnable_chalan_id')
            // leftJoin('tbl_returnable_chalan_item_details', 'tbl_returnable_chalan.id', '=', 'tbl_returnable_chalan_item_details.returnable_chalan_id')
            ->leftJoin('tbl_hsn as hsn', 'hsn.id', '=', 'retd1.hsn_id')  
            ->where('retd1.is_deleted', 0)   
            ->select( 'retd1.*',
            'retd1.id as tbl_returnable_chalan_item_details_id',
                
            //     'tbl_returnable_chalan_item_details.*', 
            // 'tbl_returnable_chalan_item_details.id as tbl_returnable_chalan_item_details_id', 
                'tbl_returnable_chalan.id as purchase_main_id', 
                'tbl_returnable_chalan.vendor_id',
                'tbl_returnable_chalan.transport_id',
                 'tbl_returnable_chalan.vehicle_id',
                  'tbl_returnable_chalan.business_id',
                  'tbl_returnable_chalan.tax_type', 
                  'tbl_returnable_chalan.tax_id',
                  'tbl_returnable_chalan.po_date',
                  'tbl_returnable_chalan.customer_po_no', 
                'tbl_returnable_chalan.vehicle_number',
                'tbl_returnable_chalan.plant_id', 
                'tbl_returnable_chalan.vehicle_number',
                'tbl_returnable_chalan.remark',
                // 'tbl_returnable_chalan.image',
                'tbl_returnable_chalan.id',
                'hsn.name as hsn_name')
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
                    'vendors.contact_no',
                    'vendors.quote_no',
                    'tbl_returnable_chalan.tax_type',
                    'tbl_tax.name as tax_number',
                    'tbl_returnable_chalan.vehicle_number',
                    'tbl_returnable_chalan.dc_number',
                    // 'tbl_returnable_chalan.image',
                    'tbl_returnable_chalan.dc_date',
                      'tbl_returnable_chalan.remark'
                )
                ->where('tbl_returnable_chalan.id', $return_id)
                ->where('tbl_returnable_chalan.is_deleted', 0)
                ->first();
            if (!$purchaseOrder) {
                throw new \Exception('Purchase order not found.');
            }
                $purchaseOrderDetails = ReturnableChalanItemDetails::join('tbl_part_item', 'tbl_part_item.id', '=', 'tbl_returnable_chalan_item_details.part_item_id')
                ->join('tbl_unit', 'tbl_unit.id', '=', 'tbl_returnable_chalan_item_details.unit_id')
                ->join('tbl_process_master', 'tbl_process_master.id', '=', 'tbl_returnable_chalan_item_details.process_id')
                ->join('tbl_hsn', 'tbl_hsn.id', '=', 'tbl_returnable_chalan_item_details.hsn_id')
                ->where('returnable_chalan_id', $purchaseOrder->id)
                ->where('tbl_returnable_chalan_item_details.is_deleted', 0)
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
            $dataOutputNew = ReturnableChalan::where('id', $edit_id)->first();

            if (!$dataOutputNew) {
                return [
                    'msg' => 'Returnable Chalan not found.',
                    'status' => 'error',
                ];
            }
            for ($i = 0; $i <= $request->design_count; $i++) {
                $designDetails = ReturnableChalanItemDetails::findOrFail($request->input("design_id_" . $i));
                $designDetails->part_item_id = $request->input("part_item_id_" . $i);
                $designDetails->hsn_id = $request->input("hsn_id_" . $i);
                $designDetails->process_id = $request->input("process_id_" . $i);
                $designDetails->quantity = $request->input("quantity_" . $i);
                $designDetails->unit_id = $request->input("unit_id_" . $i);
                $designDetails->size = $request->input("size_" . $i);
                // $designDetails->rate = $request->input("rate_" . $i);
                $rate = $request->input("rate_" . $i);
                $designDetails->rate = isset($rate) && $rate !== '' ? $rate : null;
                $designDetails->amount = $request->input("amount_" . $i);           
                $designDetails->save();
            }
           
            // Update main design data
            $dataOutput = ReturnableChalan::findOrFail($request->purchase_main_id);
            $dataOutput->vendor_id = $request->vendor_id;
            $dataOutput->tax_type = $request->tax_type;
            $dataOutput->tax_id = $request->tax_id;
            // $dataOutput->business_id = $request->business_id;
            // $dataOutput->transport_id = $request->transport_id;
            $dataOutput->vehicle_id = $request->vehicle_id;
            $dataOutput->customer_po_no = $request->customer_po_no;
            $dataOutput->plant_id = $request->plant_id;
            // $dataOutput->vehicle_number = $request->vehicle_number;
            $dataOutput->po_date = $request->po_date;
            $dataOutput->lr_number = $request->lr_number;
        // $lastChalan = ReturnableChalan::orderBy('dc_number', 'desc')->first();
        // $dataOutput->dc_number = $lastChalan ? $lastChalan->dc_number + 1 : 1;
            $dataOutput->remark = $request->remark;
            // $dataOutput->image = $imageName;
             if ($request->has('transport_id')) {
                $dataOutput->transport_id = $request->transport_id;
            }
            if ($request->has('vehicle_number')) {
                $dataOutput->vehicle_number = $request->vehicle_number;
            }
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
                    // $designDetails->rate = $item['rate'];
                    if (!empty($item['rate'])) {
                        $designDetails->rate = $item['rate'];
                    } else {
                        $designDetails->rate = null; // Set to null if 'rate' is missing or empty
                    }
                    $designDetails->amount = $item['amount'];
                    // $designDetails->actual_quantity = '0';
                    // $designDetails->accepted_quantity = '0';
                    // $designDetails->rejected_quantity = '0';
                  
                    $designDetails->save();
                    
                    $itemStock = ItemStock::where('part_item_id', $item['part_item_id'])->first();
                if ($itemStock) {
                    $itemStock->quantity -= $item['quantity'];
                    $itemStock->save();
                }
                 

                }
            }
            $last_insert_id = $dataOutput->id;

            $return_data['last_insert_id'] = $last_insert_id;
            return  $return_data;
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
       public function deleteById($id)
       {
           try {
               $deleteDataById = ReturnableChalan::find($id);
   
               if (!$deleteDataById) {
                   return [
                       'msg' => 'Delivery Chalan not found.',
                       'status' => 'error',
                   ];
               }
               $itemDetails = ReturnableChalanItemDetails::where('returnable_chalan_id', $id)->get();
   
               foreach ($itemDetails as $item) {
                   $itemStock = ItemStock::where('part_item_id', $item->part_item_id)->first();
   
                   if ($itemStock) {
                       $itemStock->quantity += $item->quantity;
                       $itemStock->save();
                   }
                   $item->is_deleted = 1;
                   $item->save();
               }
               $deleteDataById->is_deleted = 1;
               $deleteDataById->save();
               return [
                   'msg' => 'Record marked as deleted and stock updated successfully.',
                   'status' => 'success',
               ];
   
           } catch (\Exception $e) {
               return [
                   'msg' => 'Failed to delete the record.',
                   'status' => 'error',
                   'error' => $e->getMessage()
               ];
           }
       }
       public function deleteByIdAddmore($id)
       {
           try {
               $deleteDataById = ReturnableChalanItemDetails::where('id', $id)
                   ->where('is_deleted', 0)
                   ->firstOrFail();
               $itemStock = ItemStock::where('part_item_id', $deleteDataById->part_item_id)->first();
       
               if ($itemStock) {
                   $itemStock->quantity += $deleteDataById->quantity;
                   $itemStock->save();
               }
       
               $deleteDataById->is_deleted = 1;
               $deleteDataById->save();
       
               return [
                   'msg' => 'Record marked as deleted and stock updated successfully.',
                   'status' => 'success',
               ];
           } catch (\Exception $e) {
               return [
                   'msg' => 'Failed to delete the record.',
                   'status' => 'error',
                   'error' => $e->getMessage()
               ];
           }
       }
   



}