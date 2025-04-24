<?php
namespace App\Http\Repository\Organizations\HR\Leaves;
use Illuminate\Database\QueryException;
use DB;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Carbon;
use App\Models\ {

    HREmployee, User, EmployeesModel, Leaves
}
;
use Config;

class LeavesRepository {

    public function getAll() {
        try {
            $data_output = Leaves::where( 'employee_id', session()->get( 'user_id' ) )
            ->join( 'tbl_leave_management', 'tbl_leaves.leave_type_id', '=', 'tbl_leave_management.id' )
            ->select(
                'tbl_leaves.id',
                'tbl_leaves.other_employee_name',
                'tbl_leaves.leave_start_date',
                'tbl_leaves.employee_id',
                'tbl_leaves.leave_end_date',
                'tbl_leaves.leave_day',
                // 'tbl_leaves.leave_count',
                DB::raw("STR_TO_DATE(tbl_leaves.leave_start_date, '%m/%d/%Y') as leave_start_date"),
                'tbl_leaves.employee_id',
                DB::raw("STR_TO_DATE(tbl_leaves.leave_end_date, '%m/%d/%Y') as leave_end_date"),
                'tbl_leaves.leave_day',
                DB::raw("DATEDIFF(STR_TO_DATE(tbl_leaves.leave_end_date, '%m/%d/%Y'), STR_TO_DATE(tbl_leaves.leave_start_date, '%m/%d/%Y')) + 1 as leave_count"),
                'tbl_leave_management.name as leave_type_name',
                'tbl_leaves.reason',
                'tbl_leaves.is_approved'
            )
            ->orderBy( 'tbl_leaves.updated_at', 'desc' )
            ->get();

            return $data_output;
        } catch ( \Exception $e ) {
            return response()->json( [ 'error' => $e->getMessage() ], 500 );
        }
    }
    public function getById($id)
{
    try {
        $ses_userId = session()->get('user_id');
        $current_year = date('Y');        
        $leave_data = DB::table('tbl_leaves')
            ->join('users', 'tbl_leaves.employee_id', '=', 'users.id')
            ->join('tbl_leave_management', 'tbl_leaves.leave_type_id', '=', 'tbl_leave_management.id')
            ->where('tbl_leaves.id', $id)
            ->where('tbl_leave_management.leave_year', $current_year)
            ->select(
                'tbl_leaves.id',
                'users.u_email',
                'users.f_name',
                'users.m_name',
                'users.l_name',
                'tbl_leaves.leave_start_date',
                'tbl_leaves.other_employee_name',
                'tbl_leaves.employee_id',
                'tbl_leaves.leave_end_date',
                'tbl_leaves.leave_day',
                DB::raw("STR_TO_DATE(tbl_leaves.leave_start_date, '%m/%d/%Y') as leave_start_date"),
                DB::raw("STR_TO_DATE(tbl_leaves.leave_end_date, '%m/%d/%Y') as leave_end_date"),
                DB::raw("DATEDIFF(STR_TO_DATE(tbl_leaves.leave_end_date, '%m/%d/%Y'), STR_TO_DATE(tbl_leaves.leave_start_date, '%m/%d/%Y')) + 1 as leave_count"),
                'tbl_leaves.leave_type_id',
                'tbl_leave_management.name as leave_type_name',
                'tbl_leaves.reason',
                'tbl_leaves.is_approved'
            )
            ->first();
        $leave_summary = DB::table('tbl_leave_management')
            ->leftJoin('tbl_leaves', function($join) use ($ses_userId) {
                $join->on('tbl_leave_management.id', '=', 'tbl_leaves.leave_type_id')
                    ->where('tbl_leaves.employee_id', $ses_userId)
                    ->where('tbl_leaves.is_approved', 2);
            })
            ->where('tbl_leave_management.is_active', 1)
            ->where('tbl_leave_management.is_deleted', 0)
            ->where('tbl_leave_management.leave_year', $current_year)
            ->select(
                'tbl_leave_management.name as leave_type_name',
                'tbl_leave_management.leave_count',
                DB::raw('COALESCE(SUM(tbl_leaves.leave_count), 0) as total_leaves_taken'),
                DB::raw('tbl_leave_management.leave_count - COALESCE(SUM(tbl_leaves.leave_count), 0) as remaining_leaves')
            )
            ->groupBy('tbl_leave_management.id', 'tbl_leave_management.name', 'tbl_leave_management.leave_count')
            ->get();

        // Return the results
        if ($leave_data) {
            return [
                'leave_details' => $leave_data,
                'leave_summary' => $leave_summary
            ];
        } else {
            return null;
        }
    } catch (\Exception $e) {
        return [
            'msg' => $e->getMessage(),
            'status' => 'error'
        ];
    }
}

