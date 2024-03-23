<?php

namespace App\Http\Controllers\Admin\Roles;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Admin\Roles\RolesServices;
use Session;
use Validator;
use Config;
use Carbon;
use App\Models\RolesModel;
class RolesController extends Controller
{ 
    public function __construct(){
        $this->service = new RolesServices();
    }



    public function index(){
        try {
            $getOutput = $this->service->getAll();
            return view('admin.pages.roles.list-roles', compact('getOutput'));
        } catch (\Exception $e) {
            return $e;
        }
    }    


    public function add(){
        return view('admin.pages.roles.add-roles');
    }

      public function store(Request $request){
         $rules = [
                    'role_name' => 'required|string|max:255',
                    
                ];

                $messages = [
                    'role_name.required' => 'Please enter the role name.',
                    'role_name.string' => 'The company name must be a valid string.',
                    'role_name.max' => 'The company name must not exceed 255 characters.',
                ];
  
          try {
              $validation = Validator::make($request->all(), $rules, $messages);
              
              if ($validation->fails()) {
                  return redirect('add-roles')
                      ->withInput()
                      ->withErrors($validation);
              } else {
                  $add_record = $this->service->addAll($request);
                //   dd($add_record);
                  if ($add_record) {
                      $msg = $add_record['msg'];
                      $status = $add_record['status'];
  
                      if ($status == 'success') {
                          return redirect('list-roles')->with(compact('msg', 'status'));
                      } else {
                          return redirect('add-roles')->withInput()->with(compact('msg', 'status'));
                      }
                  }
              }
          } catch (Exception $e) {
              return redirect('add-roles')->withInput()->with(['msg' => $e->getMessage(), 'status' => 'error']);
          }
      }




  public function edit(Request $request){
    $edit_data_id = base64_decode($request->id);
    $editData = $this->service->getById($edit_data_id);
    $data=RolesModel::orderby('updated_at','desc')->get();
    return view('admin.pages.roles.edit-roles', compact('editData','data'));
}


        public function update(Request $request){
            $rules = [
                    'role_name' => 'required|string|max:255',
                    
                ];
    
                     
            $messages = [
                    'role_name.required' => 'Please enter the department name.',
                    'role_name.string' => 'The company name must be a valid string.',
                    'role_name.max' => 'The company name must not exceed 255 characters.',
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
                            return redirect('list-roles')->with(compact('msg', 'status'));
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
                        return redirect('list-roles')->with(compact('msg', 'status'));
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
