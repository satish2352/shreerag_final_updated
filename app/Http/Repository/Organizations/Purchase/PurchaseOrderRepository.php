<?php
namespace App\Http\Repository\Organizations\Purchase;
use Illuminate\Database\QueryException;
use DB;
use Illuminate\Support\Carbon;
use App\Models\ {
PurchaseOrdersModel,
PurchaseOrderDetailsModel
};
use Config;

class PurchaseOrderRepository  {



public function getDetailsForPurchase($id) {

    return PurchaseOrdersModel::where('id', '=', $id)->first();
   
}
    // repository
public function submitBOMToOwner($request)
{
        // dd($request);
        $purchase_orderid=str_replace(array("-",":"),"",date('Y-m-d').time());
    try {
        $dataOutput = new PurchaseOrdersModel();
        // dd($dataOutput);
        $dataOutput->purchase_orders_id = $purchase_orderid;
        $dataOutput->requisition_id = '234';
        $dataOutput->business_id = '345';
        $dataOutput->production_id = '456';
        $dataOutput->po_date = '';
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
    // dd($item);

            $designDetails = new PurchaseOrderDetailsModel();
            $designDetails->purchase_id = $last_insert_id;
            $designDetails->part_no = $item['part_no'];
            $designDetails->description = $item['description'];
            $designDetails->due_date = $item['due_date'];
            $designDetails->hsn_no = $item['hsn'];
            $designDetails->quantity = $item['quantity'];
            $designDetails->rate = $item['rate'];
            $designDetails->amount = '99';
            $designDetails->save();
        }
        \Log::info($designDetails);
        // Updating image name in PurchaseOrderModel
        // $imageName = $last_insert_id . '_' . rand(100000, 999999) . '_image.' . $request->image->getClientOriginalExtension();
        // $finalOutput = PurchaseOrderModel::find($last_insert_id);
        // $finalOutput->image = $imageName;
        // $finalOutput->save();
// dd('kkkkkkkkkkkkk');
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
   
}