    public function getAllLeavesRequest() {
        try {

            $data_output = Leaves::join( 'users', 'tbl_leaves.employee_id', '=', 'users.id' )
            ->join('tbl_roles', 'users.role_id', '=', 'tbl_roles.id') 
            ->join( 'tbl_leave_management', 'tbl_leaves.leave_type_id', '=', 'tbl_leave_management.id' )
            ->where( 'tbl_leaves.organization_id', session()->get( 'org_id' ) )
            ->where( 'tbl_leaves.is_approved', 0 )
            ->select(
                'tbl_leaves.id',
                'users.u_email',
                'users.f_name',
                'users.m_name',
                'users.l_name',
                'users.role_id',
                'tbl_roles.role_name',
                'tbl_leaves.leave_start_date',
                'tbl_leaves.other_employee_name',
                'tbl_leaves.employee_id',
                'tbl_leaves.leave_end_date',
                'tbl_leaves.leave_day',
                // 'tbl_leaves.leave_count',
                DB::raw("STR_TO_DATE(tbl_leaves.leave_start_date, '%m/%d/%Y') as leave_start_date"),
                'tbl_leaves.employee_id',
                DB::raw("STR_TO_DATE(tbl_leaves.leave_end_date, '%m/%d/%Y') as leave_end_date"),
                'tbl_leaves.leave_day',
                DB::raw("DATEDIFF(STR_TO_DATE(tbl_leaves.leave_end_date, '%m/%d/%Y'), STR_TO_DATE(tbl_leaves.leave_start_date, '%m/%d/%Y')) + 1 as leave_count"),
                'tbl_leave_management.name as leave_type_name',
                'tbl_leaves.reason',
                'tbl_leaves.is_approved'
            )
            ->orderBy( 'tbl_leaves.updated_at', 'desc' )
            ->get();

            return $data_output;
        } catch ( \Exception $e ) {
            // Log the exception message for debugging
            Log::error( 'Error fetching leave requests: ' . $e->getMessage() );
            return response()->json( [ 'error' => 'Failed to fetch leave requests' ], 500 );
        }
    }

    public function getAllNotApprovedRequest() {
        try {
            $data_output = Leaves::join( 'users', 'tbl_leaves.employee_id', '=', 'users.id' )
            ->join('tbl_roles', 'users.role_id', '=', 'tbl_roles.id') 
            ->join( 'tbl_leave_management', 'tbl_leaves.leave_type_id', '=', 'tbl_leave_management.id' )
            ->where( 'organization_id', session()->get( 'org_id' ) )
            ->where( 'is_approved', 1 )
            ->select(
                'tbl_leaves.id',
                'users.u_email',
                'users.f_name',
                'users.m_name',
                'users.l_name',
                'users.role_id',
                'tbl_roles.role_name',
                'tbl_leaves.leave_start_date',
                'tbl_leaves.other_employee_name',
                'tbl_leaves.employee_id',
                'tbl_leaves.leave_end_date',
                'tbl_leaves.leave_day',
                // 'tbl_leaves.leave_count',
                DB::raw("STR_TO_DATE(tbl_leaves.leave_start_date, '%m/%d/%Y') as leave_start_date"),
                'tbl_leaves.employee_id',
                DB::raw("STR_TO_DATE(tbl_leaves.leave_end_date, '%m/%d/%Y') as leave_end_date"),
                'tbl_leaves.leave_day',
                DB::raw("DATEDIFF(STR_TO_DATE(tbl_leaves.leave_end_date, '%m/%d/%Y'), STR_TO_DATE(tbl_leaves.leave_start_date, '%m/%d/%Y')) + 1 as leave_count"),
                'tbl_leave_management.name as leave_type_name',
                'tbl_leaves.reason',
                'tbl_leaves.is_approved',
            )
            ->orderBy( 'tbl_leaves.updated_at', 'desc' )
            ->get();

            return $data_output;
        } catch ( \Exception $e ) {
            return $e;
        }
    }

