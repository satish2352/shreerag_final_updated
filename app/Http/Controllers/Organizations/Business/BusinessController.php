<?php

namespace App\Http\Controllers\Organizations\Business;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Organizations\Business\BusinessServices;
use Session;
use Validator;
use Config;
use Carbon;
use Illuminate\Validation\Rule;
use App\Models\ {
    Business,AdminView
}
;
use App\Http\Controllers\Organizations\CommanController;

class BusinessController extends Controller
 {

    public function __construct() {
        $this->service = new BusinessServices();
        $this->serviceCommon = new CommanController();
    }

    public function index()
 {
        try {
            $data_output = $this->service->getAll();
            return view( 'organizations.business.business.list-business', compact( 'data_output' ) );
        } catch ( \Exception $e ) {
            return $e;
        }
    }

    public function add() {
        try {
            return view( 'organizations.business.business.add-business' );
        } catch ( \Exception $e ) {
            return $e;
        }
    }

    public function store( Request $request ) {
        $rules = [
            'title' => 'required|string|max:255',
            // 'product_name' => 'required',
            'customer_po_number' => 'required|unique:businesses|string|min:10|max:16',
            // 'quantity' => 'required',
            'po_validity' => 'required',
            // 'hsn_number' => 'required',
            // 'customer_payment_terms' => 'required',
            // 'customer_terms_condition' => 'required',
            'remarks' => 'required',
        ];

        $messages = [
            'title.required' => 'The customer name is required.',
            'title.string' => 'The customer name must be a valid string.',
            'title.max' => 'The customer name must not exceed 255 characters.',
            // 'product_name.required' => 'The product name is required.',
            'customer_po_number.required' => 'The customer po number is required.',
            'customer_po_number.min' => 'The customer po number must be at least 10 characters.',
            'customer_po_number.max' => 'The customer po number must not exceed 16 characters.',
            // 'quantity.required' => 'The customer quantity is required.',
            'po_validity.required' => 'The po validity is required.',
            // 'hsn_number.required' => 'The hsn number is required.',
            // 'customer_payment_terms.required' => 'The customer payment terms is required.',
            // 'customer_terms_condition.required' => 'The customer terms condition is required.',
            'remarks.required' => 'The remarks is required.',
            'customer_po_number.unique' => 'PO number already exist.',
        ];

        try {
            $validation = Validator::make( $request->all(), $rules, $messages );

            if ( $validation->fails() ) {
                return redirect( 'owner/add-business' )
                ->withInput()
                ->withErrors( $validation );
            } else {
                $add_record = $this->service->addAll( $request );

                if ( $add_record ) {
                    $msg = $add_record[ 'msg' ];
                    $status = $add_record[ 'status' ];

                    if ( $status == 'success' ) {
                        return redirect( 'owner/list-forwarded-to-design' )->with( compact( 'msg', 'status' ) );
                    } else {
                        return redirect( 'owner/add-business' )->withInput()->with( compact( 'msg', 'status' ) );
                    }
                }
            }
        } catch ( Exception $e ) {
            return redirect( 'owner/add-business' )->withInput()->with( [ 'msg' => $e->getMessage(), 'status' => 'error' ] );
        }
    }

    public function edit( Request $request ) {
        try {

            $edit_data_id = base64_decode( $request->id );
            // $edit_data_id = $request->id;

            $editData = $this->service->getById( $edit_data_id );
            return view( 'organizations.business.business.edit-business', compact( 'editData' ) );
        } catch ( \Exception $e ) {
            return $e;
        }
    }

    public function update( Request $request )
 {
        $rules = [];
        // Define your validation rules
        $messages = [];
        // Define your custom messages

        try {
            // Validate the request
            $validation = Validator::make( $request->all(), $rules, $messages );
            if ( $validation->fails() ) {
                return redirect()->back()
                ->withInput()
                ->withErrors( $validation );
            } else {
                // Update data
                $update_data = $this->service->updateAll( $request );
                if ( $update_data[ 'status' ] == 'success' ) {
                    return redirect( 'owner/list-business' )->with( 'msg', $update_data[ 'msg' ] )->with( 'status', $update_data[ 'status' ] );
                } else {
                    return redirect()->back()->withInput()->with( 'msg', $update_data[ 'msg' ] )->with( 'status', $update_data[ 'status' ] );
                }
            }
        } catch ( Exception $e ) {
            return redirect()->back()->withInput()->with( [ 'msg' => $e->getMessage(), 'status' => 'error' ] );
        }
    }

    public function destroy( Request $request ) {
        $delete_data_id = base64_decode( $request->id );
        try {
            $delete_record = $this->service->deleteById( $delete_data_id );
            if ( $delete_record ) {
                $msg = $delete_record[ 'msg' ];
                $status = $delete_record[ 'status' ];
                if ( $status == 'success' ) {
                    return redirect( 'owner/list-business' )->with( compact( 'msg', 'status' ) );
                } else {
                    return redirect()->back()
                    ->withInput()
                    ->with( compact( 'msg', 'status' ) );
                }
            }
        } catch ( \Exception $e ) {
            return $e;
        }
    }

    public function destroyAddmore( Request $request ) {
        try {
            $delete_rti = $this->service->deleteByIdAddmore( $request->delete_id );

            if ( $delete_rti ) {
                $msg = $delete_rti[ 'msg' ];
                $status = $delete_rti[ 'status' ];

                $id = base64_encode( $request->delete_id );
                if ( $status == 'success' ) {
                    return redirect( 'owner/edit-business/{id}' )->with( compact( 'msg', 'status' ) );
                    // return redirect()->route( 'purchase.edit-purchase-order', [ 'id' => $id ] )
                    // ->with( compact( 'msg', 'status' ) );
                } else {
                    return redirect()->back()
                    ->withInput()
                    ->with( compact( 'msg', 'status' ) );
                }
            }
        } catch ( \Exception $e ) {
            return $e;
        }
    }

    public function submitFinalPurchaseOrder( $id ) {
        try {
            $data_output = $this->service->getPurchaseOrderBusinessWise( $id );
           
            if ( $data_output->isNotEmpty() ) {
                foreach ( $data_output as $data ) {
                    $business_id = $data->business_details_id;

                    if ( !empty( $business_id ) ) {
                        $update_data[ 'is_view' ] = '1';
                        AdminView::where( 'is_view', '0' )
                        ->where( 'business_details_id', $business_id )
                        ->update( $update_data );
                    }
                }
            } else {
                return view( 'organizations.business.list.list-purchase-order-particular-po', [
                    'data_output' => [],
                    'message' => 'No data found'
                ] );
            }
            return view( 'organizations.business.list.list-purchase-order-particular-po', compact( 'data_output' ) );
        } catch ( \Exception $e ) {
            return $e;
        }
    }

    public function getPurchaseOrderDetails( $purchase_order_id ) {

        try {
            $getOrganizationData = $this->serviceCommon->getAllOrganizationData();
            $getAllRulesAndRegulations = $this->serviceCommon->getAllRulesAndRegulations();

            $data = $this->serviceCommon->getPurchaseOrderDetails( $purchase_order_id );
            // $business_id = $data[ 'purchaseOrder' ]->business_id;
            $business_id = $data[ 'purchaseOrder' ]->business_id;

            $purchaseOrder = $data[ 'purchaseOrder' ];
            $purchaseOrderDetails = $data[ 'purchaseOrderDetails' ];

            return view( 'organizations.business.purchase-order.purchase-order-details', compact( 'purchase_order_id', 'purchaseOrder', 'purchaseOrderDetails', 'getOrganizationData', 'business_id', 'getAllRulesAndRegulations' ) );

        } catch ( \Exception $e ) {
            return $e;
        }
    }

    public function acceptPurchaseOrder( $purchase_order_id, $business_id )
 {
        try {
            $delete = $this->service->acceptPurchaseOrder( $purchase_order_id, $business_id );
            if ( $delete ) {
                $status = 'success';
                $msg = 'Purchase order accepted.';
            } else {
                $status = 'success';
                $msg = 'Purchase order accepted.';
            }

            return redirect( 'owner/list-approved-purchase-orders-owner' )->with( compact( 'msg', 'status' ) );
        } catch ( Exception $e ) {
            return [ 'status' => 'error', 'msg' => $e->getMessage() ];
        }

    }

    public function rejectedPurchaseOrder( $purchase_order_id, $business_id )
 {
        try {
            $delete = $this->service->rejectedPurchaseOrder( $purchase_order_id, $business_id );
            if ( $delete ) {
                $status = 'success';
                $msg = 'Purchase order accepted.';
            } else {
                $status = 'success';
                $msg = 'Purchase order accepted.';
            }

            return redirect( 'owner/list-rejected-purchase-orders-owner' )->with( compact( 'msg', 'status' ) );
        } catch ( Exception $e ) {
            return [ 'status' => 'error', 'msg' => $e->getMessage() ];
        }

    }

    public function acceptPurchaseOrderPaymentRelease( $purchase_order_id, $business_id )
 {
        try {
            $delete = $this->service->acceptPurchaseOrderPaymentRelease( $purchase_order_id, $business_id );
            if ( $delete ) {
                $status = 'success';
                $msg = 'Purchase order accepted.';
            } else {
                $status = 'success';
                $msg = 'Purchase order accepted.';
            }

            return redirect( 'owner/list-po-recived-for-approval-payment' )->with( compact( 'msg', 'status' ) );
        } catch ( Exception $e ) {
            return [ 'status' => 'error', 'msg' => $e->getMessage() ];
        }

    }

}