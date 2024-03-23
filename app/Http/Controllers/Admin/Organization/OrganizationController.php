<?php

namespace App\Http\Controllers\Admin\Organization;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Admin\Organization\OrganizationServices;
use Session;
use Validator;
use Config;
use Carbon;
use App\Models\
{
    OrganizationModel,DepartmentsModel,RolesModel,EmployeesModel,HREmployee
};    
class OrganizationController extends Controller
{ 
    public function __construct(){
        $this->service = new OrganizationServices();
        }



    public function index(){
        try {
            $getOutput = $this->service->getAll();
            // dd($getOutput);
            return view('admin.pages.organizations.list-organizations', compact('getOutput'));
        } catch (\Exception $e) {
            return $e;
        }
    }    


    public function add(){
        return view('admin.pages.organizations.add-organizations');
    }




      public function store(Request $request){
        // dd($request);
         $rules = [
                    'company_name' => 'required|string|max:255',
                    'email' => 'required|email|max:255',
                    'mobile_number' => 'required|string|max:20',
                    'address' => 'required|string|max:255',
                    'employee_count' => 'nullable',
                    'founding_date' => 'nullable|date',
                    'image' => 'required|image|mimes:jpeg,png,jpg|max:10240|min:5',
                ];

                $messages = [
                    'company_name.required' => 'Please enter the company name.',
                    'company_name.string' => 'The company name must be a valid string.',
                    'company_name.max' => 'The company name must not exceed 255 characters.',
                    
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
                    
                    'founding_date.date' => 'Please enter a valid founding date.',
                    
                    'website_link.url' => 'Please enter a valid website URL.',
                    'website_link.max' => 'The website must not exceed 255 characters.',
                    
                    'is_active.boolean' => 'The active status must be a boolean value.',
                    
                    'image.required' => 'The image is required.',
                    'image.image' => 'The image must be a valid image file.',
                    'image.mimes' => 'The image must be in JPEG, PNG, JPG format.',
                    'image.max' => 'The image size must not exceed 10MB.',
                    'image.min' => 'The image size must not be less than 5KB.',
                ];
  
          try {
              $validation = Validator::make($request->all(), $rules, $messages);

              if ($validation->fails()) {
                  return redirect('add-organizations')
                      ->withInput()
                      ->withErrors($validation);
              } else {
                  $add_record = $this->service->addAll($request);
                //   dd($add_record);
                  if ($add_record) {
                      $msg = $add_record['msg'];
                      $status = $add_record['status'];
  
                      if ($status == 'success') {
                          return redirect('list-organizations')->with(compact('msg', 'status'));
                      } else {
                          return redirect('add-organizations')->withInput()->with(compact('msg', 'status'));
                      }
                  }
              }
          } catch (Exception $e) {
              return redirect('add-organizations')->withInput()->with(['msg' => $e->getMessage(), 'status' => 'error']);
          }
      }




  public function edit(Request $request){
    $edit_data_id = base64_decode($request->id);
    $editData = $this->service->getById($edit_data_id);
    $editData->founding_date = Carbon\Carbon::parse($editData->founding_date)->format('d/m/Y');

    return view('admin.pages.organizations.edit-organizations', compact('editData'));
}


        public function update(Request $request){
            $rules = [
                    'company_name' => 'required|string|max:255',
                    'email' => 'required|email|max:255',
                    'mobile_number' => 'required|string|max:20',
                    'address' => 'required|string|max:255',
                    'employee_count' => 'nullable',
                ];
    
            if($request->has('image')) {
                $rules['image'] = 'required|image|mimes:jpeg,png,jpg|max:10240|min:5';//|dimensions:min_width=100,min_height=100,max_width=5000,max_height=5000';
            }
           
            $messages = [
                    'company_name.required' => 'Please enter the company name.',
                    'company_name.string' => 'The company name must be a valid string.',
                    'company_name.max' => 'The company name must not exceed 255 characters.',
                    
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
                    
                    'founding_date.date' => 'Please enter a valid founding date.',
                    
                    'website_link.url' => 'Please enter a valid website URL.',
                    'website_link.max' => 'The website must not exceed 255 characters.',
                    
                    'is_active.boolean' => 'The active status must be a boolean value.',
                    
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
                    // dd($update_data);
                    if ($update_data) {
                        $msg = $update_data['msg'];
                        $status = $update_data['status'];
                        if ($status == 'success') {
                            return redirect('list-organizations')->with(compact('msg', 'status'));
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
                        return redirect('list-organizations')->with(compact('msg', 'status'));
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

    public function details(Request $request){
        $id=base64_decode($request->id);
        $detailsData = OrganizationModel::where('id',$id)->get();
        $dept=DepartmentsModel::get();
        $roles=RolesModel::get();
        $employees = EmployeesModel::get();

        // dd($dept);
        // $detailsData->founding_date = Carbon\Carbon::parse($detailsData->founding_date)->format('d/m/Y');
        return view('admin.pages.organizations.detail-organizations',compact('detailsData','dept','roles','employees'));
    }
    


     public function filterEmployees(Request $request)
    {
          try {
        $roleId = $request->id;
        $data = EmployeesModel::where('department_id', $roleId)->get();
        return response()->json($data);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }

}
}
