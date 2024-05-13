<?php
namespace App\Http\Repository\Organizations\Business;
use Illuminate\Database\QueryException;
use DB;
use Illuminate\Support\Carbon;
use App\Models\ {
Business,
DesignModel,
BusinessApplicationProcesses,
Vendors,
};
use Config;

class VendorRepository  {


    public function getAll(){
        try {
            $data_output= Vendors::get();
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }


// public function addAll($request)
// {
//     try {
//         $dataOutput = new Business();
//         $dataOutput->title = $request->title;
//         $dataOutput->descriptions = $request->descriptions;
//         $dataOutput->remarks = $request->remarks;
      
//         $dataOutput->save();

//         return [
//             'msg' => 'Data Added Successfully',
//             'status' => 'success'
//         ];

//     } catch (\Exception $e) {
//         return [
//             'msg' => $e->getMessage(),
//             'status' => 'error'
//         ];
//     }
// }
    
public function addAll($request)
{
    // dd($request);
    try {
        $vendor_data = new Vendors();
        $vendor_data->vendor_name = $request->vendor_name;
        $vendor_data->vendor_email = $request->vendor_email;
        $vendor_data->contact_no = $request->contact_no;
        $vendor_data->gst_no = $request->gst_no;
        $vendor_data->quote_no = $request->quote_no;
        $vendor_data->payment_terms = $request->payment_terms;
        $vendor_data->vendor_address = $request->payment_terms;
		// $project_data->is_active = isset($request['is_active']) ? true : false;
        $vendor_data->save();
// dd($vendor_data->save());
        // $design_data = new DesignModel();
        // $design_data->id=$vendor_data->id;
        // $design_data->design_image='';
        // $design_data->bom_image='';
        // $design_data->save();


        // $business_application = new BusinessApplicationProcesses();
        // $business_application->business_id =$business_data->id;
        // $business_application->business_status_id =config('constants.HIGHER_AUTHORITY.NEW_REQUIREMENTS_SENT_TO_DESIGN_DEPARTMENT');
        // $business_application->design_id =$design_data->id;
        // $business_application->design_status_id =config('constants.DESIGN_DEPARTMENT.LIST_NEW_REQUIREMENTS_RECEIVED_FOR_DESIGN');
        // $business_application->production_id ='0';
        // $business_application->production_status_id ='0';
        // $business_application->save();

        return [
            'msg' => 'This business send to Design Department Successfully',
            'status' => 'success'
        ];

    } catch (\Exception $e) {
        // dd($e);
        return [
            'msg' => $e->getMessage(),
            'status' => 'error'
        ];
    }
}

    public function getById($id){
    try {
            $dataOutputByid = Vendors::find($id);
            // dd($dataOutputByid);
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

        public function updateAll($request){
        try { 
            $vendor_data = Vendors::find($request->id);
            // dd($vendor_data);

            if (!$vendor_data) {
                return [
                    'msg' => 'Update Data not found.',
                    'status' => 'error'
                ];
            }

            $vendor_data->vendor_name = $request->vendor_name;
            $vendor_data->vendor_email = $request->vendor_email;
            $vendor_data->contact_no = $request->contact_no;
            $vendor_data->gst_no = $request->gst_no;
            $vendor_data->quote_no = $request->quote_no;
            $vendor_data->payment_terms = $request->payment_terms;
            $vendor_data->vendor_address = $request->payment_terms;
            

            $vendor_data->save();

            return [
            'msg' => 'This business send to Design Department Successfully',
            'status' => 'success'
        ];
        
        } catch (\Exception $e) {
            return [
                'msg' => 'Failed to Update Data.',
                'status' => 'error',
                'error' => $e->getMessage() // Return the error message for debugging purposes
            ];
        }
    }


    public function deleteById($id){
            try {   
                $deleteDataById = Vendors::find($id);
                
                if ($deleteDataById) {
                    $deleteDataById->delete();
                    return $deleteDataById;
                } else {
                    return null;
                }
            } catch (\Exception $e) {
                return $e;
            }
    }

}