    public function getAllApprovedRequest() {
        try {
            $data_output = Leaves::join( 'users', 'tbl_leaves.employee_id', '=', 'users.id' )
            ->join('tbl_roles', 'users.role_id', '=', 'tbl_roles.id') 
            ->join( 'tbl_leave_management', 'tbl_leaves.leave_type_id', '=', 'tbl_leave_management.id' )
            ->where( 'organization_id', session()->get( 'org_id' ) )
            ->where( 'is_approved', 2 )
            ->select(
                'tbl_leaves.id',
                'users.u_email',
                'users.f_name',
                'users.m_name',
                'users.l_name',
                'users.role_id',
                'tbl_roles.role_name',
                'tbl_leaves.leave_start_date',
                'tbl_leaves.other_employee_name',
                'tbl_leaves.employee_id',
                'tbl_leaves.leave_end_date',
                'tbl_leaves.leave_day',
                // 'tbl_leaves.leave_count',
                DB::raw("STR_TO_DATE(tbl_leaves.leave_start_date, '%m/%d/%Y') as leave_start_date"),
                'tbl_leaves.employee_id',
                DB::raw("STR_TO_DATE(tbl_leaves.leave_end_date, '%m/%d/%Y') as leave_end_date"),
                'tbl_leaves.leave_day',
                DB::raw("DATEDIFF(STR_TO_DATE(tbl_leaves.leave_end_date, '%m/%d/%Y'), STR_TO_DATE(tbl_leaves.leave_start_date, '%m/%d/%Y')) + 1 as leave_count"),
                'tbl_leave_management.name as leave_type_name',
                'tbl_leaves.reason',
                'tbl_leaves.is_approved',
            )
            ->orderBy( 'tbl_leaves.updated_at', 'desc' )
            ->get();

            return $data_output;
        } catch ( \Exception $e ) {
            return $e;
        }
    }

    public function addAll( $request )
 {

        try {
            $dataOutput = new Leaves();
            $dataOutput->employee_id = $request->session()->get( 'user_id' );
            $dataOutput->other_employee_name = $request->other_employee_name;
            $dataOutput->leave_start_date = $request->leave_start_date;
            $dataOutput->leave_end_date = $request->leave_end_date;
            $dataOutput->leave_day = $request->leave_day;
            $dataOutput->leave_type_id = $request->leave_type_id;
            $dataOutput->reason = $request->reason;

            $dataOutput->organization_id = $request->session()->get( 'org_id' );

            // Calculate leave days count
            $leaveStartDate = Carbon::parse( $request->leave_start_date );
            $leaveEndDate = Carbon::parse( $request->leave_end_date );

            if ( $request->leave_count == 'full_day' ) {
                // For full day leave, count the days including the start and end date
                $leaveDaysCount = $leaveEndDate->diffInDays( $leaveStartDate ) + 1;

            } else {
                // For half-day leave, count the days between start and end date
                $leaveDaysCount = $leaveEndDate->diffInDays( $leaveStartDate ) ;
            }

            $dataOutput->leave_count = $leaveDaysCount;

            $dataOutput->save();

            $last_insert_id = $dataOutput->id;
            $finalOutput = Leaves::find( $last_insert_id );
            $finalOutput->save();
            $update_data_admin[ 'notification_read_status' ] = '0';
            Leaves::where( 'employee_id', $dataOutput->employee_id )
            ->update( $update_data_admin );
            return [
                'status' => 'success'
            ];
        } catch ( \Exception $e ) {
            return [
                'msg' => $e->getMessage(),
                'status' => 'error'
            ];
        }
    }
    public function updateAll( $request )
 {
        try {
            $dataOutput = Leaves::find( $request->id );

            if ( !$dataOutput ) {
                return [
                    'msg' => 'Update Data not found.',
                    'status' => 'error'
                ];
            }
            $dataOutput->employee_id = $request->session()->get( 'user_id' );
            $dataOutput->other_employee_name = $request->other_employee_name;
            $dataOutput->leave_start_date = $request->leave_start_date;
            $dataOutput->leave_end_date = $request->leave_end_date;
            $dataOutput->leave_day = $request->leave_day;
            $dataOutput->leave_type_id = $request->leave_type_id;
            $dataOutput->reason = $request->reason;

            $dataOutput->organization_id = $request->session()->get( 'org_id' );

            $dataOutput->save();
            $last_insert_id = $dataOutput->id;

            $return_data[ 'last_insert_id' ] = $last_insert_id;

            return  $return_data;

        } catch ( \Exception $e ) {
            return [
                'msg' => 'Failed to Update Data.',
                'status' => 'error',
                'error' => $e->getMessage() // Return the error message for debugging purposes
            ];
        }
    }

    public function deleteById( $id ) {
        try {
            $data_output = Leaves::find( $id );
            $data_output->delete();

            return $data_output;
        } catch ( \Exception $e ) {
            return $e;
        }
    }

}
