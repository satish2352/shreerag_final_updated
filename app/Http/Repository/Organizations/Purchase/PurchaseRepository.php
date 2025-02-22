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
public function getDetailsForPurchase($id) {

    return PurchaseOrderModel::where('id', '=', $id)->first();
   
}
    // repository
public function submitBOMToOwner($request)
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
            $designDetails->part_no_id = $item['part_no_id'];
            $designDetails->description = $item['description'];
            $designDetails->due_date = $item['due_date'];
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
   
}