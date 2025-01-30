<?php

namespace App\Http\Controllers\Organizations\Purchase;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Organizations\Purchase\AllListServices;
use Session;
use Validator;
use Config;
use Carbon;
use App\Models\ {
    Business,
    BusinessApplicationProcesses,
    AdminView,
    NotificationStatus

}
;

class AllListController extends Controller
 {

    public function __construct() {
        $this->service = new AllListServices();
    }

    public function getAllListMaterialReceivedForPurchase() {

        try {
            $data_output = $this->service->getAllListMaterialReceivedForPurchase();
            if ( $data_output->isNotEmpty() ) {
                foreach ( $data_output as $data ) {
                    $business_details = $data->business_details_id;

                    if ( !empty( $business_details ) ) {
                        $update_data[ 'purchase_is_view' ] = '1';
                        NotificationStatus::where( 'purchase_is_view', '0' )
                        ->where( 'business_details_id', $business_details )
                        ->update( $update_data );
                    }
                }
            } else {
                return view( 'organizations.purchase.list.list-bom-material-recived-for-purchase', [
                    'data_output' => [],
                    'message' => 'No data found for designs received for correction'
                ] );
            }

            return view( 'organizations.purchase.list.list-bom-material-recived-for-purchase', compact( 'data_output' ) );
            // return view( 'organizations.purchase.forms.send-vendor-details-for-purchase', compact( 'data_output' ) );
        } catch ( \Exception $e ) {

            return $e;
        }
    }

    public function getAllListApprovedPurchaseOrder( Request $request ) {
        try {
            $data_output = $this->service->getAllListApprovedPurchaseOrder();
            if ( $data_output instanceof \Illuminate\Support\Collection && $data_output->isNotEmpty() ) {
                foreach ( $data_output as $data ) {
                    $business_id = $data->id;

                    if ( !empty( $business_id ) ) {
                        $update_data[ 'purchase_order_is_view_po' ] = '1';
                        NotificationStatus::where( 'purchase_order_is_view_po', '0' )
                        ->where( 'id', $business_id )
                        ->update( $update_data );
                    }
                }
            } else {
                return view( 'organizations.purchase.list.list-purchase-order-approved-need-to-check', [
                    'data_output' => [],
                    'message' => 'No data found'
                ] );
            }
            return view( 'organizations.purchase.list.list-purchase-order-approved-need-to-check', compact( 'data_output' ) );
        } catch ( \Exception $e ) {
            return $e;
        }
    }

    public function getPurchaseOrderSentToOwnerForApprovalBusinesWise( $id ) {
        try {
            $data_output = $this->service->getPurchaseOrderSentToOwnerForApprovalBusinesWise( $id );

            return view( 'organizations.purchase.list.list-purchase-order-approved-need-to-check-businesswise', compact( 'data_output' ) );
        } catch ( \Exception $e ) {
            return $e;
        }
    }

    public function getAllListPurchaseOrderMailSentToVendor( Request $request ) {
        try {

            $data_output = $this->service->getAllListPurchaseOrderMailSentToVendor();

            return view( 'organizations.purchase.list.list-purchase-order-approved-sent-to-vendor', compact( 'data_output' ) );
        } catch ( \Exception $e ) {
            return $e;
        }
    }

    public function getAllListPurchaseOrderMailSentToVendorBusinessWise( $id ) {
        try {
            $data_output = $this->service->getAllListPurchaseOrderMailSentToVendorBusinessWise( $id );

            return view( 'organizations.purchase.list.list-purchase-order-approved-sent-to-vendor-businesswise', compact( 'data_output' ) );
        } catch ( \Exception $e ) {
            return $e;
        }
    }

    public function getAllListSubmitedPurchaeOrderByVendor( Request $request ) {
        try {

            $data_output = $this->service->getAllListSubmitedPurchaeOrderByVendor();

            if ( $data_output instanceof \Illuminate\Support\Collection && $data_output->isNotEmpty() ) {
                foreach ( $data_output as $data ) {
                    $business_id = $data->id;

                    if ( !empty( $business_id ) ) {
                        $update_data[ 'po_send_to_vendor' ] = '1';
                        NotificationStatus::where( 'po_send_to_vendor', '0' )
                        ->where( 'id', $business_id )
                        ->update( $update_data );
                    }
                }
            } else {
                return view( 'organizations.purchase.list.list-all-po-sent-to-vendor', [
                    'data_output' => [],
                    'message' => 'No data found'
                ] );
            }
            return view( 'organizations.purchase.list.list-all-po-sent-to-vendor', compact( 'data_output' ) );
        } catch ( \Exception $e ) {
            return $e;
        }
    }

    public function getAllListSubmitedPurchaeOrderByVendorBusinessWise( $id ) {
        try {
            $data_output = $this->service->getAllListSubmitedPurchaeOrderByVendorBusinessWise( $id );

            return view( 'organizations.purchase.list.list-all-po-sent-to-vendor-businesswise', compact( 'data_output' ) );
        } catch ( \Exception $e ) {
            return $e;
        }
    }

    public function getAllListPurchaseOrderTowardsOwner( Request $request )
 {
        try {
            $data_output = $this->service->getAllListPurchaseOrderTowardsOwner();

            if ( $data_output instanceof \Illuminate\Support\Collection && $data_output->isNotEmpty() ) {
                foreach ( $data_output as $data ) {
                    $business_details_id = $data->id;
                    if ( !empty( $business_details_id ) ) {
                        $update_data[ 'purchase_is_view' ] = '1';
                        NotificationStatus::where( 'purchase_is_view', '0' )
                        ->where( 'business_details_id', $business_details_id )
                        ->update( $update_data );
                    }
                }
            } else {
                return view( 'organizations.purchase.list.list-purchase-order-need-to-check', [
                    'data_output' => [],
                    'message' => 'No data found for designs received for correction',
                ] );
            }

            return view( 'organizations.purchase.list.list-purchase-order-need-to-check', compact( 'data_output' ) );
        } catch ( \Exception $e ) {
            // Log the error for debugging
            \Log::error( 'Error fetching purchase orders: ' . $e->getMessage() );
            return view( 'organizations.purchase.list.list-purchase-order-need-to-check', [
                'data_output' => [],
                'message' => 'An error occurred while fetching purchase orders.',
            ] );
        }
    }

}