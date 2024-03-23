<?php

namespace App\Http\Controllers\Organizations\Employees;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Organizations\Employees\EmployeesServices;
use Session;
use Validator;
use Config;
use Carbon;
use App\Models\DepartmentsModel;
use App\Models\RolesModel;
use App\Models\EmployeesModel;

class EmployeesController extends Controller
{ 
    public function __construct(){
        $this->service = new EmployeesServices();
    }



    public function index(){
        try {
            $getOutput = $this->service->getAll();
            // dd($getOutput);
            return view('organizations.pages.employees.list-employees', compact('getOutput'));
        } catch (\Exception $e) {
            return $e;
        }
    }    


    public function add(){
            $dept=DepartmentsModel::get();
            $roles=RolesModel::get();
        return view('organizations.pages.employees.add-employees',compact('dept','roles'));
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
                    'image' => 'required|image|mimes:jpeg,png,jpg|max:10240|min:5',
                    'password' => 'required|min:6',
                ];

                $messages = [
                    'employee_name.required' => 'Please enter the company name.',
                    'employee_name.string' => 'The company name must be a valid string.',
                    'employee_name.max' => 'The company name must not exceed 255 characters.',
                    
                    'email.required' => 'Please enter the email.',
                    'email.email' => 'Please enter a valid email address.',
                    'email.max' => 'The email must not exceed 255 characters.',
                    
                    'mobile_number.required' => 'Please enter the mobile number.',
                    'mobile_number.string' => 'The mobile number must be a valid string.',
                    'mobile_number.max' => 'The mobile number must not exceed 20 characters.',
                    
                    'address.required' => 'Please enter the address.',
                    'address.string' => 'The address must be a valid string.',
                    'address.max' => 'The address must not exceed 255 characters.',
                    
                    'employee_count.integer' => 'The employee count must be an integer.',
                    
                                        
                    'image.required' => 'The image is required.',
                    'image.image' => 'The image must be a valid image file.',
                    'image.mimes' => 'The image must be in JPEG, PNG, JPG format.',
                    'image.max' => 'The image size must not exceed 10MB.',
                    'image.min' => 'The image size must not be less than 5KB.',
                ];
  
          try {
              $validation = Validator::make($request->all(), $rules, $messages);
              
              if ($validation->fails()) {
                  return redirect('organizations-add-employees')
                      ->withInput()
                      ->withErrors($validation);
              } else {
                  $add_record = $this->service->addAll($request);
                //   dd($add_record);
                  if ($add_record) {
                      $msg = $add_record['msg'];
                      $status = $add_record['status'];
  
                      if ($status == 'success') {
                          return redirect('organizations-list-employees')->with(compact('msg', 'status'));
                      } else {
                          return redirect('organizations-add-employees')->withInput()->with(compact('msg', 'status'));
                      }
                  }
              }
          } catch (Exception $e) {
              return redirect('organizations-add-employees')->withInput()->with(['msg' => $e->getMessage(), 'status' => 'error']);
          }
      }




  public function edit(Request $request){
    $edit_data_id = base64_decode($request->id);
    $editData = $this->service->getById($edit_data_id);
    $dept=DepartmentsModel::get();
    $roles=RolesModel::get();
    return view('organizations.pages.employees.edit-employees', compact('editData','dept','roles'));
}


        public function update(Request $request){
            $rules = [
                    'employee_name' => 'required|string|max:255',
                    'email' => 'required|email|max:255',
                    'mobile_number' => 'required|string|max:20',
                    'address' => 'required|string|max:255',
                 
                ];
    
            if($request->has('image')) {
                $rules['image'] = 'required|image|mimes:jpeg,png,jpg|max:10240|min:5';//|dimensions:min_width=100,min_height=100,max_width=5000,max_height=5000';
            }
           
            $messages = [
                    'employee_name.required' => 'Please enter the company name.',
                    'employee_name.string' => 'The company name must be a valid string.',
                    'employee_name.max' => 'The company name must not exceed 255 characters.',
                    
                    'email.required' => 'Please enter the email.',
                    'email.email' => 'Please enter a valid email address.',
                    'email.max' => 'The email must not exceed 255 characters.',
                    
                    'mobile_number.required' => 'Please enter the mobile number.',
                    'mobile_number.string' => 'The mobile number must be a valid string.',
                    'mobile_number.max' => 'The mobile number must not exceed 20 characters.',
                    
                    'address.required' => 'Please enter the address.',
                    'address.string' => 'The address must be a valid string.',
                    'address.max' => 'The address must not exceed 255 characters.',
                    
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
                            return redirect('organizations-list-employees')->with(compact('msg', 'status'));
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
                        return redirect('organizations-list-employees')->with(compact('msg', 'status'));
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
