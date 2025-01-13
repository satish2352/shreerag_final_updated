<?php

namespace App\Http\Controllers\Organizations\HR\Employees;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Organizations\HR\Employees\EmployeesHrServices;
use Session;
use Validator;
use Config;
use Carbon;
// use App\Models\DepartmentsModel;
use App\Models\
{
    DepartmentsModel, RolesModel,EmployeesModel,
    TblArea
};

class EmployeesHrController extends Controller
{ 
    public function __construct(){
        $this->service = new EmployeesHrServices();
    }

    public function index(){
        $register_user = $this->service->index();
        return view('organizations.hr.employees.list-employees',compact('register_user'));
    }

    public function addUsers(){
        $roles = RolesModel::where('is_active', true)
                        ->select('id','role_name')
                        ->get()
                        ->toArray();
        $dynamic_state = TblArea::where('location_type', 1)
                            ->select('location_id','name')
                            ->get()
                            ->toArray();
        $dept=DepartmentsModel::get();
                          
    	return view('organizations.hr.employees.add-employees',compact('roles','dynamic_state','dept'));
    }

    public function getCities(Request $request){
        $stateId = $request->input('stateId');
        $city = TblArea::where('location_type', 2) // 4 represents cities
                    ->where('parent_id', $stateId)
                    ->get(['location_id', 'name']);
              return response()->json(['city' => $city]);

    }

    public function getState(Request $request){
        $stateId = $request->input('stateId');
        $state =  TblArea::where('location_type', 1)
                            // ->where('parent_id', $stateId)
                            ->select('location_id','name')
                            ->get()
                            ->toArray();
        return response()->json(['state' => $state]);

    }

    public function register(Request $request){

        $rules = [
                //    'u_email' => 'required|unique:users,u_email|regex:/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z])+\.)+([a-zA-Z0-9]{2,4})+$/',
                //     'u_password'=>'required|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[a-zA-Z\d]).{8,}$/',
                //     'password_confirmation' => 'required|same:u_password',
                //     'role_id' => 'required',
                //     'f_name' => 'required|regex:/^[a-zA-Z\s]+$/u|max:255',
                //     'm_name' => 'required|regex:/^[a-zA-Z\s]+$/u|max:255',
                //     'l_name' => 'required|regex:/^[a-zA-Z\s]+$/u|max:255',
                //     'number' =>  'required|regex:/^[0-9]{10}$/',
                //     'designation' => 'required|regex:/^[a-zA-Z\s]+$/u|max:255',
                //     'address' => ['required','regex:/^(?![0-9\s]+$)[A-Za-z0-9\s\.,#\-\(\)\[\]\{\}]+$/','max:255'],
                //     'state' => 'required',
                //     'city' => 'required',
                //     'pincode' => 'required|regex:/^[0-9]{6}$/',
                    // 'user_profile' => 'required|image|mimes:jpeg,png,jpg|max:'.Config::get("AllFileValidation.USER_PROFILE_MAX_SIZE").'|dimensions:min_width=150,min_height=150,max_width=400,max_height=400|min:'.Config::get("AllFileValidation.USER_PROFILE_MIN_SIZE").'',

                 ];       

        $messages = [   
                    //     'u_email.required' => 'Please enter email.',
                    //     'u_email.unique' => 'Your email is already exist.',
                    //     'u_email.regex' => 'Enter valid email.',
                    //     // 'u_uname.required'=>'Please enter firstname.',
                    //     // 'u_uname.regex' => 'Please  enter text only.',
                    //     // 'u_uname.max'   => 'Please  enter firstname length upto 255 character only.',       
                    //     'u_password.required' => 'Please enter password.',
                    //     'u_password.regex' => 'Password should be more than 8 numbers with atleast 1 capital letter,1 small letter, 1 number and 1 special character.',
                    //     'password_confirmation.same' => 'The password confirmation does not match.',
                    //     // 'u_password.min' => 'Please combination of number character of 8 char.',
                    //     'role_id.required' => 'Please select role type.',
                    //     'f_name.required' => 'Please enter first name.',
                    //      'f_name.regex' => 'Please  enter text only.',
                    //     'f_name.max'   => 'Please  enter first name length upto 255 character only.',

                    //     'm_name.required' =>'Please enter middle name.',
                    //     'm_name.regex' => 'Please  enter text only.',
                    //     'm_name.max'   => 'Please  enter middle name length upto 255 character only.',

                    //     'l_name.required' => 'Please enter last name.',
                    //     'l_name.regex' => 'Please  enter text only.',
                    //     'l_name.max'   => 'Please  enter last name length upto 255 character only.',

                    //     'number.required' => 'Please enter number.',
                    //     'number.regex' => 'Please enter only numbers with 10-digit.',

                    //     'designation.required' =>'Please enter designation.',
                    //     'designation.regex' => 'Please  enter text only.',
                    //     'designation.max'   => 'Please  enter designation length upto 255 character only.',

                    //     'address.required' => 'Please enter address.',
                    //     'address.regex' => 'Please enter right address.',
                    //     'address.max'   => 'Please  enter address length upto 255 character only.',


                    //     'state.required' => 'Please select state.',
                    //     'city.required' =>'Please select city.',
                    //    'pincode.required' => 'Please enter pincode.',
                    //     'pincode.regex' => 'Please enter a 6-digit pincode.',
                                            ];


        $validation = Validator::make($request->all(),$rules,$messages);
        if($validation->fails() )
        {
            return redirect('hr/add-users')
            ->withInput()
            ->withErrors($validation);
        }
        else
        {
            $register_user = $this->service->register($request);
            if($register_user)
            {
              
                $msg = $register_user['msg'];
                $status = $register_user['status'];
                if($status=='success') {
                    return redirect('hr/list-users')->with(compact('msg','status'));
                }
                else {
                    return redirect('hr/add-users')->withInput()->with(compact('msg','status'));
                }
            }
            
        }


    }

