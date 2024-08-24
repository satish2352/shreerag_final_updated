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
    Dispatch
    };
use Config;

class DispatchRepository  {
public function storeDispatch($request)
{
    try {
        // $dataOutput = Dispatch::first();

     
        $dataOutput = Dispatch::where('business_details_id', $request->business_details_id)->first();
    

        if ($dataOutput) {
            // Update the fields
            $dataOutput->outdoor_no = $request->outdoor_no;
            $dataOutput->gate_entry = $request->gate_entry;
            $dataOutput->is_approve = '0';
            $dataOutput->is_active = '1';
            $dataOutput->is_deleted = '0';
            if (isset($request['remark'])) {
                $dataOutput->remark = $request['remark'];
            } 
            $dataOutput->save();
       
            $business_application = BusinessApplicationProcesses::where('business_details_id', $dataOutput->business_details_id)->first();
            
            if ($business_application) {
                $business_application->dispatch_status_id = config('constants.DISPATCH_DEPARTMENT.DISPATCH_DEPARTMENT_MARKED_DISPATCH_COMPLETED');
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

}