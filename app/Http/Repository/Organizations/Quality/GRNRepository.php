<?php
namespace App\Http\Repository\Organizations\Quality;

use Illuminate\Database\QueryException;
use DB;
use Illuminate\Support\Carbon;
use App\Models\{
    GRNModel,
    PurchaseOrderDetailsModel,
    Gatepass,
    PurchaseOrderModel,
    BusinessApplicationProcesses,
    RejectedChalan,
    PurchaseOrdersModel,
    AdminView,
    NotificationStatus,
    GrnPOQuantityTracking,
    ItemStock
};
use Config;

class GRNRepository
{

    public function getAll()
    {
        try {
            // $data_output = Gatepass::where('is_checked_by_quality',false)->get();
            $data_output = Gatepass::leftJoin('purchase_orders', function ($join) {
                $join->on('gatepass.purchase_orders_id', '=', 'purchase_orders.purchase_orders_id');
            })
            ->where('gatepass.po_tracking_status', 4001)
            ->select(
                'gatepass.id',
                'purchase_orders.business_details_id',
                'gatepass.purchase_orders_id',
                'gatepass.gatepass_name', 
                'gatepass.gatepass_date', 
                'gatepass.gatepass_time', 
                'gatepass.remark',
                'gatepass.is_active'
            )
            ->orderBy('gatepass.updated_at', 'desc')
            ->get();
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getDetailsForPurchase($id)
    {
        return PurchaseOrdersModel::where('id', '=', $id)->first();
    }

    public function storeGRN($request)
{
    try {
        $gatepass = Gatepass::where('id', $request->id)->first();
        if (!$gatepass) {
            return [
                'msg' => 'Gatepass not found.',
                'status' => 'error'
            ];
        }
        $grn_no_generate = str_replace(array("-", ":"), "", date('Y-m-d') . time());
        $purchase_orders_details = PurchaseOrderModel::where('purchase_orders_id', $request->purchase_orders_id)->first();
        if (!$purchase_orders_details) {
            return [
                'msg' => 'Purchase order not found.',
                'status' => 'error'
            ];
        }

        $purchase_id = $purchase_orders_details->purchase_orders_id;
        $business_details_id = $purchase_orders_details->business_details_id;

        // Check for business application process
        $business_application = BusinessApplicationProcesses::where('business_details_id', $business_details_id)->first();
        if (!$business_application) {
            return [
                'msg' => 'Business Application not found.',
                'status' => 'error'
            ];
        }

        // Create a new GRN entry
        $dataOutput = new GRNModel();
        $dataOutput->purchase_orders_id = $purchase_id;
        $dataOutput->gatepass_id = $gatepass->id;
        $dataOutput->po_date = $request->po_date;
        $dataOutput->grn_date = $request->grn_date;
        $dataOutput->bill_no = $request->bill_no;
        $dataOutput->bill_date = $request->bill_date;
        $dataOutput->remark = $request->remark;
        $dataOutput->grn_no_generate = $grn_no_generate;
        $dataOutput->image = 'null';
        $dataOutput->is_approve = '0';
        $dataOutput->is_active = '1';
        $dataOutput->is_deleted = '0';
        $dataOutput->save();

        $last_insert_id = $dataOutput->id;

        // Update purchase order details and create GRN tracking entries
        foreach ($request->addmore as $item) {
            $purchaseOrderDetail = PurchaseOrderDetailsModel::where('id', $item['edit_id'])->first();
            if (!$purchaseOrderDetail) {
                return [
                    'msg' => "Purchase order detail not found for item ID {$item['edit_id']}.",
                    'status' => 'error'
                ];
            }

            // Update the purchase order detail
            $purchaseOrderDetail->update([
                'actual_quantity' => $item['actual_quantity'],
                'accepted_quantity' => $item['accepted_quantity'],
                'rejected_quantity' => $item['rejected_quantity'],
            ]);

            // Create GRN tracking entry
            $grnPoTracking = new GrnPOQuantityTracking();
            $grnPoTracking->purchase_order_id = $purchase_orders_details->id;
            $grnPoTracking->grn_id = $last_insert_id;
            $grnPoTracking->purchase_order_details_id = $item['edit_id'];
            // $grnPoTracking->description = $item['description'];
            $grnPoTracking->part_no_id = $purchaseOrderDetail->part_no_id;
            $grnPoTracking->rate = $purchaseOrderDetail->rate;
            $grnPoTracking->quantity = $item['chalan_quantity'];
            $grnPoTracking->actual_quantity = $item['actual_quantity'];
            $grnPoTracking->unit = $purchaseOrderDetail->unit;
            $grnPoTracking->discount = $purchaseOrderDetail->discount;
            $grnPoTracking->accepted_quantity = $item['accepted_quantity'];
            $grnPoTracking->rejected_quantity = $item['rejected_quantity'];
            $grnPoTracking->is_deleted = false;
            $grnPoTracking->is_active = true;
            $grnPoTracking->save();

               // ✅ Inventory Update logic
            $existingStock = ItemStock::where('part_item_id', $purchaseOrderDetail->part_no_id)->first();

            // if ($existingStock) {
                $existingStock->quantity += $item['accepted_quantity']; // Add new accepted qty
                $existingStock->updated_at = now();
                $existingStock->save();
            // } else {
            //     $newStock = new ItemStock();
            //     $newStock->part_item_id = $purchaseOrderDetail->part_no_id;
            //     $newStock->quantity = $item['accepted_quantity'];
            //     $newStock->created_at = now();
            //     $newStock->save();
            // }
        }
        if ($request->hasFile('image')) {
                            $imageName = $last_insert_id . '_' . rand(100000, 999999) . '_image.' . $request->image->getClientOriginalExtension();
                            $finalOutput = GRNModel::find($last_insert_id);
                            $finalOutput->image = $imageName;
                            $finalOutput->save();
                        }
        // Update purchase order with GRN number and quality status
        $purchase_orders_details->grn_no = $grn_no_generate;
        $purchase_orders_details->quality_material_sent_to_store_date = date('Y-m-d');
        $purchase_orders_details->quality_status_id = config('constants.QUALITY_DEPARTMENT.PO_CHECKED_OK_GRN_GENRATED_SENT_TO_STORE');
        $purchase_orders_details->save();

        // Update business application and gatepass statuses
        $business_application->off_canvas_status = 27;
        $business_application->save();

        Gatepass::where('id', $gatepass->id)->update([
            'po_tracking_status' => 4002,
            'is_checked_by_quality' => true,
        ]);

        // Save rejected chalan data
        $rejected_chalan_data = new RejectedChalan();
        $rejected_chalan_data->purchase_orders_id = $request->purchase_orders_id;
        $rejected_chalan_data->grn_id = $dataOutput->id;
        $rejected_chalan_data->chalan_no = '';
        $rejected_chalan_data->reference_no = '';
        $rejected_chalan_data->remark = '';
        $rejected_chalan_data->save();

        // Update admin and notification statuses
        AdminView::where('business_details_id', $business_application->business_details_id)
            ->update(['off_canvas_status' => 27, 'is_view' => '0']);

        NotificationStatus::where('business_details_id', $business_application->business_details_id)
            ->update(['off_canvas_status' => 27,  'quality_create_grn' => '0']);

        return [
            'ImageName' => $imageName ?? null,
            'status' => 'success'
        ];
    } catch (\Exception $e) {
        return [
            'msg' => $e->getMessage(),
            'status' => 'error'
        ];
    }
}
public function getAllListMaterialSentFromQualityBusinessWise($request, $id)
{
    try {
        $array_to_be_check = [config('constants.QUALITY_DEPARTMENT.PO_CHECKED_OK_GRN_GENRATED_SENT_TO_STORE')];

        $query = PurchaseOrdersModel::leftJoin('grn_tbl', 'purchase_orders.purchase_orders_id', '=', 'grn_tbl.purchase_orders_id')
            ->leftJoin('businesses_details', 'purchase_orders.business_details_id', '=', 'businesses_details.id')
            ->leftJoin('purchase_order_details', 'purchase_orders.id', '=', 'purchase_order_details.purchase_id')
            ->leftJoin('tbl_grn_po_quantity_tracking', 'purchase_orders.id', '=', 'tbl_grn_po_quantity_tracking.purchase_order_id')
            ->leftJoin('vendors', 'purchase_orders.vendor_id', '=', 'vendors.id')
            ->select(
                'purchase_orders.business_details_id',
                'purchase_orders.purchase_orders_id',
                'tbl_grn_po_quantity_tracking.grn_id',
                'vendors.vendor_name',
                'vendors.vendor_company_name',
                'vendors.vendor_email',
                'vendors.vendor_address',
                'vendors.contact_no',
                'vendors.gst_no',
                'businesses_details.product_name',
                'businesses_details.description',
                'tbl_grn_po_quantity_tracking.grn_id as tracking_grn_id'
            )
            ->whereIn('purchase_orders.quality_status_id', $array_to_be_check)
            ->where('businesses_details.id', $id);

        // Apply filters if request parameters are provided
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('grn_tbl.updated_at', [$request->from_date, $request->to_date]);
        }
        if ($request->filled('year')) {
            $query->whereYear('grn_tbl.updated_at', $request->year);
        }
        if ($request->filled('month')) {
            $query->whereMonth('grn_tbl.updated_at', $request->month);
        }

        $data_output = $query
            ->groupBy(
                'purchase_orders.business_details_id',
                'purchase_orders.purchase_orders_id',
                'tbl_grn_po_quantity_tracking.grn_id',
                'vendors.vendor_name',
                'vendors.vendor_company_name',
                'vendors.vendor_email',
                'vendors.vendor_address',
                'vendors.contact_no',
                'vendors.gst_no',
                'businesses_details.product_name',
                'businesses_details.description'
            )
            ->orderBy('tbl_grn_po_quantity_tracking.grn_id', 'desc')
            ->get();

        return $data_output;

    } catch (\Exception $e) {
        // Log the exception and return an error message
        \Log::error('Error fetching materials from quality: ' . $e->getMessage());
        throw new \Exception('Unable to fetch materials from quality.');
    }
}


public function getAllRejectedChalanList()
{
    try {
        
        $dataOutputCategory = RejectedChalan::join('grn_tbl', 'grn_tbl.purchase_orders_id', '=', 'tbl_rejected_chalan.purchase_orders_id')
        ->select(
            'tbl_rejected_chalan.purchase_orders_id',
            'grn_tbl.po_date', 
            'grn_tbl.grn_date', 
            'grn_tbl.remark', 
            'grn_tbl.bill_no', 
            'grn_tbl.bill_date', 
            'tbl_rejected_chalan.is_active'
        )
        ->groupBy('tbl_rejected_chalan.purchase_orders_id', 'grn_tbl.po_date', 'grn_tbl.grn_date', 'grn_tbl.remark', 'tbl_rejected_chalan.is_active')
        ->orderBy('tbl_rejected_chalan.purchase_orders_id', 'desc')
        ->whereNotNull('tbl_rejected_chalan.chalan_no')
        ->get();                
        return $dataOutputCategory;
    } catch (\Exception $e) {
        return $e;
    }
}
}