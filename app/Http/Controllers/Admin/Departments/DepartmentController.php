<?php

namespace App\Http\Controllers\Admin\Departments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Admin\Departments\DepartmentsServices;
use Session;
use Validator;
use Config;
use Carbon;
use App\Models\OrganizationModel;
class DepartmentController extends Controller
{ 
    public function __construct(){
        $this->service = new DepartmentsServices();
    }



    public function index(){
        try {
            $getOutput = $this->service->getAll();
            return view('admin.pages.departments.list-departments', compact('getOutput'));
        } catch (\Exception $e) {
            return $e;
        }
    }    


    public function add(){
        return view('admin.pages.departments.add-departments');
    }




      public function store(Request $request){
         $rules = [
                    'department_name' => 'required|string|max:255',
                    
                ];

                $messages = [
                    'department_name.required' => 'Please enter the department name.',
                    'department_name.string' => 'The company name must be a valid string.',
                    'department_name.max' => 'The company name must not exceed 255 characters.',
                ];
  
          try {
              $validation = Validator::make($request->all(), $rules, $messages);
              
              if ($validation->fails()) {
                  return redirect('add-departments')
                      ->withInput()
                      ->withErrors($validation);
              } else {
                  $add_record = $this->service->addAll($request);
                //   dd($add_record);
                  if ($add_record) {
                      $msg = $add_record['msg'];
                      $status = $add_record['status'];
  
                      if ($status == 'success') {
                          return redirect('list-departments')->with(compact('msg', 'status'));
                      } else {
                          return redirect('add-departments')->withInput()->with(compact('msg', 'status'));
                      }
                  }
              }
          } catch (Exception $e) {
              return redirect('add-departments')->withInput()->with(['msg' => $e->getMessage(), 'status' => 'error']);
          }
      }




  public function edit(Request $request){
    $edit_data_id = base64_decode($request->id);
    $editData = $this->service->getById($edit_data_id);
    $data=OrganizationModel::orderby('updated_at','desc')->get();
    return view('admin.pages.departments.edit-departments', compact('editData','data'));
}


        public function update(Request $request){
            $rules = [
                    'department_name' => 'required|string|max:255',
                    
                ];
    
                     
            $messages = [
                    'department_name.required' => 'Please enter the department name.',
                    'department_name.string' => 'The company name must be a valid string.',
                    'department_name.max' => 'The company name must not exceed 255 characters.',
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
                            return redirect('list-departments')->with(compact('msg', 'status'));
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
            // dd($delete_data_id);
            try {
                $delete_record = $this->service->deleteById($delete_data_id);
                if ($delete_record) {
                    $msg = $delete_record['msg'];
                    $status = $delete_record['status'];
                    if ($status == 'success') {
                        return redirect('list-departments')->with(compact('msg', 'status'));
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
