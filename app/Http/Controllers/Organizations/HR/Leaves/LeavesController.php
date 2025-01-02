<?php

namespace App\Http\Controllers\Organizations\HR\Leaves;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Organizations\HR\Leaves\LeavesServices;
use Session;
use Validator;
use Config;
use Carbon;
use App\Models\DepartmentsModel;
use App\Models\
{
    RolesModel,EmployeesModel, Leaves,
    LeaveManagement, FinancialYearLeaveRecord
};

class LeavesController extends Controller
{ 
    public function __construct(){
        $this->service = new LeavesServices();
    }



    public function index(){
        try {
            $getOutput = $this->service->getAll();
            
            return view('organizations.hr.leaves.list-leaves', compact('getOutput'));
        } catch (\Exception $e) {
            return $e;
        }
    }  
    public function getAllLeavesRequest(){
        try {
            $getOutput = $this->service->getAllLeavesRequest();
          
            return view('organizations.hr.leaves.list-leaves-accepted', compact('getOutput'));
        } catch (\Exception $e) {
            return $e;
        }
    } 
    public function show(Request $request){
        try {
            $data_id = base64_decode($request->id);
          
            $user_detail = $this->service->getById($data_id);
  
            return view('organizations.hr.leaves.show-leaves', compact('user_detail'));
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getAllNotApprovedRequest(){
        try {
            $getOutput = $this->service->getAllNotApprovedRequest();
            
            return view('organizations.hr.leaves.list-leaves-not-approved', compact('getOutput'));
        } catch (\Exception $e) {
            return $e;
        }
    } 
    public function getAllApprovedRequest(){
        try {
            $getOutput = $this->service->getAllApprovedRequest();
            
            return view('organizations.hr.leaves.list-leaves-approved', compact('getOutput'));
        } catch (\Exception $e) {
            return $e;
        }
    } 

   

 
    public function updateOne(Request $request){
        try {
            $active_id = $request->active_id;
        $result = $this->service->updateOne($active_id);
            return redirect('hr/list-leaves-accepted')->with('flash_message', 'Updated!');  
        } catch (\Exception $e) {
            return $e;
        }
    }

    // public function updateLabourStatusApproved(Request $request) {
    //     try {
          
    //         $leaves_id = $request->input('active_id');

           
    //         $action = $request->input('action');
    
    //         $validator = Validator::make($request->all(), [
    //             'active_id' => 'required|exists:tbl_leaves,id',
    //             'action' => 'required|in:approve,notapprove',
    //         ]);
    
    //         if ($validator->fails()) {
    //             return response()->json(['status' => 'false', 'message' => 'Validation failed', 'errors' => $validator->errors()], 422);
    //         }
    
    //         $leaves = Leaves::find($leaves_id);
    
    //         if ($action === 'approve') {
    //             if ($leaves->is_approved === 1) {
    //                 return response()->json(['status' => 'false', 'message' => 'Leaves record is already approved'], 200);
    //             }
    //             $leaves->is_approved = 1;
    //         } else {
    //             $leaves->is_approved = 2; 
    //         }
    
    //         $leaves->save();
    
    //         return redirect('list-leaves-approvedby-hr')->with('status', 'success')->with('msg', 'Leave status updated successfully');
    //     } catch (\Exception $e) {
    //         return response()->json(['status' => 'false', 'message' => 'Update failed', 'error' => $e->getMessage()], 500);
    //     }
    // }


    // public function updateLabourStatusNotApproved(Request $request) {
    //     try {
          
    //         $leaves_id = $request->input('active_id');

           
    //         $action = $request->input('action');
    
    //         $validator = Validator::make($request->all(), [
    //             'active_id' => 'required|exists:tbl_leaves,id',
    //             'action' => 'required|in:approve,notapprove',
    //         ]);
    
    //         if ($validator->fails()) {
    //             return response()->json(['status' => 'false', 'message' => 'Validation failed', 'errors' => $validator->errors()], 422);
    //         }
    
    //         $leaves = Leaves::find($leaves_id);
    
    //         if ($action === 'notapprove') {
    //             if ($leaves->is_approved === 2) {
    //                 return response()->json(['status' => 'false', 'message' => 'Leaves record is already approved'], 200);
    //             }
    //             $leaves->is_approved = 2;
    //         } else {
    //             $leaves->is_approved = 1; 
    //         }
    
    //         $leaves->save();
    
    //         return redirect('list-leaves-not-approvedby-hr')->with('status', 'success')->with('msg', 'Leave status updated successfully');
    //     } catch (\Exception $e) {
    //         return response()->json(['status' => 'false', 'message' => 'Update failed', 'error' => $e->getMessage()], 500);
    //     }
    // }


    public function updateLabourStatus(Request $request) {
        try {
            $leaves_id = $request->input('active_id');
            
            $action = $request->input('action');
          
            $validator = Validator::make($request->all(), [
                'active_id' => 'required|exists:tbl_leaves,id',
                'action' => 'required|in:approve,notapprove',
            ]);
    
            if ($validator->fails()) {
                return response()->json(['status' => 'false', 'message' => 'Validation failed', 'errors' => $validator->errors()], 422);
            }
    
            $leaves = Leaves::find($leaves_id);
           

             $employeeId = $leaves->employee_id;
             $leaveType = $leaves->leave_type_id;
           
            if ($action === 'approve') {
                if ($leaves->is_approved === 0) {
                    $leaves->is_approved = 2;                
                    $financialRecord = FinancialYearLeaveRecord::where('tbl_financial_year_leave_record.user_id', $employeeId)
                    ->where('tbl_financial_year_leave_record.leave_management_id', $leaveType)
                   ->first();

                  
                    if ($financialRecord) {
                        $financialRecord->leave_balance -= $leaves->leave_count;
                        $financialRecord->save();
                    }

                } elseif($leaves->is_approved === 1) {
                    $leaves->is_approved = 2;

                //     $financialRecord = FinancialYearLeaveRecord::where('tbl_financial_year_leave_record.user_id', $employeeId)
                //     ->where('tbl_financial_year_leave_record.leave_type_name', $leaveType)
                //    ->first();
                //     if ($financialRecord) {
                //         $financialRecord->leave_balance -= $leaves->leave_count;
                //         $financialRecord->save();
                //     }

                    // return response()->json(['status' => 'false', 'message' => 'Leaves record is already approved'], 200);
                                
                }
            } elseif ($action === 'notapprove') {
                if ($leaves->is_approved === 0) {
                    $leaves->is_approved = 1; // Update status to not approved
                } elseif($leaves->is_approved === 2) {
                    $leaves->is_approved = 1;
                    // return response()->json(['status' => 'false', 'message' => 'Leaves record is already not approved'], 200);
                }
            }
    
            $leaves->save();
    
            // Redirect based on the action
            if ($action === 'approve') {
                return redirect('hr/list-leaves-approvedby-hr')->with('status', 'success')->with('msg', 'Leave status updated successfully');
            } elseif ($action === 'notapprove') {
                return redirect('hr/list-leaves-not-approvedby-hr')->with('status', 'success')->with('msg', 'Leave status updated successfully');
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'false', 'message' => 'Update failed', 'error' => $e->getMessage()], 500);
        }
    }
    
    // public function updateLabourStatusNotApproved(Request $request) {
    //     try {
          
    //         $leaves_id = $request->input('active_id');

           
    //         $action = $request->input('action');
    
    //         $validator = Validator::make($request->all(), [
    //             'active_id' => 'required|exists:tbl_leaves,id',
    //             'action' => 'required|in:approve,notapprove',
    //         ]);
    
    //         if ($validator->fails()) {
    //             return response()->json(['status' => 'false', 'message' => 'Validation failed', 'errors' => $validator->errors()], 422);
    //         }
    
    //         $leaves = Leaves::find($leaves_id);
    
    //         if ($action === 'notapprove') {
    //             if ($leaves->is_approved === 1) {
    //                 return response()->json(['status' => 'false', 'message' => 'Leaves record is already approved'], 200);
    //             }
    //             $leaves->is_approved = 1;
    //         } else {
    //             $leaves->is_approved = 0; // or whatever status denotes "Not Approved"
    //         }
    
    //         $leaves->save();
    
    //         return redirect('list-leaves-acceptedby-hr')->with('status', 'success')->with('msg', 'Leave status updated successfully');
    //     } catch (\Exception $e) {
    //         return response()->json(['status' => 'false', 'message' => 'Update failed', 'error' => $e->getMessage()], 500);
    //     }
    // }
    
    
    public function add(){
        $leaveManagment = LeaveManagement::where('is_active', true)
        ->select('id','name')
        ->get()
        ->toArray();
            $dept=DepartmentsModel::get();
            // $roles=RolesModel::get();
        return view('organizations.hr.leaves.add-leaves',compact('dept','leaveManagment'));
    }




      public function store(Request $request){

      
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
                  return redirect('hr/add-leaves')
                      ->withInput()
                      ->withErrors($validation);
              } else {
                  $add_record = $this->service->addAll($request);
                
                  if ($add_record) {
                      $msg = $add_record['msg'];
                      $status = $add_record['status'];
  
                      if ($status == 'success') {
                          return redirect('hr/list-leaves')->with(compact('msg', 'status'));
                      } else {
                          return redirect('hr/add-leaves')->withInput()->with(compact('msg', 'status'));
                      }
                  }
              }
          } catch (Exception $e) {
              return redirect('hr/add-leaves')->withInput()->with(['msg' => $e->getMessage(), 'status' => 'error']);
          }
      }


    //   public function checkDates(Request $request)
    //   {
    //       $exists = Leaves::table('leaves')
    //           ->where('leave_start_date', $request->leave_start_date)
    //           ->orWhere('leave_end_date', $request->leave_end_date)
    //           ->exists();
      
    //       return response()->json(['exists' => $exists]);
    //   }
    // public function checkDates(Request $request)
    // {
    //     $request->validate([
    //         'leave_start_date' => 'required|date',
    //         'leave_end_date' => 'required|date|after_or_equal:leave_start_date',
    //     ]);
    
    //     $existingLeave = Leaves::where(function ($query) use ($request) {
    //         $query->where('is_approved', 0)
    //             ->whereBetween('leave_start_date', [$request->leave_start_date, $request->leave_end_date])
    //             ->orWhereBetween('leave_end_date', [$request->leave_start_date, $request->leave_end_date])
    //             ->orWhere(function ($query) use ($request) {
    //                 $query->where('leave_start_date', '<=', $request->leave_start_date)
    //                     ->where('leave_end_date', '>=', $request->leave_end_date);
    //             });
    //     })->first();
    
    //     if ($existingLeave) {
    //         return response()->json([
    //             'message' => 'Leave request overlaps with an existing request and is not approved.',
    //             'leave' => $existingLeave,
    //             'status' => 'overlap'
    //         ], 400);
    //     }
    
    //     return response()->json([
    //         'message' => 'Leave request does not overlap with existing requests.',
    //         'status' => 'not_overlap'
    //     ], 200);
    // }
    
    
    public function checkDates(Request $request)
    {
        $request->validate([
            'leave_start_date' => 'required|date',
            'leave_end_date' => 'required|date|after_or_equal:leave_start_date',
        ]);
    
        $employeeId = $request->session()->get('user_id');
    
        $existingLeave = Leaves::where('employee_id', $employeeId)
            ->where('is_approved', 0)
            ->where(function ($query) use ($request) {
                $query->whereBetween('leave_start_date', [$request->leave_start_date, $request->leave_end_date])
                    ->orWhereBetween('leave_end_date', [$request->leave_start_date, $request->leave_end_date])
                    ->orWhere(function ($query) use ($request) {
                        $query->where('leave_start_date', '<=', $request->leave_start_date)
                            ->where('leave_end_date', '>=', $request->leave_end_date);
                    });
            })->first();
    
        if ($existingLeave) {
            return response()->json([
                'message' => 'Leave request overlaps with an existing request and is not approved.',
                'leave' => $existingLeave,
                'status' => 'overlap'
            ], 400);
        }
    
        return response()->json([
            'message' => 'Leave request does not overlap with existing requests.',
            'status' => 'not_overlap'
        ], 200);
    }
    
  public function edit(Request $request){
    $leaveManagment = LeaveManagement::where('is_active', true)
    ->select('id','name')
    ->get()
    ->toArray();

    $edit_data_id = base64_decode($request->id);
    $editData = $this->service->getById($edit_data_id);
    $dept=DepartmentsModel::get();
    // $roles=RolesModel::get();
    return view('organizations.hr.leaves.edit-leaves', compact('editData','dept','leaveManagment'));
}


        public function update(Request $request){

        

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
                $validation = Validator::make($request->all(),$rules, $messages);
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

        public function destroy(Request $request){
            $delete_data_id = base64_decode($request->id);
            try {
                $delete_record = $this->service->deleteById($delete_data_id);
                if ($delete_record) {
                    $msg = $delete_record['msg'];
                    $status = $delete_record['status'];
                    if ($status == 'success') {
                        return redirect('hr/list-leaves')->with(compact('msg', 'status'));
                    } else {
                        return redirect()->back()
                            ->withInput()
                            ->with(compact('msg', 'status'));
                    }
                }
            } catch (\Exception $e) {
                return $e;
            }
        } 
}
