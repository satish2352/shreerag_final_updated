<?php

namespace App\Http\Controllers\Organizations\HR\Leaves;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Organizations\HR\Leaves\LeavesServices;
use App\Http\Controllers\Organizations\CommanController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Models\DepartmentsModel;
use App\Models\{
    Leaves,
    LeaveManagement,
    FinancialYearLeaveRecord
};

class LeavesController extends Controller
{
    protected $service;
    protected $serviceCommon;
    public function __construct()
    {
        $this->service = new LeavesServices();
        $this->serviceCommon = new CommanController();
    }



    public function index()
    {
        try {
            $getOutput = $this->service->getAll();

            return view('organizations.hr.leaves.list-leaves', compact('getOutput'));
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getAllLeavesRequest()
    {
        try {
            $getOutput = $this->service->getAllLeavesRequest();

            if ($getOutput->isNotEmpty()) {
                foreach ($getOutput as $data) {
                    $user_id = $data->id;

                    if (!empty($user_id)) {
                        $update_data['notification_read_status'] = '1';
                        Leaves::where('notification_read_status', '0')
                            ->where('id', $user_id)
                            ->update($update_data);
                    }
                }
            } else {
                return view('organizations.hr.leaves.list-leaves-accepted', [
                    'getOutput' => [],
                    'message' => 'No data found for designs received for correction'
                ]);
            }


            return view('organizations.hr.leaves.list-leaves-accepted', compact('getOutput'));
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function show(Request $request)
    {
        try {
            $data_id = base64_decode($request->id);
            $user_detail = $this->service->getById($data_id);
            $getOrganizationData = $this->serviceCommon->getAllOrganizationData();
            return view('organizations.hr.leaves.show-leaves', compact('user_detail', 'getOrganizationData'));
        } catch (\Exception $e) {
            return $e;
        }
    }


    public function getAllNotApprovedRequest()
    {
        try {
            $getOutput = $this->service->getAllNotApprovedRequest();

            return view('organizations.hr.leaves.list-leaves-not-approved', compact('getOutput'));
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getAllApprovedRequest()
    {
        try {
            $getOutput = $this->service->getAllApprovedRequest();

            return view('organizations.hr.leaves.list-leaves-approved', compact('getOutput'));
        } catch (\Exception $e) {
            return $e;
        }
    }




    public function updateOne(Request $request)
    {
        try {
            $active_id = $request->active_id;
            $result = $this->service->updateOne($active_id);
            return redirect('hr/list-leaves-accepted')->with('flash_message', 'Updated!');
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function updateLabourStatus(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'active_id' => 'required|exists:tbl_leaves,id',
                'action' => 'required|in:approve,notapprove',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed'
                ], 422);
            }

            $leaves_id = $request->active_id;
            $action = $request->action;

            $leaves = Leaves::find($leaves_id);
            $employeeId = $leaves->employee_id;
            $leaveType = $leaves->leave_type_id;

            /* ---------------------- APPROVE LEAVE ---------------------- */
            if ($action === 'approve') {

                if ($leaves->is_approved == 0) {
                    $leaves->is_approved = 2;

                    $financialRecord = FinancialYearLeaveRecord::where('user_id', $employeeId)
                        ->where('leave_management_id', $leaveType)
                        ->first();

                    if ($financialRecord) {
                        $financialRecord->leave_balance -= $leaves->leave_count;
                        $financialRecord->save();
                    }
                } else {
                    $leaves->is_approved = 2;
                }

                $leaves->save();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Leave approved successfully',
                    'redirect' => url('hr/list-leaves-approvedby-hr')
                ]);
            }

            /* ---------------------- REJECT LEAVE ---------------------- */
            if ($action === 'notapprove') {

                if ($leaves->is_approved == 0 || $leaves->is_approved == 2) {
                    $leaves->is_approved = 1;
                }

                $leaves->save();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Leave rejected successfully',
                    'redirect' => url('hr/list-leaves-not-approvedby-hr')
                ]);
            }
        } catch (\Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }
    public function updateLabourStatusRejected(Request $request)
    {
        try {

            // Validation
            $validator = Validator::make($request->all(), [
                'active_id' => 'required|exists:tbl_leaves,id',
                'action' => 'required|in:approve,notapprove',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed'
                ], 422);
            }

            $leaveId = $request->active_id;
            $action = $request->action;

            $leave = Leaves::find($leaveId);
            $employeeId = $leave->employee_id;
            $leaveType = $leave->leave_type_id;

            /* ---------------------- APPROVE LEAVE FROM REJECTED LIST ---------------------- */
            if ($action === 'approve') {

                // Only approve if not already approved
                if ($leave->is_approved != 2) {

                    $leave->is_approved = 2; // 2 = approved

                    // Update leave balance only once
                    $financialRecord = FinancialYearLeaveRecord::where('user_id', $employeeId)
                        ->where('leave_management_id', $leaveType)
                        ->first();

                    if ($financialRecord) {
                        $financialRecord->leave_balance -= $leave->leave_count;

                        if ($financialRecord->leave_balance < 0) {
                            return response()->json([
                                'status' => 'error',
                                'message' => 'Insufficient leave balance for approval'
                            ]);
                        }

                        $financialRecord->save();
                    }
                }

                $leave->save();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Leave approved successfully',
                    'redirect' => url('hr/list-leaves-approvedby-hr')
                ]);
            }

