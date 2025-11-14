<?php
namespace App\Http\Repository\Organizations\Store;

use Illuminate\Database\QueryException;
use DB;
use Illuminate\Support\Carbon;
use App\Models\{
    DeliveryChalan,
    DeliveryChalanItemDetails,
    BusinessApplicationProcesses,
    ItemStock
};
use Config;

class DeliveryChalanRepository
{

    public function getDetailsForPurchase($id){
        return DeliveryChalan::where('id', '=', $id)->first();
    }
    public function submitBOMToOwner($request){
        try {

            $dataOutput = new DeliveryChalan();
            $dataOutput->vendor_id = $request->vendor_id;
            // $dataOutput->transport_id = $request->transport_id;
            $dataOutput->vehicle_id = $request->vehicle_id;
            $dataOutput->customer_po_no = $request->customer_po_no;
            $dataOutput->plant_id = $request->plant_id;
            // $dataOutput->vehicle_number = $request->vehicle_number;
            $dataOutput->po_date = $request->po_date;
            $dataOutput->dc_date = now();

            $lastChalan = DeliveryChalan::orderBy('dc_number', 'desc')->first();
            $dataOutput->dc_number = $lastChalan ? $lastChalan->dc_number + 1 : 1;
            $dataOutput->lr_number = $request->lr_number;
            $dataOutput->tax_type = $request->tax_type;
            $dataOutput->tax_id = $request->tax_id;
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
                $designDetails->rate = isset($item['rate']) && $item['rate'] !== '' ? $item['rate'] : null; // Handle optional rate
                $designDetails->amount = $item['amount'];
                $designDetails->save();

                // Handle stock deduction
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
            $designData = DeliveryChalan::leftJoin('tbl_delivery_chalan_item_details as deld1', 'tbl_delivery_chalan.id', '=', 'deld1.delivery_chalan_id')
            ->leftJoin('tbl_hsn as hsn', 'hsn.id', '=', 'deld1.hsn_id')   
            ->where('deld1.is_deleted', 0)
            ->select(  'deld1.*',
            'deld1.id as tbl_delivery_chalan_item_details_id',            
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
             ->leftJoin('tbl_transport_name', function($join) {
                $join->on('tbl_delivery_chalan.transport_id', '=', 'tbl_transport_name.id');
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
                    'tbl_delivery_chalan.dc_date',
                     'tbl_delivery_chalan.remark',
                     'tbl_transport_name.name as transport_name'
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
                ->where('tbl_delivery_chalan_item_details.is_deleted', 0)
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
    public function updateAll($request) {
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
            for ($i = 0; $i < $request->design_count; $i++) {
                $designId = $request->input("design_id_" . $i);
                if ($designId) {  
                    $designDetails = DeliveryChalanItemDetails::findOrFail($designId);
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
            }
            $dataOutput = DeliveryChalan::findOrFail($request->purchase_main_id);
            $dataOutput->vendor_id = $request->vendor_id;
            $dataOutput->tax_type = $request->tax_type;
            $dataOutput->tax_id = $request->tax_id;
            // $dataOutput->transport_id = $request->transport_id;
            $dataOutput->vehicle_id = $request->vehicle_id;
            $dataOutput->customer_po_no = $request->customer_po_no;
            $dataOutput->plant_id = $request->plant_id;
            // $dataOutput->vehicle_number = $request->vehicle_number;
            $dataOutput->po_date = $request->po_date;
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
            $dataOutput->save();
    
            if ($request->has('addmore')) {
                foreach ($request->addmore as $item) {
                    $designDetails = new DeliveryChalanItemDetails();
                    $designDetails->delivery_chalan_id = $request->purchase_main_id;
                    $designDetails->part_item_id = $item['part_item_id'];
                    $designDetails->hsn_id = $item['hsn_id'];
                    $designDetails->process_id = $item['process_id'];
                    $designDetails->quantity = $item['quantity'];
                    $designDetails->unit_id = $item['unit_id'];
                    $designDetails->size = $item['size'];
                    $designDetails->rate = $item['rate'] ?? null;  
                    $designDetails->amount = $item['amount'];
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
    
            return $return_data;
    
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
            $deleteDataById = DeliveryChalan::find($id);

            if (!$deleteDataById) {
                return [
                    'msg' => 'Delivery Chalan not found.',
                    'status' => 'error',
                ];
            }
            $itemDetails = DeliveryChalanItemDetails::where('delivery_chalan_id', $id)->get();

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
            $deleteDataById = DeliveryChalanItemDetails::where('id', $id)
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