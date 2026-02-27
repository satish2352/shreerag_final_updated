<?php

namespace App\Http\Repository\Admin\Dashboard;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\{
    Leaves,
    User,
    Notice,
    LeaveManagement
};

class HRDashboardRepository
{
    public function getCounts()
    {

        $ses_userId = session()->get('user_id');
        $currentYear = date('Y');
        $previousYear = $currentYear - 1;

        $leave_request = Leaves::where(['is_active' => 1, 'is_approved' => 0])->where('is_deleted', 0)->count();
        $accepted_leave_request = Leaves::where(['is_active' => 1, 'is_approved' => 2])->where('is_deleted', 0)->count();
        $rejected__leave_request = Leaves::where(['is_active' => 1, 'is_approved' => 1])->where('is_deleted', 0)->count();
        $total_employee = User::where('is_active', 1)->where('is_deleted', 0)->where('id', '!=', 1)->count();
        $total_leaves_type = LeaveManagement::where('is_active', 1)->where('is_deleted', 0)->count();

        $total_notice = Notice::where('is_active', 1)->where('is_deleted', 0)->count();

        $employee_accepted_leave_request = Leaves::leftJoin('users', function ($join) {
            $join->on('tbl_leaves.employee_id', '=', 'users.id');
        })
            ->where('users.id', $ses_userId)
            ->where('tbl_leaves.is_active', 1)
            ->where('tbl_leaves.is_approved', 2)
            ->where('tbl_leaves.is_deleted', 0)
            ->count();
        $employee_rejected_leave_request = Leaves::leftJoin('users', function ($join) {
            $join->on('tbl_leaves.employee_id', '=', 'users.id');
        })
            ->where('users.id', $ses_userId)
            ->where('tbl_leaves.is_active', 1)
            ->where('tbl_leaves.is_approved', 1)
            ->where('tbl_leaves.is_deleted', 0)
            ->count();
        // $employee_leave_type= LeaveManagement::where('is_active',1)->get();
        $employee_leave_type = LeaveManagement::where('is_active', 1)->where('is_deleted', 0)
            ->select('name', 'leave_count')
            ->get();

        $total_leaves = LeaveManagement::where('is_active', 1)
            ->where('is_deleted', 0)
            ->sum('leave_count');

        $available_leaves = LeaveManagement::where('leave_year', $currentYear)
            ->where('is_active', 1)
            ->where('is_deleted', 0)
            ->sum('leave_count');

        $pending_leaves = DB::table('tbl_leave_management as lm')
            ->leftJoin('tbl_leaves as l', function ($join) use ($ses_userId) {
                $join->on('lm.id', '=', 'l.leave_type_id')
                    ->where('l.employee_id', $ses_userId)
                    ->where('l.is_approved', 2)
                    ->where('l.is_active', 1)
                    ->where('l.is_deleted', 0);
            })
            ->where('lm.is_active', 1)
            ->where('lm.is_deleted', 0)
            // ->where('lm.leave_year', date('Y'))
            ->select(DB::raw('SUM(lm.leave_count - COALESCE(l.leave_count, 0)) as remaining_count'))
            ->first()
            ->remaining_count ?? 0;

        // Previous unused = example logic (last year’s remaining)
        $previous_unused_leaves = DB::table('tbl_leave_management')
            ->where('is_active', 1)
            ->where('is_deleted', 0)
            ->where('leave_year', date('Y') - 1)
            ->sum('leave_count'); // adjust logic if you track remaining from last year



        $employee_leave_request = Leaves::leftJoin('users', function ($join) {
            $join->on('tbl_leaves.employee_id', '=', 'users.id');
        })
            ->where('users.id', $ses_userId)
            ->where('tbl_leaves.is_active', 1)
            ->where('tbl_leaves.is_approved', 0)
            ->where('tbl_leaves.is_deleted', 0)
            ->count();



        // $user_leaves_status = User::crossJoin('tbl_leave_management') 
        // ->leftJoin('tbl_leaves', function($join) use ($ses_userId) {
        //     $join->on('users.id', '=', 'tbl_leaves.employee_id')
        //         ->on('tbl_leave_management.id', '=', 'tbl_leaves.leave_type_id')
        //         ->where('tbl_leaves.is_approved', 2);
        // })
        // ->where('users.id', $ses_userId)
        // ->where('tbl_leave_management.is_active', 1)
        // ->where('tbl_leave_management.is_deleted', 0)
        // ->select(
        //     'tbl_leave_management.name as leave_type_name',
        //     'tbl_leave_management.leave_count',
        //     DB::raw('COALESCE(SUM(tbl_leaves.leave_count), 0) as total_leaves_taken'),
        //     DB::raw('tbl_leave_management.leave_count - COALESCE(SUM(tbl_leaves.leave_count), 0) as remaining_leaves'),
        // )
        // ->groupBy(
        //     'tbl_leave_management.id',
        //     'tbl_leave_management.name',
        //     'tbl_leave_management.leave_count'
        // )
        // ->get();
        $currentYear = date('Y');
        $previousYear = $currentYear - 1;

        /* -------------------------------------------
   PREVIOUS YEAR PENDING LEAVES
--------------------------------------------*/
        $previousYearPending = DB::table('tbl_leave_management')
            ->leftJoin('tbl_leaves', function ($join) use ($ses_userId, $previousYear) {
                $join->on('tbl_leave_management.id', '=', 'tbl_leaves.leave_type_id')
                    ->where('tbl_leaves.employee_id', $ses_userId)
                    ->where('tbl_leaves.is_approved', 2)
                    ->whereYear('tbl_leaves.leave_start_date', $previousYear);
            })
            ->where('tbl_leave_management.leave_year', $previousYear)
            ->select(
                'tbl_leave_management.name',
                'tbl_leave_management.leave_count',
                DB::raw('tbl_leave_management.leave_count - COALESCE(SUM(tbl_leaves.leave_count), 0) AS pending_carry_forward')
            )
            ->groupBy('tbl_leave_management.name', 'tbl_leave_management.leave_count')
            ->get()
            ->keyBy('name');

        /* -------------------------------------------
   CURRENT YEAR LEAVES
--------------------------------------------*/
        $user_leaves_status = DB::table('tbl_leave_management')
            ->leftJoin('tbl_leaves', function ($join) use ($ses_userId) {
                $join->on('tbl_leave_management.id', '=', 'tbl_leaves.leave_type_id')
                    ->where('tbl_leaves.employee_id', $ses_userId)
                    ->where('tbl_leaves.is_approved', 2);
            })
            ->where('tbl_leave_management.leave_year', $currentYear)
            ->where('tbl_leave_management.is_active', 1)
            ->where('tbl_leave_management.is_deleted', 0)
            ->select(
                'tbl_leave_management.id',
                'tbl_leave_management.name as leave_type_name',
                'tbl_leave_management.leave_count as current_year_leave',
                DB::raw('COALESCE(SUM(tbl_leaves.leave_count), 0) AS total_leaves_taken')
            )
            ->groupBy('tbl_leave_management.id', 'tbl_leave_management.name', 'tbl_leave_management.leave_count')
            ->get();

        /* -------------------------------------------
   MERGE CARRY FORWARD + FINAL BALANCE
--------------------------------------------*/
        foreach ($user_leaves_status as $item) {

            $carryForward = $previousYearPending[$item->leave_type_name]->pending_carry_forward ?? 0;

            $item->carry_forward = $carryForward;

            $item->total_available_leaves = $item->current_year_leave + $carryForward;

            $item->remaining_leaves =
                $item->total_available_leaves - $item->total_leaves_taken;
        }

        // $currentYear = date('Y');

        // $user_leaves_status = DB::table('tbl_leave_management')
        //     ->leftJoin('tbl_leaves', function ($join) use ($ses_userId) {
        //         $join->on('tbl_leave_management.id', '=', 'tbl_leaves.leave_type_id')
        //             ->where('tbl_leaves.employee_id', $ses_userId)
        //             ->where('tbl_leaves.is_approved', 2);
        //     })
        //     ->where('tbl_leave_management.leave_year', $currentYear)
        //     ->where('tbl_leave_management.is_active', 1)
        //     ->where('tbl_leave_management.is_deleted', 0)
        //     ->select(
        //         'tbl_leave_management.name as leave_type_name',
        //         'tbl_leave_management.leave_count',
        //         DB::raw('COALESCE(SUM(tbl_leaves.leave_count), 0) as total_leaves_taken'),
        //         DB::raw('tbl_leave_management.leave_count - COALESCE(SUM(tbl_leaves.leave_count), 0) as remaining_leaves')
        //     )
        //     ->groupBy('tbl_leave_management.id', 'tbl_leave_management.name', 'tbl_leave_management.leave_count')
        //     ->get();

        return [


            'hr_counts' => [
                'leave_request' => $leave_request,
                'accepted_leave_request' => $accepted_leave_request,
                'rejected__leave_request' => $rejected__leave_request,
                'total_employee' => $total_employee,
                'total_leaves_type' => $total_leaves_type,
                'total_notice' => $total_notice


            ],
            'employee_counts' => [
                'employee_leave_request' => $employee_leave_request,
                'employee_accepted_leave_request' => $employee_accepted_leave_request,
                'employee_rejected_leave_request' => $employee_rejected_leave_request,
                'user_leaves_status' => $user_leaves_status,
                'total_leaves' => $total_leaves,
                'available_leaves' => $available_leaves,
                'previous_unused_leaves' => $previous_unused_leaves,
                'pending_leaves' => $pending_leaves,
            ]
        ];
    }
}
