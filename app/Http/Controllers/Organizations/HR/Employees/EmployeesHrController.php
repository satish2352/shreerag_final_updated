<?php

namespace App\Http\Controllers\Organizations\HR\Employees;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Organizations\HR\Employees\EmployeesHrServices;
use Session;
use Validator;
use Config;
use Carbon;
use App\Models\DepartmentsModel;
use App\Models\
{
    RolesModel,EmployeesModel
};

class EmployeesHrController extends Controller
{ 
    public function __construct(){
        $this->service = new EmployeesHrServices();
    }



    public function index(){
        try {
            $getOutput = $this->service->getAll();
            // dd($getOutput);
            return view('organizations.hr.employees.list-employees', compact('getOutput'));
        } catch (\Exception $e) {
            return $e;
        }
    }    


    public function add(){
            $dept=DepartmentsModel::get();
            $roles=RolesModel::get();
        return view('organizations.hr.employees.add-employees',compact('dept','roles'));
    }




      public function store(Request $request){

        $emailExists = EmployeesModel::where('email', $request->email)->exists();

        if ($emailExists) {
            return redirect()->back()->with([
                'msg' => 'Email already exists',
                'status' => 'error'
            ]);
        }
        $rules = [
                'employee_name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'mobile_number' => 'required|string|max:20',
                'address' => 'required|string|max:255',
                'aadhar_number' => 'required|string|max:20', // Add Aadhar Number
                'pancard_number' => 'required|string|max:20', // Add Pan Card Number
                'joining_date' => 'required|date', // Add Joining Date
                'highest_qualification' => 'required|string|max:255', // Add Highest Qualification
                'gender' => 'required|in:male,female,other', // Add Gender
                'image' => 'required|image|mimes:jpeg,png,jpg|max:10240|min:5',
            ];

            $messages = [
                'employee_name.required' => 'Please enter the employee name.',
                'employee_name.string' => 'The employee name must be a valid string.',
                'employee_name.max' => 'The employee name must not exceed 255 characters.',
                
                'email.required' => 'Please enter the email.',
                'email.email' => 'Please enter a valid email address.',
                'email.max' => 'The email must not exceed 255 characters.',
                
                'mobile_number.required' => 'Please enter the mobile number.',
                'mobile_number.string' => 'The mobile number must be a valid string.',
                'mobile_number.max' => 'The mobile number must not exceed 20 characters.',
                
                'address.required' => 'Please enter the address.',
                'address.string' => 'The address must be a valid string.',
                'address.max' => 'The address must not exceed 255 characters.',
                
                'aadhar_number.required' => 'Please enter the Aadhar number.',
                'aadhar_number.string' => 'The Aadhar number must be a valid string.',
                'aadhar_number.max' => 'The Aadhar number must not exceed 20 characters.',
                
                'pancard_number.required' => 'Please enter the Pan Card number.',
                'pancard_number.string' => 'The Pan Card number must be a valid string.',
                'pancard_number.max' => 'The Pan Card number must not exceed 20 characters.',
                
                'joining_date.required' => 'Please enter the joining date.',
                'joining_date.date' => 'The joining date must be a valid date.',
                
                'highest_qualification.required' => 'Please enter the highest qualification.',
                'highest_qualification.string' => 'The highest qualification must be a valid string.',
                'highest_qualification.max' => 'The highest qualification must not exceed 255 characters.',
                
                'gender.required' => 'Please select the gender.',
                'gender.in' => 'Please select a valid gender.',
                
                'image.required' => 'The image is required.',
                'image.image' => 'The image must be a valid image file.',
                'image.mimes' => 'The image must be in JPEG, PNG, JPG format.',
                'image.max' => 'The image size must not exceed 10MB.',
                'image.min' => 'The image size must not be less than 5KB.',
            ];

  
          try {
              $validation = Validator::make($request->all(), $rules, $messages);
              
              if ($validation->fails()) {
                  return redirect('hr-add-employees')
                      ->withInput()
                      ->withErrors($validation);
              } else {
                  $add_record = $this->service->addAll($request);
                
                  if ($add_record) {
                      $msg = $add_record['msg'];
                      $status = $add_record['status'];
  
                      if ($status == 'success') {
                          return redirect('hr-list-employees')->with(compact('msg', 'status'));
                      } else {
                          return redirect('hr-add-employees')->withInput()->with(compact('msg', 'status'));
                      }
                  }
              }
          } catch (Exception $e) {
              return redirect('hr-add-employees')->withInput()->with(['msg' => $e->getMessage(), 'status' => 'error']);
          }
      }




