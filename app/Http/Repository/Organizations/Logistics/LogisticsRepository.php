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
    Logistics
    };
use Config;

class LogisticsRepository  {
public function storeLogistics($request)
{
    try {
        $dataOutput = Logistics::first();
        if ($dataOutput) {
            // Update the fields
            $dataOutput->truck_no = $request->truck_no;
            $dataOutput->quantity = $request->quantity;
            $dataOutput->vendor_id = $request->vendor_id;
            $dataOutput->is_approve = '0';
            $dataOutput->is_active = '1';
            $dataOutput->is_deleted = '0';
            $dataOutput->save();

            $business_application = BusinessApplicationProcesses::where('business_id', $dataOutput->business_id)->first();
            
            if ($business_application) {
                $business_application->logistics_status_id = config('constants.LOGISTICS_DEPARTMENT.LOGISTICS_FILL_COMPLETED_PRODUCTION_FORM_IN_LOGISTICS');
                $business_application->save();

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
       
        $business_application = BusinessApplicationProcesses::where('business_id', $id)->first();
        if ($business_application) {
            $business_application->logistics_status_id = config('constants.LOGISTICS_DEPARTMENT.LOGISTICS_SEND_PRODUCTION_REQUEST_TO_FINANCE');
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