            /* ---------------------- REJECT LEAVE ---------------------- */
            if ($action === 'notapprove') {

                // 1 = rejected
                $leave->is_approved = 1;
                $leave->save();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Leave rejected successfully',
                    'redirect' => url('hr/list-leaves-not-approvedby-hr')
                ]);
            }
        } catch (\Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }


    public function add()
    {
        $currentYear = date('Y');

        $leaveManagment = LeaveManagement::where('is_active', true)
            ->where('is_deleted', 0)
            ->where('leave_year', $currentYear)
            ->select('id', 'name')
            ->get()
            ->toArray();
        $dept = DepartmentsModel::get();
        return view('organizations.hr.leaves.add-leaves', compact('dept', 'leaveManagment'));
    }
    public function store(Request $request)
    {
        $rules = [
            'other_employee_name' => 'required',
            'leave_type_id'       => 'required',
            'leave_day'           => 'required',
            'leave_start_date'    => 'required|date',
            'leave_end_date'      => 'required|date|after_or_equal:leave_start_date',
            'reason'              => 'required'
        ];

        $messages = [
            'other_employee_name.required' => 'Please enter full name.',
            'leave_type_id.required'       => 'Please select leave type.',
            'leave_day.required'           => 'Please select full / half day.',
            'leave_start_date.required'    => 'Please select start date.',
            'leave_end_date.required'      => 'Please select end date.',
            'reason.required'              => 'Please enter reason.'
        ];

        $validation = Validator::make($request->all(), $rules, $messages);

        if ($validation->fails()) {
            return back()->withErrors($validation)->withInput();
        }

        // CALL SERVICE
        $response = $this->service->addAll($request);

        // SUCCESS ⇒ Redirect to list page
        if ($response['status'] == 'success') {
            return redirect()->route('list-leaves')->with([
                'status' => 'success',
                'msg'    => $response['msg']
            ]);
        }

        // ERROR ⇒ Stay on same page
        return back()->with([
            'status' => 'error',
            'msg'    => $response['msg']
        ])->withInput();
    }

    public function checkLeaveBalance(Request $request)
    {
        $leaveTypeId = $request->leave_type_id;
        $employeeId  = session()->get('user_id');
        $currentYear = date('Y');
        $previousYear = $currentYear - 1;

        /* =============================
       1️⃣ GET CURRENT YEAR LEAVES
    ============================= */
        $current = DB::table('tbl_leave_management')
            ->leftJoin('tbl_leaves', function ($join) use ($employeeId) {
                $join->on('tbl_leave_management.id', '=', 'tbl_leaves.leave_type_id')
                    ->where('tbl_leaves.employee_id', $employeeId)
                    ->where('tbl_leaves.is_approved', 2);
            })
            ->where('tbl_leave_management.id', $leaveTypeId)
            ->where('tbl_leave_management.leave_year', $currentYear)
            ->select(
                'tbl_leave_management.leave_count',
                DB::raw('COALESCE(SUM(tbl_leaves.leave_count),0) as taken')
            )
            ->groupBy('tbl_leave_management.leave_count')
            ->first();

        if (!$current) {
            return response()->json(['available' => 0]);
        }

        $currentLeaveCount = $current->leave_count;
        $currentTaken      = $current->taken;
        $currentAvailable  = $currentLeaveCount - $currentTaken;


        /* =============================
       2️⃣ GET PREVIOUS YEAR UNUSED
    ============================= */
        $previous = DB::table('tbl_leave_management')
            ->leftJoin('tbl_leaves', function ($join) use ($employeeId) {
                $join->on('tbl_leave_management.id', '=', 'tbl_leaves.leave_type_id')
                    ->where('tbl_leaves.employee_id', $employeeId)
                    ->where('tbl_leaves.is_approved', 2);
            })
            ->where('tbl_leave_management.name', function ($q) use ($leaveTypeId) {
                $q->select('name')
                    ->from('tbl_leave_management')
                    ->where('id', $leaveTypeId);
            })
            ->where('tbl_leave_management.leave_year', $previousYear)
            ->select(
                'tbl_leave_management.leave_count',
                DB::raw('COALESCE(SUM(tbl_leaves.leave_count),0) as taken')
            )
            ->groupBy('tbl_leave_management.leave_count')
            ->first();

        $previousUnused = 0;

        if ($previous) {
            $previousUnused = $previous->leave_count - $previous->taken;
            if ($previousUnused < 0) $previousUnused = 0;
        }


        /* =============================
       3️⃣ FINAL AVAILABLE LEAVES
    ============================= */
        $finalAvailable = $currentAvailable + $previousUnused;

        return response()->json([
            'available' => $finalAvailable
        ]);
    }



    public function checkDates(Request $request)
    {
        $employeeId = session()->get('user_id');

        $exists = Leaves::where('employee_id', $employeeId)
            ->where(function ($query) use ($request) {

                $query->whereBetween('leave_start_date', [
                    $request->leave_start_date,
                    $request->leave_end_date
                ])
                    ->orWhereBetween('leave_end_date', [
                        $request->leave_start_date,
                        $request->leave_end_date
                    ])
                    ->orWhere(function ($q) use ($request) {
                        $q->where('leave_start_date', '<=', $request->leave_start_date)
                            ->where('leave_end_date', '>=', $request->leave_end_date);
                    });
            })
            ->exists();

        return response()->json([
            'exists' => $exists
        ]);
    }


    public function edit(Request $request)
    {
        $leaveManagment = LeaveManagement::where('is_active', true)->where('is_deleted', 0)
            ->select('id', 'name')
            ->get()
            ->toArray();

        $edit_data_id = base64_decode($request->id);
        $editData = $this->service->getById($edit_data_id);

        $dept = DepartmentsModel::get();
        // $roles=RolesModel::get();
        return view('organizations.hr.leaves.edit-leaves', compact('editData', 'dept', 'leaveManagment'));
    }


    public function update(Request $request)
    {



        $rules = [
            // 'employee_name' => 'required|string|max:255',
            // 'email' => 'required|email|max:255',
            // 'mobile_number' => 'required|string|max:20',
            // 'address' => 'required|string|max:255',
            // 'aadhar_number' => 'required|string|max:20', // Add Aadhar Number
            // 'pancard_number' => 'required|string|max:20', // Add Pan Card Number
            // 'joining_date' => 'required|date', // Add Joining Date
            // 'highest_qualification' => 'required|string|max:255', // Add Highest Qualification
            // 'gender' => 'required|in:male,female,other', // Add Gender
            // 'image' => 'required|image|mimes:jpeg,png,jpg|max:10240|min:5',
        ];

        $messages = [
            // 'employee_name.required' => 'Please enter the employee name.',
            // 'employee_name.string' => 'The employee name must be a valid string.',
            // 'employee_name.max' => 'The employee name must not exceed 255 characters.',

            // 'email.required' => 'Please enter the email.',
            // 'email.email' => 'Please enter a valid email address.',
            // 'email.max' => 'The email must not exceed 255 characters.',

            // 'mobile_number.required' => 'Please enter the mobile number.',
            // 'mobile_number.string' => 'The mobile number must be a valid string.',
            // 'mobile_number.max' => 'The mobile number must not exceed 20 characters.',

            // 'address.required' => 'Please enter the address.',
            // 'address.string' => 'The address must be a valid string.',
            // 'address.max' => 'The address must not exceed 255 characters.',

            // 'aadhar_number.required' => 'Please enter the Aadhar number.',
            // 'aadhar_number.string' => 'The Aadhar number must be a valid string.',
            // 'aadhar_number.max' => 'The Aadhar number must not exceed 20 characters.',

            // 'pancard_number.required' => 'Please enter the Pan Card number.',
            // 'pancard_number.string' => 'The Pan Card number must be a valid string.',
            // 'pancard_number.max' => 'The Pan Card number must not exceed 20 characters.',

            // 'joining_date.required' => 'Please enter the joining date.',
            // 'joining_date.date' => 'The joining date must be a valid date.',

            // 'highest_qualification.required' => 'Please enter the highest qualification.',
            // 'highest_qualification.string' => 'The highest qualification must be a valid string.',
            // 'highest_qualification.max' => 'The highest qualification must not exceed 255 characters.',

            // 'gender.required' => 'Please select the gender.',
            // 'gender.in' => 'Please select a valid gender.',

            // 'image.required' => 'The image is required.',
            // 'image.image' => 'The image must be a valid image file.',
            // 'image.mimes' => 'The image must be in JPEG, PNG, JPG format.',
            // 'image.max' => 'The image size must not exceed 10MB.',
            // 'image.min' => 'The image size must not be less than 5KB.',
        ];

        try {
            $validation = Validator::make($request->all(), $rules, $messages);
            if ($validation->fails()) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors($validation);
            } else {
                $update_data = $this->service->updateAll($request);
                if ($update_data) {
                    $msg = $update_data['msg'];
                    $status = $update_data['status'];
                    if ($status == 'success') {
                        return redirect('hr/list-leaves')->with(compact('msg', 'status'));
                    } else {
                        return redirect()->back()
                            ->withInput()
                            ->with(compact('msg', 'status'));
                    }
                }
            }
        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with(['msg' => $e->getMessage(), 'status' => 'error']);
        }
    }
    public function destroy($id)
    {
        $delete_data_id = base64_decode($id);

        try {
            $delete_record = $this->service->deleteById($delete_data_id);

            return redirect()->route('list-leaves')
                ->with(['msg' => $delete_record['msg'], 'status' => $delete_record['status']]);
        } catch (\Exception $e) {
            return redirect()->back()->with(['msg' => $e->getMessage(), 'status' => 'error']);
        }
    }
}
