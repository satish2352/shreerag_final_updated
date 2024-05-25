<?php
namespace App\Http\Repository\Organizations\HR\Leaves;
use Illuminate\Database\QueryException;
use DB;
use Illuminate\Support\Carbon;
use App\Models\ {

HREmployee,User,EmployeesModel, Leaves
};
use Config;

class LeavesRepository  {


    public function getAll(){
        try {
            
            $data_output = Leaves::where('employee_id', session()->get('user_id'))
            ->orderBy('updated_at', 'desc')
            ->get();
         
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getAllLeavesRequest(){
        try {

           
            $data_output = Leaves::where('organization_id', session()->get('org_id'))
            ->where('is_approved', 0)
            ->orderBy('updated_at', 'desc')
            ->get();
           
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getAllNotApprovedRequest(){
        try {
            $data_output = Leaves::where('organization_id', session()->get('org_id'))
            ->where('is_approved', 1)
            ->orderBy('updated_at', 'desc')
            ->get();
           
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getAllApprovedRequest(){
        try {
            $data_output = Leaves::where('organization_id', session()->get('org_id'))
            ->where('is_approved', 2)
            ->orderBy('updated_at', 'desc')
            ->get();
           
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }

   

   

    public function addAll($request)
{   
    try {
        $dataOutput = new Leaves();
        $dataOutput->employee_id = $request->session()->get('user_id');
        $dataOutput->leave_start_date = $request->leave_start_date;
        $dataOutput->leave_end_date = $request->leave_end_date;
        $dataOutput->leave_day = $request->leave_day;
        $dataOutput->leave_type = $request->leave_type;
        $dataOutput->reason = $request->reason; 
        $dataOutput->organization_id = $request->session()->get('org_id');
        $dataOutput->save();
        
        $last_insert_id = $dataOutput->id;
        $finalOutput = Leaves::find($last_insert_id);
        $finalOutput->save();

        return [
            'status' => 'success'
        ];
    } catch (\Exception $e) {
        return [
            'msg' => $e->getMessage(),
            'status' => 'error'
        ];
    }
}

    public function getById($id){
    try {
            $dataOutputByid = Leaves::find($id);
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
        $dataOutput = Leaves::find($request->id);

        if (!$dataOutput) {
            return [
                'msg' => 'Update Data not found.',
                'status' => 'error'
            ];
        }
        $dataOutput->employee_id = $request->session()->get('user_id');
        $dataOutput->leave_start_date = $request->leave_start_date;
        $dataOutput->leave_end_date = $request->leave_end_date;
        $dataOutput->leave_day = $request->leave_day;
        $dataOutput->leave_type = $request->leave_type;
        $dataOutput->reason = $request->reason; 
        $dataOutput->organization_id = $request->session()->get('org_id');

        $dataOutput->save();
            $last_insert_id = $dataOutput->id;

            $return_data['last_insert_id'] = $last_insert_id;
     
            return  $return_data;
        
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
            $data_output = Leaves::find($id);
            $data_output->delete();
            
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }

}
