<?php

namespace App\Http\Controllers\Organizations\Designers\Designs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Organizations\Designers\Designs\DesignsServices;
use Session;
use Validator;
use Config;
use Carbon;
use App\Models\DepartmentsModel;
use App\Models\RolesModel;

class DesignerControllerOld extends Controller
{ 
    public function __construct(){
        $this->service = new DesignsServices();
    }



    public function index(){
        try {
            $getOutput = $this->service->getAll();
            return view('organizations.designer.designs.list-designs', compact('getOutput'));
        } catch (\Exception $e) {
            return $e;
        }
    }    


    public function add(){
            // $dept=DepartmentsModel::get();
            // $roles=RolesModel::get();
        return view('organizations.designer.designs.add-designs');
    }




      public function store(Request $request){

        $rules = [
                'design_name' => 'required|string|max:255',
                'design_page' => 'required|max:255',
                'project_name' => 'required|string|max:20',
                'time_allocation' => 'required|string|max:255',
                'image' => 'required|image|mimes:jpeg,png,jpg|max:10240|min:5',
            ];

            $messages = [
                        'design_name.required' => 'The design name is required.',
                        'design_name.string' => 'The design name must be a valid string.',
                        'design_name.max' => 'The design name must not exceed 255 characters.',
                        
                        'design_page.required' => 'The design page is required.',
                        'design_page.max' => 'The design page must not exceed 255 characters.',
                        
                        'project_name.required' => 'The project name is required.',
                        'project_name.string' => 'The project name must be a valid string.',
                        'project_name.max' => 'The project name must not exceed 20 characters.',
                        
                        'time_allocation.required' => 'The time allocation is required.',
                        'time_allocation.string' => 'The time allocation must be a valid string.',
                        'time_allocation.max' => 'The time allocation must not exceed 255 characters.',
                        
                        'image.required' => 'The image is required.',
                        'image.image' => 'The image must be a valid image file.',
                        'image.mimes' => 'The image must be in JPEG, PNG, JPG format.',
                        'image.max' => 'The image size must not exceed 10MB.',
                        'image.min' => 'The image size must not be less than 5KB.',
                    ];

  
          try {
              $validation = Validator::make($request->all(), $rules, $messages);
              
              if ($validation->fails()) {
                  return redirect('add-designs')
                      ->withInput()
                      ->withErrors($validation);
              } else {
                  $add_record = $this->service->addAll($request);
                
                  if ($add_record) {
                      $msg = $add_record['msg'];
                      $status = $add_record['status'];
  
                      if ($status == 'success') {
                          return redirect('list-designs')->with(compact('msg', 'status'));
                      } else {
                          return redirect('add-designs')->withInput()->with(compact('msg', 'status'));
                      }
                  }
              }
          } catch (Exception $e) {
              return redirect('add-designs')->withInput()->with(['msg' => $e->getMessage(), 'status' => 'error']);
          }
      }




  public function edit(Request $request){
    $edit_data_id = base64_decode($request->id);
    $editData = $this->service->getById($edit_data_id);

    return view('organizations.designer.designs.edit-designs', compact('editData'));
}


        public function update(Request $request){
           $rules = [
                'design_name' => 'required|string|max:255',
                'design_page' => 'required|max:255',
                'project_name' => 'required|string|max:20',
                'time_allocation' => 'required|string|max:255',
                'image' => 'image|mimes:jpeg,png,jpg|max:10240|min:5',
            ];

            $messages = [
                        'design_name.required' => 'The design name is required.',
                        'design_name.string' => 'The design name must be a valid string.',
                        'design_name.max' => 'The design name must not exceed 255 characters.',
                        
                        'design_page.required' => 'The design page is required.',
                        'design_page.max' => 'The design page must not exceed 255 characters.',
                        
                        'project_name.required' => 'The project name is required.',
                        'project_name.string' => 'The project name must be a valid string.',
                        'project_name.max' => 'The project name must not exceed 20 characters.',
                        
                        'time_allocation.required' => 'The time allocation is required.',
                        'time_allocation.string' => 'The time allocation must be a valid string.',
                        'time_allocation.max' => 'The time allocation must not exceed 255 characters.',
                        
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
                            return redirect('list-designs')->with(compact('msg', 'status'));
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
                        return redirect('list-designs')->with(compact('msg', 'status'));
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
