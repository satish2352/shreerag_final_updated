<?php
namespace App\Http\Repository\Organizations\Purchase;
use Illuminate\Database\QueryException;
use DB;
use Illuminate\Support\Carbon;
use App\Models\ {
PurchaseOrderModel,
PurchaseOrderDetailsModel
};
use Config;

class PurchaseRepository  {


    public function getAll(){
        try {
            $data_output= PurchaseOrderModel::get();

            // $data_output = PurchaseOrderModel::leftJoin('purchase_order_details', 'purchase_orders.id', '=', 'purchase_order_details.purchase_id')
            // ->select('purchase_order_details.*','purchase_order_details.id as designs_details_id', 'purchase_orders.id as purchase_main_id', 'purchase_orders.vendor_id', 'purchase_orders.po_date', 'purchase_orders.terms_condition', 'purchase_orders.image')
            // ->where('purchase_orders.id', $id)
            // ->get();
          
            return $data_output;
        } catch (\Exception $e) {
      
            return $e;
        }
    }
    // repository
public function addAll($request)
{
    try {
        $dataOutput = new PurchaseOrderModel();
        $dataOutput->po_date = $request->po_date;
        $dataOutput->vendor_id = $request->vendor_id;
        $dataOutput->terms_condition = $request->terms_condition;
        $dataOutput->remark = $request->remark;
        $dataOutput->transport_dispatch = $request->transport_dispatch;
        $dataOutput->image = 'null';
        $dataOutput->save();
        $last_insert_id = $dataOutput->id;

        // Save data into DesignDetailsModel
        foreach ($request->addmore as $item) {
            $designDetails = new PurchaseOrderDetailsModel();
            $designDetails->purchase_id = $last_insert_id;
            $designDetails->part_no = $item['part_no'];
            $designDetails->description = $item['description'];
            $designDetails->due_date = $item['due_date'];
            $designDetails->hsn_no = $item['hsn_no'];
            $designDetails->quantity = $item['quantity'];
            $designDetails->rate = $item['rate'];
            $designDetails->amount = $item['amount'];
            $designDetails->save();
        }

        // Updating image name in PurchaseOrderModel
        $imageName = $last_insert_id . '_' . rand(100000, 999999) . '_image.' . $request->image->getClientOriginalExtension();
        $finalOutput = PurchaseOrderModel::find($last_insert_id);
        $finalOutput->image = $imageName;
        $finalOutput->save();

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
    public function getById($id) {
        try {
            $designData = PurchaseOrderModel::leftJoin('purchase_order_details', 'purchase_order.id', '=', 'purchase_order_details.purchase_id')
                ->select('purchase_order_details.*','purchase_order_details.id as purchase_order_details_id', 'purchase_order.id as purchase_main_id', 'purchase_order.vendor_id', 'purchase_order.po_date', 'purchase_order.remark', 'purchase_order.image')
                ->where('purchase_order.id', $id)
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
                
                $designDetails->part_no = $request->input("part_no_" . $i);
                $designDetails->description = $request->input("description_" . $i);
                $designDetails->due_date = $request->input("due_date_" . $i);
                $designDetails->hsn_no = $request->input("hsn_no_" . $i);
                $designDetails->quantity = $request->input("quantity_" . $i);
                $designDetails->rate = $request->input("rate_" . $i);
                $designDetails->amount = $request->input("amount_" . $i);
                $designDetails->save();
            }
    
            // Update main design data
            $dataOutput = PurchaseOrderModel::findOrFail($request->design_main_id);
            $dataOutput->po_date = $request->po_date;
            $dataOutput->vendor_id = $request->vendor_id;
            $dataOutput->terms_condition = $request->terms_condition;
            $dataOutput->remark = $request->remark;
            $dataOutput->transport_dispatch = $request->transport_dispatch;
            $dataOutput->save();
   
            // Add new design details
            if ($request->has('addmore')) {
                foreach ($request->addmore as $key => $item) {
                    $designDetails = new PurchaseOrderDetailsModel();
              
                    // Assuming 'purchase_id' is a foreign key related to 'PurchaseOrderModel'
                    $designDetails->purchase_id = $request->design_main_id; // Set the parent design ID
                    $designDetails->part_no = $item['part_no'];
                    $designDetails->description = $item['description'];
                    $designDetails->due_date = $item['due_date'];
                    $designDetails->hsn_no = $item['hsn_no'];
                    $designDetails->quantity = $item['quantity'];
                    $designDetails->rate = $item['rate'];
                    $designDetails->amount = $item['amount'];
                  
                 
                    $designDetails->save();
                     
                }
            }
    
            // Updating image name in PurchaseOrderModel if a new image is uploaded
            if ($request->hasFile('image')) {
                $imageName = $dataOutput->id . '_' . rand(100000, 999999) . '_image.' . $request->image->getClientOriginalExtension();
                $dataOutput->image = $imageName;
                $dataOutput->save();
            }
    
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
                $deleteDataById = DesignModel::find($id);
                
                if ($deleteDataById) {
                    if (file_exists_view(Config::get('DocumentConstant.DESIGNS_DELETE') . $deleteDataById->image)){
                        removeImage(Config::get('DocumentConstant.DESIGNS_DELETE') . $deleteDataById->image);
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
}