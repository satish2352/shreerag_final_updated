<?php
namespace App\Http\Repository\Organizations\Business;
use Illuminate\Database\QueryException;
use DB;
use Illuminate\Support\Carbon;
use App\Models\ {
Business,
DesignModel,
BusinessApplicationProcesses
};
use Config;

class BusinessRepository  {


    public function getAll(){
        try {
            $data_output= Business::get();
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
    try {
        $business_data = new Business();
        $business_data->title = $request->title;
        $business_data->descriptions = $request->descriptions;
        $business_data->remarks = $request->remarks;
        $business_data->save();

        $design_data = new DesignModel();
        $design_data->business_id=$business_data->id;
        $design_data->design_image='';
        $design_data->bom_image='';
        $design_data->save();


        $business_application = new BusinessApplicationProcesses();
        $business_application->business_id =$business_data->id;
        $business_application->business_status_id =config('constants.HIGHER_AUTHORITY.NEW_REQUIREMENTS_SENT_TO_DESIGN_DEPARTMENT');
        $business_application->design_id =$design_data->id;
        $business_application->design_status_id =config('constants.DESIGN_DEPARTMENT.LIST_NEW_REQUIREMENTS_RECEIVED_FOR_DESIGN');
        $business_application->production_id ='0';
        $business_application->production_status_id ='0';
        $business_application->save();

        return [
            'msg' => 'This business send to Design Department Successfully',
            'status' => 'success'
        ];

    } catch (\Exception $e) {
        dd($e);
        return [
            'msg' => $e->getMessage(),
            'status' => 'error'
        ];
    }
}

    public function getById($id){
    try {
            $dataOutputByid = Business::find($id);
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
            $dataOutput = Business::find($request->id);
            // dd($dataOutput);

            if (!$dataOutput) {
                return [
                    'msg' => 'Update Data not found.',
                    'status' => 'error'
                ];
            }

            $dataOutput->title = $request->title;
            $dataOutput->descriptions = $request->descriptions;
            $dataOutput->remarks = $request->remarks;
            

            $dataOutput->save();

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
                $deleteDataById = Business::find($id);
                
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