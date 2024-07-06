<?php
namespace App\Http\Controllers\Organizations;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
// use DB;
use Illuminate\Support\Carbon;
use App\Models\{
    Business,
    DesignModel,
    BusinessApplicationProcesses,
    PurchaseOrderDetailsModel,
    PurchaseOrdersModel,
    OrganizationModel,
    RulesAndRegulations
};

// use Config;

class CommanController
{




    public function getPurchaseOrderDetails($purchase_order_id)
    {
        try {
            // Fetch the Purchase Order
            $purchaseOrder = PurchaseOrdersModel::where('purchase_orders_id', $purchase_order_id)
                ->select(
                    'id',
                    'purchase_orders_id',
                    'requisition_id',
                    'business_id',
                    'production_id',
                    'po_date',
                    'vendor_id',
                    'terms_condition',
                    'transport_dispatch',
                    'image',
                    // 'status',
                    'client_name',
                    'phone_number',
                    'email',
                    'tax',
                    'invoice_date',
                    'gst_number',
                    'payment_terms',
                    'client_address',
                    'discount',
                    'note',
                    'created_at'
                )
                ->first();

            // Fetch related Purchase Order Details
            $purchaseOrderDetails = PurchaseOrderDetailsModel::where('purchase_id', $purchaseOrder->id)
                ->select(
                    'purchase_id',
                    'part_no',
                    'description',
                    // 'qc_check_remark',
                    'due_date',
                    'hsn_no',
                    'quantity',
                    'actual_quantity',
                    'accepted_quantity',
                    'rejected_quantity',
                    'rate',
                    'amount'
                )
                ->get();



            return [
                'purchaseOrder' => $purchaseOrder,
                'purchaseOrderDetails' => $purchaseOrderDetails,
            ];
        } catch (\Exception $e) {
            return $e;
        }
    }


    public function getAllOrganizationData()
    {
        try {
            $dataOutputCategory = Business::join('tbl_organizations', 'tbl_organizations.id', '=', 'businesses.organization_id')
                ->select(
                    'tbl_organizations.id',
                    'tbl_organizations.company_name',
                    'tbl_organizations.email',
                    'tbl_organizations.mobile_number',
                    'tbl_organizations.address',
                    'tbl_organizations.image',
                )
                ->first();

            return $dataOutputCategory;
        } catch (\Exception $e) {
            return $e;
        }
    }

    function getAllRulesAndRegulations() {
        try {
            $rulesAndRegulationsDetails = RulesAndRegulations::first();;
            return $rulesAndRegulationsDetails;
        } catch (Exception $e) {
            return $e;
        }
    }
    
}