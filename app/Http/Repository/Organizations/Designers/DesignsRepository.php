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
DesignRevisionForProd,
AdminView,
ProductionDetails,
BusinessDetails,
NotificationStatus
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
              ->distinct('businesses.id')
              ->select(
                  'businesses.id',
                  'businesses.customer_po_number',
                //   'businesses.product_name',
                  'businesses.title',
                //   'businesses.descriptions',
                //   'businesses.quantity',
                //   'businesses.descriptions',
                  'businesses.remarks',
                  'businesses.is_active',
                  'designs.business_id',
                  'businesses.updated_at'
                 
              )
              ->orderBy('businesses.updated_at', 'desc')
              ->get();
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
   
    public function getAllNewRequirementBusinessWise($id)
{
    try {
        $decoded_business_id = base64_decode($id);

        // Fetch the design data based on the given id
        $dataOutputNew = DesignModel::where('business_id', $decoded_business_id)->get();

        // Define the array to be checked against
        $array_to_be_check = [config('constants.DESIGN_DEPARTMENT.LIST_NEW_REQUIREMENTS_RECEIVED_FOR_DESIGN')];

        // Perform the query using the provided id
        $data_output = DesignModel::leftJoin('businesses', function($join) {
                $join->on('designs.business_id', '=', 'businesses.id');
            })
            ->leftJoin('businesses_details', function($join) {
                $join->on('designs.business_details_id', '=', 'businesses_details.id');
            })
            ->leftJoin('business_application_processes', function($join) {
                $join->on('designs.business_details_id', '=', 'business_application_processes.business_details_id');
            })
            ->where('business_application_processes.production_status_id', 0) 
            ->where('business_application_processes.production_id', 0)
            ->where('designs.business_id', $decoded_business_id)
            ->whereIn('business_application_processes.design_status_id', $array_to_be_check)
            ->groupBy('businesses_details.product_name', 'designs.business_id', 'designs.business_details_id','businesses_details.description',
                'businesses_details.quantity','businesses_details.business_id','business_application_processes.production_id',
                'business_application_processes.production_status_id','designs.updated_at')
            ->select(
              'businesses_details.business_id',
                'designs.business_id',
                'designs.business_details_id',
                'businesses_details.business_id',
                'businesses_details.product_name',
                'businesses_details.description',
                'businesses_details.quantity',
                'business_application_processes.production_id',
                'business_application_processes.production_status_id',
                'designs.updated_at'
            )->orderBy('designs.updated_at', 'desc')
            ->get();

        return $data_output;
    } catch (\Exception $e) {
        return $e;
    }
}

    public function getAll(){
        try {

            $array_to_be_check = [config('constants.DESIGN_DEPARTMENT.LIST_NEW_REQUIREMENTS_RECEIVED_FOR_DESIGN'),
            config('constants.PRODUCTION_DEPARTMENT.LIST_DESIGN_RECEIVED_FOR_PRODUCTION'),
            config('constants.PRODUCTION_DEPARTMENT.LIST_DESIGN_RECIVED_FROM_PRODUCTION_DEPT_REVISED'),
        
            ];
            $data_output= ProductionModel::leftJoin('businesses', function($join) {
                $join->on('production.business_id', '=', 'businesses.id');
              })
              ->leftJoin('business_application_processes', function($join) {
                $join->on('production.business_id', '=', 'business_application_processes.business_id');
              })
              ->leftJoin('designs', function($join) {
                $join->on('production.business_details_id', '=', 'designs.business_id');
              })
              
              ->whereIn('business_application_processes.production_status_id',$array_to_be_check)
              ->where('businesses.is_active',true)
              ->groupBy('businesses.id', 'businesses.customer_po_number', 'businesses.title', 'businesses.remarks', 'businesses.is_active', 'production.business_id')

              ->select(
                  'businesses.id',
                  'businesses.customer_po_number',
                //   'businesses.product_name',
                //   'businesses.title',
                //   'businesses.descriptions',
                //   'businesses.quantity',
                  'businesses.remarks',
                  'businesses.is_active',
                  'production.business_id',
                  'businesses.updated_at'
                //   'designs.id',
                //   'designs.design_image',
                //   'designs.bom_image',
                //   'designs.business_id'

              )
             
              ->distinct()
              ->groupBy(
                  'businesses.id',
                  'businesses.customer_po_number',
                  'businesses.title',
                  'businesses.remarks',
                  'businesses.is_active',
                  'designs.id',
                  'designs.design_image',
                  'designs.bom_image',
                  'designs.business_id',
                  'businesses.updated_at'
              )
              ->orderBy('businesses.updated_at', 'desc')
              ->get();

         
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
        $edit_id = $request->business_id;


        // $dataOutputNew = DesignModel::where('id', $edit_id)->first();
        $dataOutputNew = DesignModel::where('business_details_id', $edit_id)->first();
        // Check if the record was found
        if (!$dataOutputNew) {
            return [
                'msg' => 'Design not found.',
                'status' => 'error',
            ];
        }

        $businessDetails = BusinessDetails::where('id', $dataOutputNew->business_details_id)->first();
        if (!$businessDetails) {
            return [
                'msg' => 'Business details not found.',
                'status' => 'error',
            ];
        }

        $productName = $businessDetails->product_name;
        $designImageName = $dataOutputNew->design_image;
        $bomImageName = $dataOutputNew->bom_image;

        // Handle design image upload
        if ($request->hasFile('design_image')) {
            $formattedProductName = str_replace(' ', '_', $productName);
            // dd($formattedProductName);
            // die();
            $designImageName = $dataOutputNew->id . '_'. $formattedProductName .'_'. rand(100000, 999999) . '.' . $request->file('design_image')->getClientOriginalExtension();
            $dataOutputNew->design_image = $designImageName;
        }

        // Handle BOM image upload
        if ($request->hasFile('bom_image')) {
            $formattedProductName = str_replace(' ', '_', $productName);
            $bomImageName = $dataOutputNew->id . '_'. $formattedProductName .'_'. rand(100000, 999999) . '.' . $request->file('bom_image')->getClientOriginalExtension();
            $dataOutputNew->bom_image = $bomImageName;
        }

        $dataOutputNew->save();

        $production_data = ProductionModel::firstOrNew(['design_id' => $dataOutputNew->id]);

        $production_data->business_id = $dataOutputNew->business_id;
        $production_data->business_details_id = $dataOutputNew->business_details_id;
        $production_data->design_id = $dataOutputNew->id;
        $production_data->save();

        // Handle ProductionModel update/creation for each design detail
        $production_data_details = ProductionDetails::where('design_id', $dataOutputNew->id)->first();
        if (!$production_data_details) {
            $production_data_details = new ProductionDetails();
        }

        $production_data_details->business_id = $dataOutputNew->business_id;
        $production_data_details->design_id = $dataOutputNew->id;
        $production_data_details->business_details_id = $dataOutputNew->id;
        $production_data_details->production_id = $production_data->id;
        $production_data_details->part_item_id = NULL;
        $production_data_details->quantity = NULL;
        $production_data_details->unit = NULL;
        $production_data_details->save();

        // Store design and production IDs
        $designIds[] = $dataOutputNew->id;
        $productionIds[] = $production_data->id;
        $productionIdsDetails[] = $production_data_details->id;

        $designRevisionForProdIDInsert = new DesignRevisionForProd();
        $designRevisionForProdIDInsert->business_id = $dataOutputNew->business_id;
        $designRevisionForProdIDInsert->business_details_id = $dataOutputNew->business_details_id;
        $designRevisionForProdIDInsert->design_id = $dataOutputNew->id;
        $designRevisionForProdIDInsert->production_id = $production_data->id;
        $designRevisionForProdIDInsert->reject_reason_prod = '';
        $designRevisionForProdIDInsert->remark_by_design = '';
        $designRevisionForProdIDInsert->design_image = $designImageName ?? null;
        $designRevisionForProdIDInsert->bom_image = $bomImageName ?? null;
        $designRevisionForProdIDInsert->save();

        // Update BusinessApplicationProcesses if record exists
        $business_applications = BusinessApplicationProcesses::where('design_id', $dataOutputNew->id)->get();

        foreach ($business_applications as $business_application) {
            $business_application->business_status_id = config('constants.HIGHER_AUTHORITY.NEW_REQUIREMENTS_SENT_TO_DESIGN_DEPARTMENT');
            $business_application->design_id = $designIds[0] ?? null; // Use first element if available
            $business_application->design_status_id = config('constants.DESIGN_DEPARTMENT.DESIGN_SENT_TO_PROD_DEPT_FIRST_TIME');
            $business_application->production_id = $productionIds[0] ?? null; // Use first element if available
            $business_application->production_status_id = config('constants.PRODUCTION_DEPARTMENT.LIST_DESIGN_RECEIVED_FOR_PRODUCTION');
            $business_application->	off_canvas_status = 12;

            $business_application->save();
        }

        // $update_data_admin['current_department'] = config('constants.DESIGN_DEPARTMENT.DESIGN_SENT_TO_PROD_DEPT_FIRST_TIME');
        $update_data_admin['off_canvas_status'] = 12;
        $update_data_business['off_canvas_status'] = 12;
        $update_data_admin['is_view'] = '0';
        AdminView::where('business_details_id', $dataOutputNew->business_details_id)
                ->update($update_data_admin);
                NotificationStatus::where('business_details_id', $dataOutputNew->business_details_id)
                ->update($update_data_business);

        $last_insert_id = $dataOutputNew->id; 

        $return_data['last_insert_id'] = $last_insert_id;
        $return_data['design_image'] = $designImageName;
        $return_data['bom_image'] = $bomImageName;
        $return_data['product_name'] = $productName;
        return $return_data;

    } catch (\Exception $e) {
        return [
            'msg' => 'Failed to update design.',
            'status' => 'error',
            'error' => $e->getMessage()
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
            $business_application = BusinessApplicationProcesses::where('business_details_id', $designRevisionForProd->business_details_id)->first();

            if ($business_application) {

                // $business_application->business_id = $designRevisionForProd->business_id;
                $business_application->business_status_id = config('constants.HIGHER_AUTHORITY.DESIGN_SENT_TO_PROD_DEPT_REVISED');
                // $business_application->design_id = $designRevisionForProd->design_id;
                $business_application->design_status_id = config('constants.DESIGN_DEPARTMENT.DESIGN_SENT_TO_PROD_DEPT_REVISED');
                // $business_application->production_id = $designRevisionForProd->production_id;
                $business_application->production_status_id = config('constants.PRODUCTION_DEPARTMENT.LIST_DESIGN_RECIVED_FROM_PRODUCTION_DEPT_REVISED');
                $business_application->	off_canvas_status = 14;
                $business_application->save();

                  // $update_data_admin['current_department'] = config('constants.DESIGN_DEPARTMENT.DESIGN_SENT_TO_PROD_DEPT_FIRST_TIME');
        $update_data_admin['off_canvas_status'] = 14;
        $update_data_business['off_canvas_status'] = 14;
        $update_data_admin['is_view'] = '0';
        AdminView::where('business_details_id', $business_application->business_details_id)
                ->update($update_data_admin);
                NotificationStatus::where('business_details_id', $business_application->business_details_id)
                ->update($update_data_business);
            }
    
            $return_data['designImageName'] = $designImageName;
            $return_data['bomImageName'] = $bomImageName;
            $return_data['last_insert_id'] = $designRevisionForProd->business_id;
    
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
   

}