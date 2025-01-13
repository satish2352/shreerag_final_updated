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
                ->join('tbl_leave_management', 'tbl_leaves.leave_type_id', '=', 'tbl_leave_management.id')
                ->select(
                    'tbl_leaves.id',
                    'tbl_leaves.leave_start_date',
                    'tbl_leaves.employee_id',
                    'tbl_leaves.leave_end_date',
                    'tbl_leaves.leave_day',
                    'tbl_leaves.leave_count',
                    'tbl_leave_management.name as leave_type_name',
                    'tbl_leaves.reason',
                    'tbl_leaves.is_approved'
                )
                ->orderBy('tbl_leaves.updated_at', 'desc')
                ->get();
    
            return $data_output;
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getById($id){
        try {
            $data_output = Leaves::join('users', 'tbl_leaves.employee_id', '=', 'users.id')
                ->join('tbl_leave_management', 'tbl_leaves.leave_type_id', '=', 'tbl_leave_management.id')
                ->where('tbl_leaves.organization_id', session()->get('org_id'))
                ->where('tbl_leaves.is_approved', 0)
                ->select(
                    'tbl_leaves.id',
                    'users.u_email',
                    'users.f_name',
                    'users.m_name',
                    'users.l_name',
                    'tbl_leaves.leave_start_date',
                    'tbl_leaves.employee_id',
                    'tbl_leaves.leave_end_date',
                    'tbl_leaves.leave_day',
                    'tbl_leaves.leave_count',
                    'tbl_leaves.leave_type_id',
                    'tbl_leave_management.name as leave_type_name',
                    'tbl_leaves.reason',
                    'tbl_leaves.is_approved'
                )
            ->orderBy('tbl_leaves.updated_at', 'desc')
            ->where('tbl_leaves.id', $id)
            ->first();
// dd( $data_output);
// die();
                if ($data_output) {
                    return $data_output;
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

    
    public function getAllLeavesRequest() {
        try {           
            $data_output = Leaves::join('users', 'tbl_leaves.employee_id', '=', 'users.id')
                ->join('tbl_leave_management', 'tbl_leaves.leave_type_id', '=', 'tbl_leave_management.id')
                ->where('tbl_leaves.organization_id', session()->get('org_id'))
                ->where('tbl_leaves.is_approved', 0)
                ->select(
                    'tbl_leaves.id',
                    'users.u_email',
                    'users.f_name',
                    'users.m_name',
                    'users.l_name',
                    'tbl_leaves.leave_start_date',
                    'tbl_leaves.employee_id',
                    'tbl_leaves.leave_end_date',
                    'tbl_leaves.leave_day',
                    'tbl_leaves.leave_count',
                    'tbl_leave_management.name as leave_type_name',
                    'tbl_leaves.reason',
                    'tbl_leaves.is_approved'
                )
                ->orderBy('tbl_leaves.updated_at', 'desc')
                ->get();
            
            return $data_output;
        } catch (\Exception $e) {
            // Log the exception message for debugging
            Log::error('Error fetching leave requests: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch leave requests'], 500);
        }
    }
    
    public function getAllNotApprovedRequest(){
        try {
            $data_output = Leaves::join('users', 'tbl_leaves.employee_id', '=', 'users.id')
            ->join('tbl_leave_management', 'tbl_leaves.leave_type_id', '=', 'tbl_leave_management.id')
            ->where('organization_id', session()->get('org_id'))
            ->where('is_approved', 1)
            ->select(
                'tbl_leaves.id',
                    'users.u_email',
                    'users.f_name',
                    'users.m_name',
                    'users.l_name',
                    'tbl_leaves.leave_start_date',
                    'tbl_leaves.employee_id',
                    'tbl_leaves.leave_end_date',
                    'tbl_leaves.leave_day',
                    'tbl_leaves.leave_count',
                    'tbl_leave_management.name as leave_type_name',
                    'tbl_leaves.reason',
                    'tbl_leaves.is_approved',
                )
            ->orderBy('tbl_leaves.updated_at', 'desc')
            ->get();
           
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getAllApprovedRequest(){
        try {
            $data_output = Leaves::join('users', 'tbl_leaves.employee_id', '=', 'users.id')
            ->join('tbl_leave_management', 'tbl_leaves.leave_type_id', '=', 'tbl_leave_management.id')
            ->where('organization_id', session()->get('org_id'))
            ->where('is_approved', 2)
            ->select(
                'tbl_leaves.id',
                    'users.u_email',
                    'users.f_name',
                    'users.m_name',
                    'users.l_name',
                    'tbl_leaves.leave_start_date',
                    'tbl_leaves.employee_id',
                    'tbl_leaves.leave_end_date',
                    'tbl_leaves.leave_day',
                    'tbl_leaves.leave_count',
                    'tbl_leave_management.name as leave_type_name',
                    'tbl_leaves.reason',
                    'tbl_leaves.is_approved',
                )
            ->orderBy('tbl_leaves.updated_at', 'desc')
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
        $dataOutput->leave_type_id = $request->leave_type_id;
        $dataOutput->reason = $request->reason; 
        $dataOutput->organization_id = $request->session()->get('org_id');

          // Calculate leave days count
          $leaveStartDate = Carbon::parse($request->leave_start_date);
          $leaveEndDate = Carbon::parse($request->leave_end_date);
          
          
        if ($request->leave_count == 'full_day') {
            // For full day leave, count the days including the start and end date
            $leaveDaysCount = $leaveEndDate->diffInDays($leaveStartDate) + 1;
          
        } else {
            // For half-day leave, count the days between start and end date
            $leaveDaysCount = $leaveEndDate->diffInDays($leaveStartDate) ; // Include the start day
          
            // if ($leaveStartDate->format('H:i:s') > '12:00:00') {
            //     // If start time is after 12:00 PM, it's a half-day leave for the first day
            //     $leaveDaysCount -= 0.5;
            // }
            // if ($leaveEndDate->format('H:i:s') < '12:00:00') {
            //     // If end time is before 12:00 PM, it's a half-day leave for the last day
            //     $leaveDaysCount -= 0.5;
            // }

            // Ensure that if there are two half-days, they count as one full day
            // if ($leaveDaysCount < 1) {
            //     $leaveDaysCount = 1;
            // }
        }
          
          $dataOutput->leave_count = $leaveDaysCount;

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

    // public function getById($id){
    // try {
    //         $dataOutputByid = Leaves::find($id);
    //         if ($dataOutputByid) {
    //             return $dataOutputByid;
    //         } else {
    //             return null;
    //         }
    //     } catch (\Exception $e) {
    //         return [
    //             'msg' => $e,
    //             'status' => 'error'
    //         ];
    //     }
    // }

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
        $dataOutput->leave_type_id = $request->leave_type_id;
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
