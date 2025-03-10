<?php
namespace App\Http\Repository\Organizations\Dispatch;
use Illuminate\Database\QueryException;
use DB;
use Illuminate\Support\Carbon;
use App\Models\ {
    Business,
    BusinessApplicationProcesses,
    ProductionModel,
    DesignRevisionForProd,
    PurchaseOrdersModel,
    Logistics,
    Dispatch,
    AdminView,
    NotificationStatus,
    CustomerProductQuantityTracking
}
;
use Config;

class DispatchRepository {
    public function storeDispatch( $request )
 {
        try {

            $business_application = BusinessApplicationProcesses::where( 'business_details_id', $request->business_details_id )->first();

            $business_application->dispatch_status_id = config( 'constants.DISPATCH_DEPARTMENT.DISPATCH_DEPARTMENT_MARKED_DISPATCH_COMPLETED' );
            $business_application->off_canvas_status = 22;

            $business_application->save();
            $dataOutput = Dispatch::where( 'quantity_tracking_id', $request->id )->first();

            if ( !$dataOutput ) {
                return response()->json( [ 'status' => 'error', 'message' => 'Logistics record for this business does not exist.' ], 404 );
            }

            // $quantity_tracking = CustomerProductQuantityTracking::where( 'id', $id )->first();

            if ( $dataOutput ) {
                // Update the fields
                // $dataOutput->outdoor_no = $request->outdoor_no;
                $dataOutput->gate_entry = $request->gate_entry;
                $dataOutput->is_approve = '0';
                $dataOutput->is_active = '1';
                $dataOutput->is_deleted = '0';
                if ( isset( $request[ 'outdoor_no' ] ) ) {
                    $dataOutput->outdoor_no = $request[ 'outdoor_no' ];
                }

                if ( isset( $request[ 'remark' ] ) ) {
                    $dataOutput->remark = $request[ 'remark' ];
                }

                $dataOutput->save();

                if ( $dataOutput ) {

                    // Track the completed quantity for the given business_details_id
                    //   $quantity_tracking = CustomerProductQuantityTracking::where( 'business_details_id', $business_application->business_details_id )->first();
                    $quantity_tracking = CustomerProductQuantityTracking::where( 'id', $dataOutput->id )->first();
                    $quantity_tracking->quantity_tracking_status = config( 'constants.DISPATCH_DEPARTMENT.SUBMITTED_COMPLETED_QUANLTITY_DISPATCH_DEPT' );
                    $quantity_tracking->save();

                    $update_data_admin[ 'off_canvas_status' ] = 22;
                    $update_data_business[ 'off_canvas_status' ] = 22;
                    $update_data_admin[ 'is_view' ] = '0';
                    $update_data_business[ 'dispatch_status_id' ] = '0';
                    AdminView::where( 'business_details_id', $dataOutput->business_details_id )
                    ->update( $update_data_admin );
                    NotificationStatus::where( 'business_details_id', $dataOutput->business_details_id )
                    ->update( $update_data_business );

                    return response()->json( [ 'status' => 'success', 'message' => 'Production status updated successfully.' ] );
                } else {
                    return response()->json( [ 'status' => 'error', 'message' => 'Business application not found.' ], 404 );
                }
            } else {
                return response()->json( [ 'status' => 'error', 'message' => 'Logistics record not found.' ], 404 );
            }
        } catch ( \Exception $e ) {
            return response()->json( [
                'msg' => $e->getMessage(),
                'status' => 'error'
            ] );
        }
    }

}