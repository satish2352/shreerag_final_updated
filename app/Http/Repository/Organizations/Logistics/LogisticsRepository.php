<?php
namespace App\Http\Repository\Organizations\Logistics;
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
    NotificationStatus
    };
use Config;

class LogisticsRepository  {
public function storeLogistics($request)
{
    try {
        // $dataOutput = Logistics::get();
        // $dataOutput = new Logistics();
        // $dataOutput = Logistics::find($request->id);
        // $dataOutput = Logistics::where('id', $request->id)->first();
        // $dataOutput = BusinessApplicationProcesses::where('business_id', $request->business_id)->first();
        $dataOutput = Logistics::where('business_details_id', $request->business_id)->first();
        // dd($dataOutput);
        // die();
        if ($dataOutput) {
            // Update the fields
            $dataOutput->vehicle_type_id = $request->vehicle_type_id;
            $dataOutput->transport_name_id = $request->transport_name_id;
            $dataOutput->truck_no = $request->truck_no;
            $dataOutput->is_approve = '0';
            $dataOutput->is_active = '1';
            $dataOutput->is_deleted = '0';
            $dataOutput->save();

            $business_application = BusinessApplicationProcesses::where('business_details_id', $dataOutput->business_details_id)->first();
         
            if ($business_application) {
                $business_application->logistics_status_id = config('constants.LOGISTICS_DEPARTMENT.LOGISTICS_FILL_COMPLETED_PRODUCTION_FORM_IN_LOGISTICS');
                $business_application->off_canvas_status =19;
                $business_application->save();

                  // $update_data_admin['current_department'] = config('constants.DESIGN_DEPARTMENT.DESIGN_SENT_TO_PROD_DEPT_FIRST_TIME');
            $update_data_admin['off_canvas_status'] = 19;
            $update_data_business['off_canvas_status'] = 19;
            $update_data_admin['is_view'] = '0';
            AdminView::where('business_details_id', $business_application->business_details_id)
                ->update($update_data_admin);
                NotificationStatus::where('business_details_id', $business_application->business_details_id)
                ->update($update_data_business);
                // dd($business_application);
                // die();
                return response()->json(['status' => 'success', 'message' => 'Production status updated successfully.']);
            } else {
                return response()->json(['status' => 'error', 'message' => 'Business application not found.'], 404);
            }
        } else {
            return response()->json(['status' => 'error', 'message' => 'Logistics record not found.'], 404);
        }
    } catch (\Exception $e) {
        return response()->json([
            'msg' => $e->getMessage(),
            'status' => 'error'
        ]);
    }
}
public function sendToFianance($id) {
    try {
       
        $business_application = BusinessApplicationProcesses::where('business_details_id', $id)->first();
        if ($business_application) {
            $business_application->logistics_status_id = config('constants.LOGISTICS_DEPARTMENT.LOGISTICS_SEND_PRODUCTION_REQUEST_TO_FINANCE');
            $business_application->off_canvas_status =20;
            $business_application->save();

            return response()->json(['status' => 'success', 'message' => 'Production status updated successfully.']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Business application not found.'], 404);
        }
    } catch (\Exception $e) {
        return response()->json(['status' => 'error', 'message' => 'An error occurred: ' . $e->getMessage()], 500);
    }
}
}