    public function editUsers(Request $request){
        $user_data = $this->service->editUsers($request);
        return view('organizations.hr.employees.edit-employees',compact('user_data'));
    }

    public function update(Request $request){
        // $user_data = $this->service->editUsers($request);
        // return view('admin.pages.users.users-list',compact('user_data'));

        $rules = [
            // 'u_email' => 'required',
            // 'u_uname' => 'required',
            // 'u_password' => 'required',
            // 'role_id' => 'required',
            'f_name' => 'required|regex:/^[a-zA-Z\s]+$/u|max:255',
            'm_name' => 'required|regex:/^[a-zA-Z\s]+$/u|max:255',
            'l_name' => 'required|regex:/^[a-zA-Z\s]+$/u|max:255',
            'number' =>  'required|regex:/^[0-9]{10}$/',
            'designation' => 'required|regex:/^[a-zA-Z\s]+$/u|max:255',
            'address' => ['required','regex:/^(?![0-9\s]+$)[A-Za-z0-9\s\.,#\-\(\)\[\]\{\}]+$/','max:255'],
            'state' => 'required',
            'city' => 'required',
            'pincode' => 'required|regex:/^[0-9]{6}$/',
         ];       

        $messages = [   
                        // 'u_email.required' => 'Please enter email.',
                        // 'u_email.email' => 'Please enter valid email.',
                        // 'u_uname.required' => 'Please enter user uname.',
                        // 'u_password.required' => 'Please enter password.',
                        // 'role_id.required' => 'Please select role type.',
                        'f_name.required' => 'Please enter first name.',
                         'f_name.regex' => 'Please  enter text only.',
                        'f_name.max'   => 'Please  enter first name length upto 255 character only.',

                        'm_name.required' =>'Please enter middle name.',
                        'm_name.regex' => 'Please  enter text only.',
                        'm_name.max'   => 'Please  enter middle name length upto 255 character only.',

                        'l_name.required' => 'Please enter last name.',
                        'l_name.regex' => 'Please  enter text only.',
                        'l_name.max'   => 'Please  enter last name length upto 255 character only.',

                        'number.required' => 'Please enter number.',
                        'number.regex' => 'Please enter only numbers with 10-digit.',

                        'designation.required' =>'Please enter designation.',
                        'designation.regex' => 'Please  enter text only.',
                        'designation.max'   => 'Please  enter designation length upto 255 character only.',

                        'address.required' => 'Please enter address.',
                        'address.regex' => 'Please enter right address.',
                        'address.max'   => 'Please  enter address length upto 255 character only.',


                        'state.required' => 'Please select state.',
                        'city.required' =>'Please select city.',
                       'pincode.required' => 'Please enter pincode.',
                        'pincode.regex' => 'Please enter a 6-digit pincode.',
                    ];


        try {
            $validation = Validator::make($request->all(),$rules, $messages);
            if ($validation->fails()) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors($validation);
            } else {
                $register_user = $this->service->update($request);

                if($register_user)
                {
                
                    $msg = $register_user['msg'];
                    $status = $register_user['status'];
                    if($status=='success') {
                        return redirect('hr/list-users')->with(compact('msg','status'));
                    }
                    else {
                        return redirect('hr/list-users')->withInput()->with(compact('msg','status'));
                    }
                }
                
            }

        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with(['msg' => $e->getMessage(), 'status' => 'error']);
        }

    }

    public function show(Request $request){
        try {
            $data_id = base64_decode($request->id);
          
            $user_detail = $this->service->getById($data_id);
  
            return view('organizations.hr.employees.show-employees', compact('user_detail'));
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function showParticularDetails(Request $request){
        try {
            $id = Auth::user()->id;
            
            $data_id = base64_decode($request->id);
          
            $user_detail = $this->service->showParticularDetails($data_id);
  
            return view('organizations.hr.employees.show-employees', compact('user_detail'));
        } catch (\Exception $e) {
            return $e;
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
                    return redirect('hr/list-users')->with(compact('msg', 'status'));
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
