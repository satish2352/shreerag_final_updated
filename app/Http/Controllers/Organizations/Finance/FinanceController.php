<?php

namespace App\Http\Controllers\Organizations\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Organizations\Finance\FinanceServices;
use Session;
use Validator;
use Config;
use Carbon;
use App\Models\ {
    AdminView,
    NotificationStatus
}
;

class FinanceController extends Controller
 {
    public function __construct()
 {
        $this->service = new FinanceServices();
    }

    public function forwardPurchaseOrderToTheOwnerForSanction( $purchase_orders_id, $business_id )
 {
        try {
            $update_data = $this->service->forwardPurchaseOrderToTheOwnerForSanction( $purchase_orders_id, $business_id );

            if ( is_array( $update_data ) ) {
                return redirect( 'financedept/list-sr-and-gr-genrated-business' )->with( 'success', 'Purchase order forwarded successfully.' );
            } else {
                return redirect( 'financedept/list-sr-and-gr-genrated-business' )->with( 'error', $update_data );
            }
        } catch ( \Exception $e ) {
            return redirect( 'financedept/list-sr-and-gr-genrated-business' )->with( 'error', $e->getMessage() );
        }
    }

    public function forwardedPurchaseOrderPaymentToTheVendor( $purchase_orders_id, $business_id )
 {
        try {
            $update_data = $this->service->forwardedPurchaseOrderPaymentToTheVendor( $purchase_orders_id, $business_id );
            return redirect( 'financedept/list-po-sanction-and-need-to-do-payment-to-vendor' );
        } catch ( \Exception $e ) {
            return $e;
        }
    }

    public function sendToDispatch( $id, $business_details_id ) {
        try {
            $accepted = base64_decode( $id );
            // Pass both `$accepted` and `$business_details_id` to the service method
            $update_data = $this->service->sendToDispatch( $accepted, $business_details_id );
            return redirect( 'financedept/list-send-to-dispatch' );
        } catch ( \Exception $e ) {
            return $e;
        }
    }

}