  public function edit(Request $request){
    $edit_data_id = base64_decode($request->id);
    $editData = $this->service->getById($edit_data_id);
    $dept=DepartmentsModel::get();
    $roles=RolesModel::get();
    return view('organizations.hr.employees.edit-employees', compact('editData','dept','roles'));
}


        public function update(Request $request){

        

            $rules = [
                'employee_name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'mobile_number' => 'required|string|max:20',
                'address' => 'required|string|max:255',
                'aadhar_number' => 'required|string|max:20', // Add Aadhar Number
                'pancard_number' => 'required|string|max:20', // Add Pan Card Number
                'joining_date' => 'required|date', // Add Joining Date
                'highest_qualification' => 'required|string|max:255', // Add Highest Qualification
                'gender' => 'required|in:male,female,other', // Add Gender
                // 'image' => 'required|image|mimes:jpeg,png,jpg|max:10240|min:5',
            ];

            $messages = [
                'employee_name.required' => 'Please enter the employee name.',
                'employee_name.string' => 'The employee name must be a valid string.',
                'employee_name.max' => 'The employee name must not exceed 255 characters.',
                
                'email.required' => 'Please enter the email.',
                'email.email' => 'Please enter a valid email address.',
                'email.max' => 'The email must not exceed 255 characters.',
                
                'mobile_number.required' => 'Please enter the mobile number.',
                'mobile_number.string' => 'The mobile number must be a valid string.',
                'mobile_number.max' => 'The mobile number must not exceed 20 characters.',
                
                'address.required' => 'Please enter the address.',
                'address.string' => 'The address must be a valid string.',
                'address.max' => 'The address must not exceed 255 characters.',
                
                'aadhar_number.required' => 'Please enter the Aadhar number.',
                'aadhar_number.string' => 'The Aadhar number must be a valid string.',
                'aadhar_number.max' => 'The Aadhar number must not exceed 20 characters.',
                
                'pancard_number.required' => 'Please enter the Pan Card number.',
                'pancard_number.string' => 'The Pan Card number must be a valid string.',
                'pancard_number.max' => 'The Pan Card number must not exceed 20 characters.',
                
                'joining_date.required' => 'Please enter the joining date.',
                'joining_date.date' => 'The joining date must be a valid date.',
                
                'highest_qualification.required' => 'Please enter the highest qualification.',
                'highest_qualification.string' => 'The highest qualification must be a valid string.',
                'highest_qualification.max' => 'The highest qualification must not exceed 255 characters.',
                
                'gender.required' => 'Please select the gender.',
                'gender.in' => 'Please select a valid gender.',
                
                'image.required' => 'The image is required.',
                'image.image' => 'The image must be a valid image file.',
                'image.mimes' => 'The image must be in JPEG, PNG, JPG format.',
                'image.max' => 'The image size must not exceed 10MB.',
                'image.min' => 'The image size must not be less than 5KB.',
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
                            return redirect('hr-list-employees')->with(compact('msg', 'status'));
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
                // dd($delete_record);
                if ($delete_record) {
                    $msg = $delete_record['msg'];
                    $status = $delete_record['status'];
                    if ($status == 'success') {
                        return redirect('hr-list-employees')->with(compact('msg', 'status'));
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
