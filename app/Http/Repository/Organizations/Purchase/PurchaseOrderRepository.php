<?php
namespace App\Http\Repository\Organizations\Purchase;

use Illuminate\Database\QueryException;
use DB;
use Illuminate\Support\Carbon;
use App\Models\{
    PurchaseOrdersModel,
    PurchaseOrderDetailsModel,
    Requisition,
    BusinessApplicationProcesses
};
use Config;

class PurchaseOrderRepository
{



    public function getDetailsForPurchase($id)
    {

        return PurchaseOrdersModel::where('id', '=', $id)->first();

    }
    // repository
    public function submitBOMToOwner($request)
    {
        // dd($request);
        $purchase_orderid = str_replace(array("-", ":"), "", date('Y-m-d') . time());
        try {

            $requistition_id = base64_decode($request->requistition_id);

            $data_for_requistition = Requisition::where('id', $requistition_id)->first();

            $dataOutput = new PurchaseOrdersModel();
            $dataOutput->purchase_orders_id = $purchase_orderid;
            $dataOutput->requisition_id = $requistition_id;
            $dataOutput->business_id = $data_for_requistition->business_id;
            $dataOutput->production_id = $data_for_requistition->production_id;
            $dataOutput->po_date = date('m-d-Y');
            $dataOutput->vendor_id = '';
            $dataOutput->terms_condition = '';
            $dataOutput->remark = '';
            $dataOutput->transport_dispatch = '';
            $dataOutput->image = 'null';
            $dataOutput->quote_no = $request->quote_no;
            // $dataOutput->status = $request->status;
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
                $designDetails = new PurchaseOrderDetailsModel();

                $designDetails->purchase_id = $last_insert_id;
                $designDetails->part_no = $item['part_no'];
                $designDetails->description = $item['description'];
                // $designDetails->qc_check_remark = '';
                $designDetails->due_date = $item['due_date'];
                $designDetails->hsn_no = $item['hsn'];
                $designDetails->quantity = $item['quantity'];
                $designDetails->actual_quantity = '0';
                $designDetails->accepted_quantity = '0';
                $designDetails->rejected_quantity = '0';
                $designDetails->rate = $item['rate'];
                $designDetails->amount = $item['amount'];
                $designDetails->save();
            }


            return [
                // 'ImageName' => $imageName,
                'status' => 'success'
            ];
        } catch (\Exception $e) {
            dd($e);
            return [
                'msg' => $e->getMessage(),
                'status' => 'error'
            ];
        }
    }


    public function submitAndSentEmailToTheVendorFinalPurchaseOrder($purchase_status_id) {
        try {
            
            $business_application = BusinessApplicationProcesses::where('purchase_order_id', $purchase_status_id)->first();
            
            if ($business_application) {
                $business_application->business_status_id = config('constants.HIGHER_AUTHORITY.LIST_APPROVED_PO_SENT_TO_VENDOR_BY_PURCHASE');
                $business_application->purchase_order_mail_submited_to_vendor_date= date('Y-m-d');
                $business_application->purchase_status_id = config('constants.PUCHASE_DEPARTMENT.LIST_APPROVED_PO_FROM_HIGHER_AUTHORITY_SENT_TO_VENDOR');
                $business_application->save();
            }
            
            return $business_application;

        } catch (\Exception $e) {
            return $e;
        }
    }

}