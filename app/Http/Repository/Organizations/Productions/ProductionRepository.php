<?php
namespace App\Http\Repository\Organizations\Productions;
use Illuminate\Database\QueryException;
use DB;
use Illuminate\Support\Carbon;
use App\Models\ {
Business, 
DesignModel,
BusinessApplicationProcesses,
ProductionModel,
DesignRevisionForProd,
Logistics,
Dispatch
};
use Config;

class ProductionRepository  {
    



    public function acceptdesign($id){
        try {
            
            $business_application = BusinessApplicationProcesses::where('business_id', $id)->first();
          

            if ($business_application) {
                // $business_application->business_id = $id;
                $business_application->business_status_id = config('constants.HIGHER_AUTHORITY.NEW_REQUIREMENTS_SENT_TO_DESIGN_DEPARTMENT');
                // $business_application->design_id = $dataOutput->id;
                $business_application->design_status_id = config('constants.DESIGN_DEPARTMENT.ACCEPTED_DESIGN_BY_PRODUCTION');
                // $business_application->production_id = $production_data->id;
                $business_application->production_status_id = config('constants.PRODUCTION_DEPARTMENT.ACCEPTED_DESIGN_RECEIVED_FOR_PRODUCTION');
               
                $business_application->save();
            }
            $business = Business::find($id);

            if ($business) {
                $business->is_approved_production = '1';
                $business->save();
            } else {
                return [
                    'msg' => 'Business not found',
                    'status' => 'error',
                ];
            }
    
            $designRevisionForProdID = DesignRevisionForProd::where('production_id', $business_application->production_id)->orderBy('id','desc')->first();

            $dataOutput = DesignModel::where('business_id', $business_application->business_id)->first();
            // Check if the record was found
            if (!$dataOutput) {
                return [
                    'msg' => 'Record not found',
                    'status' => 'error',
                ];
            }
            $dataOutput->design_image = $designRevisionForProdID->design_image;
            $dataOutput->bom_image = $designRevisionForProdID->bom_image;

           
            $dataOutput->save();

        } catch (\Exception $e) {
            return $e;
        }
    } 


    public function rejectdesign($request){
        try {

            $idtoedit = base64_decode($request->business_id);

            $production_data = ProductionModel::where('id', $idtoedit)->first();

            $designRevisionForProdID = DesignRevisionForProd::where('id', $production_data->id)->orderBy('id','desc')->first();
            if($designRevisionForProdID) {

                $designRevisionForProdID->business_id = $production_data->business_id;
                $designRevisionForProdID->design_id = $production_data->design_id;
                $designRevisionForProdID->production_id = $production_data->business_id;
                $designRevisionForProdID->reject_reason_prod = $request->reject_reason_prod;
                $designRevisionForProdID->remark_by_design = '';
                $designRevisionForProdID->save();

            } else {
                $designRevisionForProdIDInsert = new DesignRevisionForProd();
                $designRevisionForProdIDInsert->business_id = $production_data->business_id;
                $designRevisionForProdIDInsert->design_id = $production_data->design_id;
                $designRevisionForProdIDInsert->production_id = $production_data->business_id;
                $designRevisionForProdIDInsert->reject_reason_prod = $request->reject_reason_prod;
                $designRevisionForProdIDInsert->remark_by_design = '';
                $designRevisionForProdIDInsert->save();

            }

            $business_application = BusinessApplicationProcesses::where('business_id', $production_data->business_id)->first();
            
            if ($business_application) {
                $business_application->business_status_id = config('constants.HIGHER_AUTHORITY.LIST_DESIGN_RECIEVED_FROM_PROD_DEPT_FOR_REVISED');
                $business_application->design_id = $production_data->design_id;
                $business_application->design_status_id = config('constants.DESIGN_DEPARTMENT.LIST_DESIGN_RECIEVED_FROM_PROD_DEPT_FOR_REVISED');
                $business_application->production_id =  $production_data->id;
                $business_application->production_status_id = config('constants.PRODUCTION_DEPARTMENT.DESIGN_SENT_TO_DESIGN_DEPT_FOR_REVISED');
                $business_application->save();
            }

        } catch (\Exception $e) {
            return $e;
        }
    } 

    // public function acceptProductionCompleted($id) {
    //     try {
           
    //         // Fetch the business application process record for the given business ID
    //         $business_application = BusinessApplicationProcesses::where('business_id', $id)->first();
    //         // dd($business_application);
    //         // die();
    //         // Check if the record exists
    //         if ($business_application) {
    //             $business_application->production_status_id = config('constants.PRODUCTION_DEPARTMENT.ACTUAL_WORK_COMPLETED_FROM_PRODUCTION_ACCORDING_TO_DESIGN');
    //             $business_application->save();
    
    //             return response()->json(['status' => 'success', 'message' => 'Production status updated successfully.']);
    //         } else {
    //             // Return an error response if the record does not exist
    //             return response()->json(['status' => 'error', 'message' => 'Business application not found.'], 404);
    //         }
    
    //     } catch (\Exception $e) {
    //         // Return an error response if an exception occurs
    //         return response()->json(['status' => 'error', 'message' => 'An error occurred: ' . $e->getMessage()], 500);
    //     }
    // }
    
    public function acceptProductionCompleted($id) {
        try {
            // Fetch the business application process record for the given business ID
            $business_application = BusinessApplicationProcesses::where('business_id', $id)->first();
            if ($business_application) {
                $business_application->production_status_id = config('constants.PRODUCTION_DEPARTMENT.ACTUAL_WORK_COMPLETED_FROM_PRODUCTION_ACCORDING_TO_DESIGN');
                $business_application->save();
    
                $dataOutput = Logistics::where('business_id', $id)->first();
                if (!$dataOutput) {
                    $dataOutput = new Logistics;
                }
    
                $dataOutput->business_id = $id;
                $dataOutput->business_application_processes_id = $business_application->id;
                // $dataOutput->quantity = $request->input('quantity');
                $dataOutput->is_approve = '0';
                $dataOutput->is_active = '1';
                $dataOutput->is_deleted = '0';
                $dataOutput->save();

                $dataOutput = Dispatch::where('business_id', $id)->first();
                if (!$dataOutput) {
                    $dataOutput = new Dispatch;
                }
    
                $dataOutput->business_id = $id;
                $dataOutput->business_application_processes_id = $business_application->id;
                // $dataOutput->quantity = $request->input('quantity');
                $dataOutput->is_approve = '0';
                $dataOutput->is_active = '1';
                $dataOutput->is_deleted = '0';
                $dataOutput->save();
    
                return response()->json(['status' => 'success', 'message' => 'Production status updated successfully.']);
            } else {
                // Return an error response if the record does not exist
                return response()->json(['status' => 'error', 'message' => 'Business application not found.'], 404);
            }
        } catch (\Exception $e) {
            // Return an error response if an exception occurs
            return response()->json(['status' => 'error', 'message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }
    
}