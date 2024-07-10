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
    RulesAndRegulations,
    Vendors
};

// use Config;

class CommanController
{




    public function getPurchaseOrderDetails($purchase_order_id)
    {
        try {
            $purchaseOrder = PurchaseOrdersModel::join('vendors', 'vendors.id', '=', 'purchase_orders.vendor_id')
            ->select(
                'purchase_orders.id',
                'purchase_orders.purchase_orders_id',
                'purchase_orders.requisition_id', 
                'purchase_orders.business_id', 
                'purchase_orders.production_id', 
                'purchase_orders.po_date', 
                'purchase_orders.terms_condition', 
                'purchase_orders.transport_dispatch', 
                'purchase_orders.image', 
                'purchase_orders.tax', 
                'purchase_orders.invoice_date', 
                'purchase_orders.payment_terms', 
                'purchase_orders.discount', 
                'vendors.vendor_name', 
                'vendors.vendor_company_name', 
                'vendors.vendor_email', 
                'vendors.vendor_address', 
                'vendors.gst_no', 
                'vendors.quote_no', 
                'purchase_orders.is_active'
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