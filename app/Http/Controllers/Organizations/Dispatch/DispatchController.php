<?php

namespace App\Http\Controllers\Organizations\Dispatch;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Organizations\Dispatch\DispatchServices;
use App\Http\Services\Organizations\Business\BusinessServices;
use App\Models\ {

    BusinessApplicationProcesses,
    Vendors,
    Logistics
}
;
use Session;
use Validator;
use Config;
use Carbon;

class DispatchController extends Controller
 {

    public function __construct() {
        $this->service = new DispatchServices();
        $this->business_service = new BusinessServices();
    }

    public function addDispatch( $business_id, $business_details_id )
 {
        try {
            $business_id =  base64_decode( $business_id );
            $business_details_id =  base64_decode( $business_details_id );

            $purchase_order_data = Logistics::where( 'tbl_logistics.quantity_tracking_id', $business_id )->where( 'tbl_logistics.business_details_id', $business_details_id )
            ->leftJoin( 'tbl_customer_product_quantity_tracking', 'tbl_logistics.quantity_tracking_id', '=', 'tbl_customer_product_quantity_tracking.id' )
            ->leftJoin( 'businesses', 'tbl_logistics.business_id', '=', 'businesses.id' )
            ->leftJoin( 'businesses_details', 'tbl_logistics.business_details_id', '=', 'businesses_details.id' )
            // ->leftJoin( 'tbl_logistics', 'business_application_processes.business_details_id', '=', 'tbl_logistics.business_details_id' )
            ->leftJoin( 'tbl_transport_name', 'tbl_logistics.transport_name_id', '=', 'tbl_transport_name.id' )
            ->leftJoin( 'tbl_vehicle_type', 'tbl_logistics.vehicle_type_id', '=', 'tbl_vehicle_type.id' )
            ->select(
                'tbl_customer_product_quantity_tracking.id',
                'tbl_customer_product_quantity_tracking.business_id',
                'tbl_customer_product_quantity_tracking.business_details_id',
                'tbl_customer_product_quantity_tracking.completed_quantity',
                'businesses.*',
                'businesses_details.*',
                'tbl_logistics.*',
                'tbl_transport_name.name as transport_name',
                'tbl_vehicle_type.name as vehicle_type'
            )
            ->first();

            $editData = $purchase_order_data;

            return view( 'organizations.dispatch.dispatchdept.add-dispatch', compact( 'editData' ) );
        } catch ( \Exception $e ) {
            return $e;
        }
    }

    public function storeDispatch( Request $request )
 {
        $rules = [
            //  'outdoor_no' => 'required',
            'gate_entry' => 'required',

        ];

        $messages = [
            // 'outdoor_no.required' => 'The outdoor number is required.',
            'gate_entry.required' => 'The gate entry is required.',

        ];

        try {
            $validation = Validator::make( $request->all(), $rules, $messages );

            if ( $validation->fails() ) {
                return redirect( 'dispatchdept/list-dispatch' )
                ->withInput()
                ->withErrors( $validation );
            } else {
                $add_record = $this->service->storeDispatch( $request );

                if ( $add_record ) {
                    $msg = $add_record[ 'msg' ];
                    $status = $add_record[ 'status' ];

                    if ( $status == 'success' ) {
                        return redirect( 'dispatchdept/list-dispatch' )->with( compact( 'msg', 'status' ) );
                    } else {
                        return redirect( 'dispatchdept/list-dispatch' )->withInput()->with( compact( 'msg', 'status' ) );
                    }
                }
            }
        } catch ( Exception $e ) {
            return redirect( 'dispatch/dispatchdept/add-dispatch' )->withInput()->with( [ 'msg' => $e->getMessage(), 'status' => 'error' ] );
        }
    }

}