<?php
namespace App\Http\Repository\Organizations\Security;

use Illuminate\Database\QueryException;
use DB;
use Illuminate\Support\Carbon;
use App\Models\{
    Gatepass,
    PurchaseOrderModel,
    BusinessApplicationProcesses,
    PurchaseOrdersModel
};
use Config;

class GatepassRepository
{

    public function getAll()
    {
        try {
            $data_output = Gatepass::orderBy('updated_at', 'desc')->get();
    
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }

    // public function getPurchaseDetails($id)
    // {
    //     try {
    //         $purchaseOrder = PurchaseOrdersModel::join('vendors', 'vendors.id', '=', 'purchase_orders.vendor_id')
              
    //         ->select(
    //                 'purchase_orders.id',
    //                 'purchase_orders.purchase_orders_id',
    //                 'purchase_orders.requisition_id', 
    //                 'purchase_orders.business_id', 
    //                 'purchase_orders.business_details_id', 
    //                 'purchase_orders.production_id', 
    //                 'purchase_orders.po_date', 
    //                 'purchase_orders.terms_condition', 
    //                 'purchase_orders.transport_dispatch', 
    //                 'purchase_orders.purchase_status_from_purchase',
    //                 'purchase_orders.image', 
    //                 'purchase_orders.tax_type', 
    //                 'purchase_orders.tax_id', 
    //                 'purchase_orders.invoice_date', 
    //                 'purchase_orders.payment_terms', 
    //                 'purchase_orders.discount', 
    //                 'vendors.vendor_name', 
    //                 'vendors.vendor_company_name', 
    //                 'vendors.vendor_email', 
    //                 'vendors.vendor_address', 
    //                 'vendors.gst_no', 
    //                 'vendors.quote_no', 
    //                 'purchase_orders.is_active',
    //                 'purchase_orders.created_at'
    //             )
    //             ->where('purchase_orders.purchase_orders_id', $purchase_order_id)
    //             ->first();
  
    //         if (!$purchaseOrder) {
    //             throw new \Exception('Purchase order not found.');
    //         }
    
    //         // Fetch related Purchase Order Details
    //         $purchaseOrderDetails = PurchaseOrderDetailsModel::join('tbl_part_item', 'tbl_part_item.id', '=', 'purchase_order_details.part_no_id')

    //         ->where('purchase_id', $purchaseOrder->id)
    //             ->select(
    //                 'purchase_order_details.purchase_id',
    //                 'purchase_order_details.part_no_id',
    //                 'tbl_part_item.name',
    //                 'purchase_order_details.description',
    //                 'purchase_order_details.due_date',
    //                 'purchase_order_details.quantity',
    //                 'purchase_order_details.unit',
    //                 'purchase_order_details.actual_quantity',
    //                 'purchase_order_details.accepted_quantity',
    //                 'purchase_order_details.rejected_quantity',
    //                 'purchase_order_details.rate',
    //                 'purchase_order_details.amount'
    //             )
    //             ->get();
   
    //         return [
    //             'purchaseOrder' => $purchaseOrder,
    //             'purchaseOrderDetails' => $purchaseOrderDetails,
    //         ];
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => $e->getMessage()], 500);
    //     }
    // }
    
 
    

    public function addAll($request)
    {
        $existingGatepass = Gatepass::where('purchase_orders_id', $request->purchase_orders_id)->first();
        
        if ($existingGatepass) {
            return [
                'msg' => 'Gate pass already exists for this purchase order.',
                'status' => 'error'
            ];
        }

        
        $dataOutput = new Gatepass();
        $dataOutput->purchase_orders_id = $request->purchase_orders_id;
        $dataOutput->gatepass_name = $request->gatepass_name;
        $dataOutput->gatepass_date = $request->gatepass_date;
        $dataOutput->gatepass_time = $request->gatepass_time;
        $dataOutput->remark = $request->remark;
        $dataOutput->save();
        $last_insert_id = $dataOutput->id;

        $purchase_orders_details = PurchaseOrderModel::where('purchase_orders_id', $request->purchase_orders_id)->first();
        
        if ($purchase_orders_details) {
            $purchase_orders_details->store_material_recived_for_grn_date = date('Y-m-d');
            $purchase_orders_details->store_status_id = config('constants.STORE_DEPARTMENT.LIST_BOM_PART_MATERIAL_SENT_TO_PROD_DEPT_FOR_PRODUCTION');
            $purchase_orders_details->security_material_recived_date = date('Y-m-d');
            $purchase_orders_details->security_status_id = config('constants.QUALITY_DEPARTMENT.LIST_PO_RECEIVED_FROM_SECURITY');
            $purchase_orders_details->save();
        }

        return [
            'msg' => 'Data Added Successfully',
            'status' => 'success'
        ];
    }
   
    public function getById($id)
    {
        try {
            $dataOutputByid = Gatepass::find($id);
            if ($dataOutputByid) {
                return $dataOutputByid;
            } else {
                return null;
            }
        } catch (\Exception $e) {
            return [
                'msg' => $e,
                'status' => 'error'
            ];
        }
    }
    
    public function updateAll($request)
    {
        try {
            // dd($request->purchase_orders_id);
            // die();
            // Find the Gatepass by ID
            $dataOutput = Gatepass::findOrFail($request->purchase_orders_id);
         
            // Update fields
            $dataOutput->purchase_orders_id = $request->purchase_orders_id;
            $dataOutput->gatepass_name = $request->gatepass_name;
            $dataOutput->gatepass_date = $request->gatepass_date;
            $dataOutput->gatepass_time = $request->gatepass_time;
            $dataOutput->remark = $request->remark;
          
            $dataOutput->save();
      
            // Return success message
            return [
                'msg' => 'Data updated successfully.',
                'status' => 'success',
                'designDetails' => $dataOutput
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
            $deleteDataById = Gatepass::find($id);

            if ($deleteDataById) {
                // if (file_exists_view(Config::get('FileConstant.STORE_RECEIPT_DELETE') . $deleteDataById->image)){
                //     removeImage(Config::get('FileConstant.STORE_RECEIPT_DELETE') . $deleteDataById->image);
                // }
                $deleteDataById->delete();

                return $deleteDataById;
            } else {
                return null;
            }
        } catch (\Exception $e) {
            return $e;
        }
    }
}