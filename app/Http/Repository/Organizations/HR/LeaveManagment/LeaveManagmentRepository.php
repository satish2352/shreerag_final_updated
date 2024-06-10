<?php
namespace App\Http\Repository\Organizations\HR\LeaveManagment;
use Illuminate\Database\QueryException;
use DB;
use Illuminate\Support\Carbon;
use App\Models\ {

HREmployee,User,EmployeesModel, LeaveManagement
};
use Config;

class LeaveManagmentRepository  {


  

    public function getAll() {
        try {
            $data_output = LeaveManagement::where('is_deleted', 0)
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
        $dataOutput = new LeaveManagement();
        $dataOutput->leave_year = $request->leave_year;
        $dataOutput->name = $request->name;
        $dataOutput->leave_count = $request->leave_count;
        $dataOutput->save();
        $last_insert_id = $dataOutput->id;
        $finalOutput = LeaveManagement::find($last_insert_id);
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
            $dataOutputByid = LeaveManagement::find($id);
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
        $dataOutput = LeaveManagement::find($request->id);
      
        if (!$dataOutput) {
            return [
                'msg' => 'Update Data not found.',
                'status' => 'error'
            ];
        }
        $dataOutput->leave_year = $request->leave_year;
        $dataOutput->name = $request->name;
        $dataOutput->leave_count = $request->leave_count;

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


    // public function deleteById($id){
    //     try {
    //         $data_output = LeaveManagment::find($id);
    //         $data_output->delete();
            
    //         return $data_output;
    //     } catch (\Exception $e) {
    //         return $e;
    //     }
    // }
    // public function deleteById($leaveYear){
    //     try {
    //         // Find records based on leave_year
    //         $records = LeaveManagment::where('leave_year', $leaveYear)->get();
           
    //         // Update is_deleted flag to 1 for soft delete
    //         foreach ($records as $record) {
    //             $record->is_deleted = 1;
    //             $record->save();
    //         }
          
    //         return $records;
    //     } catch (\Exception $e) {
    //         return $e->getMessage();
    //     }
    // }
    
    public function deleteByYear($leaveYear)
    {
        try {
            // Find records based on leave_year
            $records = LeaveManagement::where('leave_year', $leaveYear)->get();
    
            // Update is_deleted flag to 1 for soft delete
            foreach ($records as $record) {
                $record->is_deleted = 1;
                $record->save();
            }
    
            return $records;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    
}
