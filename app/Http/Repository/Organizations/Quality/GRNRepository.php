<?php
namespace App\Http\Repository\Organizations\Quality;
use Illuminate\Database\QueryException;
use DB;
use Illuminate\Support\Carbon;
use App\Models\ {
    GRNModel,
PurchaseOrderDetailsModel
};
use Config;

class GRNRepository  {



public function getDetailsForPurchase($id) {

    return PurchaseOrdersModel::where('id', '=', $id)->first();
   
}
    // repository
public function storeGRN($request)
{
        // dd($request);
        // $purchase_orderid=str_replace(array("-",":"),"",date('Y-m-d').time());
    try {
        $dataOutput = new GRNModel();
        // dd($dataOutput);
        $dataOutput->purchase_id = $request->purchase_id;
        $dataOutput->po_date = $request->po_date;
        $dataOutput->grn_date = $request->grn_date;
        $dataOutput->remark = $request->remark;
        $dataOutput->image = 'null';
        $dataOutput->is_approve = '0';
        $dataOutput->is_active = '1';
        $dataOutput->is_deleted = '0';
        $dataOutput->save();
        $last_insert_id = $dataOutput->id;

        // Save data into DesignDetailsModel
        foreach ($request->addmore as $index => $item) {
    // dd($item);

    //         $designDetails = new PurchaseOrderDetailsModel();
    //         $designDetails->purchase_id = $last_insert_id;
    //         $designDetails->part_no = $item['part_no'];
    //         $designDetails->description = $item['description'];
    //         $designDetails->due_date = $item['due_date'];
    //         $designDetails->hsn_no = $item['hsn'];
    //         $designDetails->quantity = $item['quantity'];
    //         $designDetails->rate = $item['rate'];
    //         $designDetails->amount = '99';
    //         $designDetails->save();


    $user_data = PurchaseOrderDetailsModel::where('id',$item['edit_id']) 
						->update([
							// 'u_uname' => $request['u_uname'],
							'qc_check_remark' => $item['qc_check_remark'],
							'actual_quantity' => $item['actual_quantity'],
							'accepted_quantity' => $item['accepted_quantity'],
							'rejected_quantity' => $item['rejected_quantity'],
						]);
        }
        // dd($user_data);
        // dd('jjjjjjjjjjjjjjjjj');
        // \Log::info($designDetails);
        // Updating image name in PurchaseOrderModel
        $imageName = $last_insert_id . '_' . rand(100000, 999999) . '_image.' . $request->image->getClientOriginalExtension();
        $finalOutput = GRNModel::find($last_insert_id);
        $finalOutput->image = $imageName;
        $finalOutput->save();
// dd('kkkkkkkkkkkkk');
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
   
}