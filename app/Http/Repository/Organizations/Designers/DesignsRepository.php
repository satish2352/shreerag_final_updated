<?php
namespace App\Http\Repository\Organizations\Designers;
use Illuminate\Database\QueryException;
use DB;
use Illuminate\Support\Carbon;
use App\Models\ {
Business, 
DesignModel,
BusinessApplicationProcesses,
ProductionModel,
DesignRevisionForProd
};
use Config;

class DesignsRepository  {
    
    public function getAllNewRequirement(){
        try {

            $array_to_be_check = [config('constants.DESIGN_DEPARTMENT.LIST_NEW_REQUIREMENTS_RECEIVED_FOR_DESIGN')];
            $data_output= DesignModel::leftJoin('businesses', function($join) {
                $join->on('designs.business_id', '=', 'businesses.id');
              })
              ->leftJoin('business_application_processes', function($join) {
                $join->on('designs.business_id', '=', 'business_application_processes.business_id');
              })
              ->whereIn('business_application_processes.design_status_id',$array_to_be_check)
              ->where('businesses.is_active',true)
              ->select(
                  'businesses.id',
                  'businesses.title',
                  'businesses.descriptions',
                  'businesses.remarks',
                  'businesses.is_active',
                  'designs.business_id'

              )->get();
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getAll(){
        try {
            // $data_output= DesignModel::get();

            $array_to_be_check = [config('constants.DESIGN_DEPARTMENT.LIST_NEW_REQUIREMENTS_RECEIVED_FOR_DESIGN'),config('constants.PRODUCTION_DEPARTMENT.LIST_DESIGN_RECEIVED_FOR_PRODUCTION') ];
            $data_output= ProductionModel::leftJoin('businesses', function($join) {
                $join->on('production.business_id', '=', 'businesses.id');
              })
              ->leftJoin('business_application_processes', function($join) {
                $join->on('production.business_id', '=', 'business_application_processes.business_id');
              })
              ->leftJoin('designs', function($join) {
                $join->on('production.business_id', '=', 'designs.business_id');
              })
              ->whereIn('business_application_processes.production_status_id',$array_to_be_check)
              ->where('businesses.is_active',true)
              ->select(
                  'businesses.id',
                  'businesses.title',
                  'businesses.descriptions',
                  'businesses.remarks',
                  'businesses.is_active',
                  'designs.id',
                  'designs.design_image',
                  'designs.bom_image',
                  'designs.business_id'

              )->get();
            //   dd($data_output);
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getById($id){
    try {
            $dataOutputByid = DesignModel::find($id);
            if ($dataOutputByid) {
                return $dataOutputByid;
            } else {
                return null;
            }
        } catch (\Exception $e) {
            return [
                'msg' => $e,
                'status' => 'error'
            ];
        }
    }
    public function updateAll($request)
    {
        try {
            $return_data = array();
            
            $dataOutput = DesignModel::where('business_id', $request->business_id)->first();
            // Check if the record was found
            if (!$dataOutput) {
                return [
                    'msg' => 'Record not found',
                    'status' => 'error',
                ];
            }

            // Store the design and bom image names
            $designImageName = $dataOutput->id . '_' . rand(100000, 999999) . '_design.' . $request->design_image->getClientOriginalExtension();
            $bomImageName = $dataOutput->id . '_' . rand(100000, 999999) . '_bom.' . $request->file('bom_image')->getClientOriginalExtension();
            
            // Update the design image and bom image fields in the DesignModel
            $dataOutput->design_image = $designImageName;
            $dataOutput->bom_image = $bomImageName;
            $dataOutput->save();
    
            // Insert into 
            $production_data = ProductionModel::where('business_id', $request->business_id)->first();
            if ($production_data) {
                
                $production_data->business_id = $dataOutput->business_id;
                $production_data->design_id = $dataOutput->id;
                $production_data->save();

            } else {

                $production_data = new ProductionModel();
                $production_data->business_id = $dataOutput->business_id;
                $production_data->design_id = $dataOutput->id;
                $production_data->save();

            }
    
            // Update BusinessApplicationProcesses if record exists
            $business_application = BusinessApplicationProcesses::where('business_id', $request->business_id)->first();
            if ($business_application) {

                $business_application->business_id = $dataOutput->business_id;
                $business_application->business_status_id = config('constants.HIGHER_AUTHORITY.NEW_REQUIREMENTS_SENT_TO_DESIGN_DEPARTMENT');
                $business_application->design_id = $dataOutput->id;
                $business_application->design_status_id = config('constants.DESIGN_DEPARTMENT.DESIGN_SENT_TO_PROD_DEPT_FIRST_TIME');
                $business_application->production_id = $production_data->id;
                $business_application->production_status_id = config('constants.PRODUCTION_DEPARTMENT.LIST_DESIGN_RECEIVED_FOR_PRODUCTION');
                $business_application->save();


                $designRevisionForProdIDInsert = new DesignRevisionForProd();
                $designRevisionForProdIDInsert->business_id = $dataOutput->business_id;
                $designRevisionForProdIDInsert->design_id = $dataOutput->id;
                $designRevisionForProdIDInsert->production_id = $production_data->id;
                $designRevisionForProdIDInsert->reject_reason_prod = '';
                $designRevisionForProdIDInsert->remark_by_design = '';
                $designRevisionForProdIDInsert->design_image = $designImageName;
                $designRevisionForProdIDInsert->bom_image = $bomImageName;

                $designRevisionForProdIDInsert->save();

            }
    
            $return_data['designImageName'] = $designImageName;
            $return_data['bomImageName'] = $bomImageName;
            $return_data['last_insert_id'] = $dataOutput->business_id;
    
            // Return the data
            return $return_data;
        } catch (\Exception $e) {
            return [
                'msg' => 'Failed to update Report Incident Crowdsourcing.',
                'status' => 'error',
                'error' => $e->getMessage() // Return the error message for debugging purposes
            ];
        }
    }
    

    public function updateReUploadDesign($request)
    {
        try {
            $return_data = array();

            $designRevisionForProd = DesignRevisionForProd::where('id', $request->design_revision_for_prod_id)->orderBy('id','desc')->first();

            if($designRevisionForProd) {

                $designRevisionForProd->remark_by_design = $request->remark_by_design;

                $designImageName = $designRevisionForProd->id . '_' . rand(100000, 999999) . '_re_design.' . $request->design_image->getClientOriginalExtension();
                $bomImageName = $designRevisionForProd->id . '_' . rand(100000, 999999) . '_re_bom.' . $request->bom_image->getClientOriginalExtension();
                
                // Update the design image and bom image fields in the DesignModel
                $designRevisionForProd->design_image = $designImageName;
                $designRevisionForProd->bom_image = $bomImageName;

                $designRevisionForProd->save();

            } 
    
            // Update BusinessApplicationProcesses if record exists
            $business_application = BusinessApplicationProcesses::where('business_id', $designRevisionForProd->business_id)->first();

            if ($business_application) {

                $business_application->business_id = $designRevisionForProd->business_id;
                $business_application->business_status_id = config('constants.HIGHER_AUTHORITY.DESIGN_SENT_TO_PROD_DEPT_REVISED');
                $business_application->design_id = $designRevisionForProd->design_id;
                $business_application->design_status_id = config('constants.DESIGN_DEPARTMENT.DESIGN_SENT_TO_PROD_DEPT_REVISED');
                $business_application->production_id = $designRevisionForProd->production_id;
                $business_application->production_status_id = config('constants.PRODUCTION_DEPARTMENT.LIST_DESIGN_RECIVED_FROM_PRODUCTION_DEPT_REVISED');
                $business_application->save();

            }
    
            $return_data['designImageName'] = $designImageName;
            $return_data['bomImageName'] = $bomImageName;
            $return_data['last_insert_id'] = $designRevisionForProd->business_id;
    
            // Return the data

            return $return_data;
        } catch (\Exception $e) {
            dd($e);
            return [
                'msg' => 'Failed to update Report Incident Crowdsourcing.',
                'status' => 'error',
                'error' => $e->getMessage() // Return the error message for debugging purposes
            ];
        }
    }
   

}