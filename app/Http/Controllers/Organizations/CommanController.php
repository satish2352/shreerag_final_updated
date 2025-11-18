<?php

namespace App\Http\Controllers\Organizations;

use Exception;
use App\Models\{
    Business,
    PurchaseOrderDetailsModel,
    PurchaseOrdersModel,
    RulesAndRegulations,
    PurchaseOrderModel
};

// use Illuminate\Support\Facades\Config;

class CommanController
{

    public function getPurchaseOrderDetails($purchase_order_id)
    {
        try {
            $purchaseOrder = PurchaseOrdersModel::leftJoin('vendors', function ($join) {
                $join->on('purchase_orders.vendor_id', '=', 'vendors.id');
            })
                ->leftJoin('tbl_tax', function ($join) {
                    $join->on('purchase_orders.tax_id', '=', 'tbl_tax.id');
                })

                ->leftJoin('business_application_processes', function ($join) {
                    $join->on('purchase_orders.business_details_id', '=', 'business_application_processes.business_details_id');
                })
                ->select(
                    'purchase_orders.id',
                    'purchase_orders.purchase_orders_id',
                    'purchase_orders.requisition_id',
                    'purchase_orders.business_id',
                    'purchase_orders.business_details_id',
                    'purchase_orders.production_id',
                    'purchase_orders.po_date',
                    'purchase_orders.terms_condition',
                    'purchase_orders.transport_dispatch',
                    'purchase_orders.purchase_status_from_purchase',
                    'purchase_orders.purchase_status_from_owner',
                    'purchase_orders.contact_person_name',
                    'purchase_orders.contact_person_number',
                    'purchase_orders.image',
                    'purchase_orders.tax_type',
                    'purchase_orders.tax_id',
                    'tbl_tax.name',
                    'purchase_orders.invoice_date',
                    'purchase_orders.payment_terms',
                    // 'purchase_orders.discount', 
                    'purchase_orders.note',
                    'vendors.vendor_name',
                    'vendors.vendor_company_name',
                    'vendors.contact_no',
                    'vendors.vendor_email',
                    'vendors.vendor_address',
                    'vendors.gst_no',
                    'vendors.quote_no',
                    'purchase_orders.is_active',
                    'purchase_orders.created_at',
                    'business_application_processes.business_status_id',
                    'business_application_processes.off_canvas_status'
                )
                ->where('purchase_orders.purchase_orders_id', $purchase_order_id)
                ->first();

            if (!$purchaseOrder) {
                throw new \Exception('Purchase order not found.');
            }

            // Fetch related Purchase Order Details
            $purchaseOrderDetails = PurchaseOrderDetailsModel::join('tbl_part_item', 'tbl_part_item.id', '=', 'purchase_order_details.part_no_id')
                ->leftJoin('tbl_hsn as pod_hsn', function ($join) {
                    $join->on('pod_hsn.id', '=', 'purchase_order_details.hsn_id');
                })
                ->leftJoin('tbl_unit as pod_unit', function ($join) {
                    $join->on('pod_unit.id', '=', 'purchase_order_details.unit');
                })
                ->where('purchase_id', $purchaseOrder->id)
                ->select(
                    'purchase_order_details.purchase_id',
                    'purchase_order_details.part_no_id',
                    'tbl_part_item.description as item_description',
                    'purchase_order_details.description',
                    'purchase_order_details.discount',
                    'purchase_order_details.quantity',
                    'purchase_order_details.unit',
                    'purchase_order_details.actual_quantity',
                    'purchase_order_details.accepted_quantity',
                    'purchase_order_details.rejected_quantity',
                    'pod_hsn.name as hsn_name',
                    'pod_unit.name as unit_name',
                    'purchase_order_details.rate',
                    'purchase_order_details.amount'
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
                    'tbl_organizations.gst_no',
                    'tbl_organizations.cin_number',
                )
                ->first();

            return $dataOutputCategory;
        } catch (\Exception $e) {
            return $e;
        }
    }

    function getAllRulesAndRegulations()
    {
        try {
            $rulesAndRegulationsDetails = RulesAndRegulations::first();;
            return $rulesAndRegulationsDetails;
        } catch (Exception $e) {
            return $e;
        }
    }

    public function checkMultiplePurchaseIDAreThereOrNot()
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


    public function getNumberOfPOCount($business_id, $purchase_order_id)
    {

        $purchase_status_from_owner = [config('constants.HIGHER_AUTHORITY.APPROVED_PO_FROM_PURCHASE')];
        $this->getPurchaseOrderUpdate($purchase_order_id);
        $count = PurchaseOrderModel::where('business_id', $business_id)->whereNotIn('purchase_status_from_owner', $purchase_status_from_owner)->count();

        return $count;
    }

    public function getPurchaseOrderUpdate($purchase_order_id)
    {
        $updated_data = PurchaseOrderModel::where('purchase_orders_id', $purchase_order_id)->update([
            'purchase_status_from_owner' => config('constants.HIGHER_AUTHORITY.APPROVED_PO_FROM_PURCHASE'),
            'owner_po_action_date' => date('Y-m-d'),
            'finanace_store_receipt_status_id' => config('constants.FINANCE_DEPARTMENT.INVOICE_APPROVED_FROM_HIGHER_AUTHORITY')
        ]);
        return $updated_data;
    }
}
