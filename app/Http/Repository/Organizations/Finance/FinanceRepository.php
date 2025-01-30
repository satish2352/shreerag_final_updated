<?php
namespace App\Http\Repository\Organizations\Finance;

use Illuminate\Database\QueryException;
use DB;
use Illuminate\Support\Carbon;
use App\Models\ {
    Business,
    DesignModel,
    BusinessApplicationProcesses,
    ProductionModel,
    DesignRevisionForProd,
    Requisition,
    PurchaseOrderModel,
    AdminView,
    NotificationStatus,
    CustomerProductQuantityTracking,
    GRNModel
}
;
use Config;

class FinanceRepository
 {
    public function forwardPurchaseOrderToTheOwnerForSanction( $purchase_orders_id, $business_id )
 {
        try {
            $purchase_order = PurchaseOrderModel::where( 'purchase_orders_id', $purchase_orders_id )->first();
            $grn = GRNModel::where( 'id', $business_id )->first();
            // Add first() to retrieve the model instance

            if ( $grn && $purchase_order ) {
                // Update the GRN and purchase order statuses
                $grn->grn_status_sanction = config( 'constants.FINANCE_DEPARTMENT.INVOICE_SENT_FOR_BILL_APPROVAL_TO_HIGHER_AUTHORITY_PARTICULAR_GRN_WISE' );
                $purchase_order->purchase_orders_id = $purchase_orders_id;

                // Save the updated GRN and purchase order
                $grn->save();
                $purchase_order->save();

                return [ 'grn' => $grn, 'purchase_order' => $purchase_order ];
            }

            return 'No matching records found.';
        } catch ( \Exception $e ) {
            return $e->getMessage();
        }
    }

    public function forwardedPurchaseOrderPaymentToTheVendor( $purchase_orders_id, $business_id )
 {
        try {
            $purchase_order = PurchaseOrderModel::where( 'purchase_orders_id', $purchase_orders_id )->first();
            $grn = GRNModel::where( 'id', $business_id )->first();
            // Add first() to retrieve the model instance

            if ( $grn && $purchase_order ) {
                // Update the GRN and purchase order statuses
                $grn->grn_status_sanction = config( 'constants.FINANCE_DEPARTMENT.FORWARDED_PURCHASE_ORDER_PAYMENT_TO_THE_VENDOR_GRN_WISE' );
                $purchase_order->purchase_orders_id = $purchase_orders_id;

                // Save the updated GRN and purchase order
                $grn->save();
                $purchase_order->save();

                return [ 'grn' => $grn, 'purchase_order' => $purchase_order ];
            }

            return 'No matching records found.';
        } catch ( \Exception $e ) {
            return $e->getMessage();
        }
    }

    public function sendToDispatch( $id,  $business_details_id ) {
        try {
            $businessDetailsId = base64_decode( $business_details_id );
            $quantity_tracking = CustomerProductQuantityTracking::where( 'id', $id )->first();
            $business_application = BusinessApplicationProcesses::where( 'business_details_id', $businessDetailsId )->first();
            $business_application->dispatch_status_id = config( 'constants.FINANCE_DEPARTMENT.LIST_LOGISTICS_SEND_TO_DISPATCH_DEAPRTMENT' );
            $business_application->off_canvas_status = 21;
            $business_application->save();
            if ( $quantity_tracking ) {
                $quantity_tracking = CustomerProductQuantityTracking::where( 'id', $quantity_tracking->id )->first();
                $quantity_tracking->quantity_tracking_status = config( 'constants.FINANCE_DEPARTMENT.SEND_COMPLETED_QUANLTITY_FROM_FIANANCE_DEPT_TO_DISPATCH_DEPT' );
                $quantity_tracking->fianace_list_status = 'Send_Dispatch';

                $quantity_tracking->save();
                $update_data_admin[ 'off_canvas_status' ] = 21;
                $update_data_business[ 'off_canvas_status' ] = 21;
                $update_data_admin[ 'is_view' ] = '0';
                $update_data_business[ 'fianance_to_dispatch_visible' ] = '0';
                AdminView::where( 'business_details_id', $quantity_tracking->business_details_id )
                ->update( $update_data_admin );
                NotificationStatus::where( 'business_details_id', $quantity_tracking->business_details_id )
                ->update( $update_data_business );

                return response()->json( [ 'status' => 'success', 'message' => 'Production status updated successfully.' ] );
            } else {
                return response()->json( [ 'status' => 'error', 'message' => 'Business application not found.' ], 404 );
            }
        } catch ( \Exception $e ) {
            return response()->json( [ 'status' => 'error', 'message' => 'An error occurred: ' . $e->getMessage() ], 500 );
        }
    }

}