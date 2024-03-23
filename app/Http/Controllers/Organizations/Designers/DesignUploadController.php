<?php

namespace App\Http\Controllers\Organizations\Designers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Organizations\Designers\DesignsServices;
use Illuminate\Validation\Rule;
use Session;
use Validator;
use Config;
use Carbon;

class DesignUploadController extends Controller
{ 
    public function __construct(){
        $this->service = new DesignsServices();
    }
    
    public function getAllNewRequirement(Request $request){
        try {
            $data_output = $this->service->getAllNewRequirement();
        
            return view('organizations.designer.design-upload.list-new-requirements-received-for-design', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    } 
    public function index(){
        try {
            $data_output = $this->service->getAll();
            return view('organizations.designer.design-upload.list-design-upload', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }     

    public function add($id){
        try {
            $addData = base64_decode($id);
            return view('organizations.designer.design-upload.add-design-upload', compact('addData'));
        } catch (\Exception $e) {
            return $e;
        }
    } 


    public function addReUploadDesing($id){
        try {
            $design_revision_for_prod_id = base64_decode($id);
            return view('organizations.designer.design-upload.add-design-re-submit-upload', compact('design_revision_for_prod_id'));
        } catch (\Exception $e) {
            return $e;
        }
    } 


    public function update(Request $request){
            
        $rules = [
            // 'design_image' => 'required|image|mimes:jpeg,png,jpg|max:'.Config::get("AllFileValidation.DESIGNS_IMAGE_MAX_SIZE").'|dimensions:min_width=300,min_height=300,max_width=2000,max_height=2000|min:'.Config::get("AllFileValidation.DESIGNS_IMAGE_MIN_SIZE").'',
            // 'bom_image' => 'required|image|mimes:jpeg,png,jpg|max:'.Config::get("AllFileValidation.DESIGNS_IMAGE_MAX_SIZE").'|dimensions:min_width=300,min_height=300,max_width=2000,max_height=2000|min:'.Config::get("AllFileValidation.DESIGNS_IMAGE_MIN_SIZE").'',
         ];

         $messages = [
            // 'design_image.required' => 'The design image is required.',
            // 'design_image.image' => 'The design image must be a valid image file.',
            // 'design_image.mimes' => 'The design image must be in JPEG, PNG, JPG format.',
            // 'design_image.max' => 'The design image size must not exceed '.Config::get("AllFileValidation.DESIGNS_IMAGE_MAX_SIZE").'KB .',
            // 'design_image.min' => 'The design image size must not be less than '.Config::get("AllFileValidation.DESIGNS_IMAGE_MIN_SIZE").'KB .',
            // 'design_image.dimensions' => 'The design image dimensions must be between 300x300 and 2000x2000 pixels.',
            // 'bom_image.required' => 'The bom image is required.',
            // 'bom_image.image' => 'The bom image must be a valid image file.',
            // 'bom_image.mimes' => 'The bom image must be in JPEG, PNG, JPG format.',
            // 'bom_image.max' => 'The bom image size must not exceed '.Config::get("AllFileValidation.DESIGNS_IMAGE_MAX_SIZE").'KB .',
            // 'bom_image.min' => 'The image bom size must not be less than '.Config::get("AllFileValidation.DESIGNS_IMAGE_MIN_SIZE").'KB .',
            // 'bom_image.dimensions' => 'The bom image dimensions must be between 300x300 and 2000x2000 pixels.',
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
                         return redirect('list-design-upload')->with(compact('msg', 'status'));
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

     
     public function updateReUploadDesign(Request $request){
            
        $rules = [
            // 'design_image' => 'required|image|mimes:jpeg,png,jpg|max:'.Config::get("AllFileValidation.DESIGNS_IMAGE_MAX_SIZE").'|dimensions:min_width=300,min_height=300,max_width=2000,max_height=2000|min:'.Config::get("AllFileValidation.DESIGNS_IMAGE_MIN_SIZE").'',
            // 'bom_image' => 'required|image|mimes:jpeg,png,jpg|max:'.Config::get("AllFileValidation.DESIGNS_IMAGE_MAX_SIZE").'|dimensions:min_width=300,min_height=300,max_width=2000,max_height=2000|min:'.Config::get("AllFileValidation.DESIGNS_IMAGE_MIN_SIZE").'',
         ];

         $messages = [
            // 'design_image.required' => 'The design image is required.',
            // 'design_image.image' => 'The design image must be a valid image file.',
            // 'design_image.mimes' => 'The design image must be in JPEG, PNG, JPG format.',
            // 'design_image.max' => 'The design image size must not exceed '.Config::get("AllFileValidation.DESIGNS_IMAGE_MAX_SIZE").'KB .',
            // 'design_image.min' => 'The design image size must not be less than '.Config::get("AllFileValidation.DESIGNS_IMAGE_MIN_SIZE").'KB .',
            // 'design_image.dimensions' => 'The design image dimensions must be between 300x300 and 2000x2000 pixels.',
            // 'bom_image.required' => 'The bom image is required.',
            // 'bom_image.image' => 'The bom image must be a valid image file.',
            // 'bom_image.mimes' => 'The bom image must be in JPEG, PNG, JPG format.',
            // 'bom_image.max' => 'The bom image size must not exceed '.Config::get("AllFileValidation.DESIGNS_IMAGE_MAX_SIZE").'KB .',
            // 'bom_image.min' => 'The image bom size must not be less than '.Config::get("AllFileValidation.DESIGNS_IMAGE_MIN_SIZE").'KB .',
            // 'bom_image.dimensions' => 'The bom image dimensions must be between 300x300 and 2000x2000 pixels.',
                 ];
 
         try {
             $validation = Validator::make($request->all(),$rules, $messages);
             if ($validation->fails()) {
                 return redirect()->back()
                     ->withInput()
                     ->withErrors($validation);
             } else {
                 
                 $update_data = $this->service->updateReUploadDesign($request);
                 
                 if ($update_data) {
                     $msg = $update_data['msg'];
                     $status = $update_data['status'];
                     if ($status == 'success') {
                         return redirect('list-design-upload')->with(compact('msg', 'status'));
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
}