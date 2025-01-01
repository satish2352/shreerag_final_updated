<?php

namespace App\Http\Controllers\Admin\CMS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Admin\CMS\VisionMissionServices;
use Session;
use Validator;
use Config;

class VisionMissionController extends Controller
{
    public function __construct(){
        $this->service = new VisionMissionServices();
        }
        public function index(){
            try {
                $getOutput = $this->service->getAll();
                return view('admin.cms.vision-mission.list-vision-mission', compact('getOutput'));
            } catch (\Exception $e) {
                return $e;
            }
        }    
        public function add(){
            return view('admin.cms.vision-mission.add-vision-mission');
        }
        public function store(Request $request){
            $rules = [
                'vision_description' => 'required',
                'mission_description' => 'required',
                'vision_image' => 'required|image|mimes:jpeg,png,jpg|max:'.Config::get("AllFileValidation.VISION_MISSION_IMAGE_MAX_SIZE").'|min:'.Config::get("AllFileValidation.VISION_MISSION_IMAGE_MIN_SIZE").'',
                'mission_image' => 'required|image|mimes:jpeg,png,jpg|max:'.Config::get("AllFileValidation.VISION_MISSION_IMAGE_MAX_SIZE").'|min:'.Config::get("AllFileValidation.VISION_MISSION_IMAGE_MIN_SIZE").'',
               
            ];
            $messages = [    
            'vision_description.required' => 'Please enter vision description.',
            'mission_description.required' => 'Please enter mission description.',
            'vision_image.required' => 'The image is required.',
            'vision_image.image' => 'The image must be a valid image file.',
            'vision_image.mimes' => 'The image must be in JPEG, PNG, JPG format.',
            'vision_image.max' => 'The image size must not exceed '.Config::get("AllFileValidation.VISION_MISSION_IMAGE_MAX_SIZE").'KB .',
            'vision_image.min' => 'The image size must not be less than '.Config::get("AllFileValidation.VISION_MISSION_IMAGE_MIN_SIZE").'KB .',
            // 'vision_image.dimensions' => 'The image dimensions must be between 300X300 and 1000x1000 pixels.',
            
            'mission_image.required' => 'The image is required.',
            'mission_image.image' => 'The image must be a valid image file.',
            'mission_image.mimes' => 'The image must be in JPEG, PNG, JPG format.',
            'mission_image.max' => 'The image size must not exceed '.Config::get("AllFileValidation.VISION_MISSION_IMAGE_MAX_SIZE").'KB .',
            'mission_image.min' => 'The image size must not be less than '.Config::get("AllFileValidation.VISION_MISSION_IMAGE_MIN_SIZE").'KB .',
            // 'mission_image.dimensions' => 'The image dimensions must be between 300X300 and 1000x1000 pixels.',
            ];
    
            try {
                $validation = Validator::make($request->all(), $rules, $messages);
                
                if ($validation->fails()) {
                    return redirect('cms/add-vision-mission')
                        ->withInput()
                        ->withErrors($validation);
                } else {
                    $add_record = $this->service->addAll($request);
    
                    if ($add_record) {
                        $msg = $add_record['msg'];
                        $status = $add_record['status'];
    
                        if ($status == 'success') {
                            return redirect('cms/list-vision-mission')->with(compact('msg', 'status'));
                        } else {
                            return redirect('cms/add-vision-mission')->withInput()->with(compact('msg', 'status'));
                        }
                    }
                }
            } catch (Exception $e) {
                return redirect('cms/add-vision-mission')->withInput()->with(['msg' => $e->getMessage(), 'status' => 'error']);
            }
        }
        public function show(Request $request){
            try {
                $showData = $this->service->getById($request->show_id);
                return view('admin.cms.vision-mission.show-vision-mission', compact('showData'));
            } catch (\Exception $e) {
                return $e;
            }
        }
        public function edit(Request $request){
            $edit_data_id = base64_decode($request->edit_id);
            $editData = $this->service->getById($edit_data_id);
           
            return view('admin.cms.vision-mission.edit-vision-mission', compact('editData'));
        }
        public function update(Request $request){
            $rules = [
                'vision_description' => 'required',
                'mission_description' => 'required',
                
            ];
    
            if($request->has('vision_image')) {
                $rules['vision_image'] = 'required|image|mimes:jpeg,png,jpg|max:'.Config::get("AllFileValidation.VISION_MISSION_IMAGE_MAX_SIZE").'|min:'.Config::get("AllFileValidation.VISION_MISSION_IMAGE_MIN_SIZE");
            }
            if($request->has('mission_image')) {
                $rules['mission_image'] = 'required|image|mimes:jpeg,png,jpg|max:'.Config::get("AllFileValidation.VISION_MISSION_IMAGE_MAX_SIZE").'|min:'.Config::get("AllFileValidation.VISION_MISSION_IMAGE_MIN_SIZE");
            }
           
            $messages = [   
            'vision_description.required' => 'Please enter vision description.',
            'mission_description.required' => 'Please enter mission description.',
            'vision_image.required' => 'The image is required.',
            'vision_image.image' => 'The image must be a valid image file.',
            'vision_image.mimes' => 'The image must be in JPEG, PNG, JPG format.',
            'vision_image.max' => 'The image size must not exceed '.Config::get("AllFileValidation.VISION_MISSION_IMAGE_MAX_SIZE").'KB .',
            'vision_image.min' => 'The image size must not be less than '.Config::get("AllFileValidation.VISION_MISSION_IMAGE_MIN_SIZE").'KB .',
            // 'vision_image.dimensions' => 'The image dimensions must be between 300X300 and 1000x1000 pixels.',
            
            'mission_image.required' => 'The image is required.',
            'mission_image.image' => 'The image must be a valid image file.',
            'mission_image.mimes' => 'The image must be in JPEG, PNG, JPG format.',
            'mission_image.max' => 'The image size must not exceed '.Config::get("AllFileValidation.VISION_MISSION_IMAGE_MAX_SIZE").'KB .',
            'mission_image.min' => 'The image size must not be less than '.Config::get("AllFileValidation.VISION_MISSION_IMAGE_MIN_SIZE").'KB .',
            // 'mission_image.dimensions' => 'The image dimensions must be between 300X300 and 1000x1000 pixels.',
               
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
                            return redirect('cms/list-vision-mission')->with(compact('msg', 'status'));
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
