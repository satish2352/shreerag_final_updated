<?php

namespace App\Http\Controllers\Organizations\Productions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Organizations\Productions\PurchaseServices;
use Session;
use Validator;
use Config;
use Carbon;
use App\Models\PurchaseModel;

class PurchaseController extends Controller
{ 
    public function __construct(){
        $this->service = new PurchaseServices();
    }



    public function index(){
        try {
            $getOutput = PurchaseModel::get();
            return view('organizations.productions.purchases.list-purchases', compact('getOutput'));
        } catch (\Exception $e) {
            return $e;
        }
    }    

    public function add(){

        return view('organizations.productions.purchases.add-purchases');
    }

      public function store(Request $request){
        // dd($request);
        $rules = [
                'name' => 'required|string|max:255',
                'email' => 'required|max:255',
                'price' => 'required|string|max:20',
                'contact' => 'required|string|max:255',
            ];

            $messages = [
                        'name.required' => 'The design name is required.',
                        'name.string' => 'The design name must be a valid string.',
                        'name.max' => 'The design name must not exceed 255 characters.',
                        
                        'email.required' => 'The design page is required.',
                        'email.max' => 'The design page must not exceed 255 characters.',
                        
                        'price.required' => 'The project name is required.',
                        'price.string' => 'The project name must be a valid string.',
                        'price.max' => 'The project name must not exceed 20 characters.',
                        
                        'contact.required' => 'The time allocation is required.',
                        'contact.string' => 'The time allocation must be a valid string.',
                        'contact.max' => 'The time allocation must not exceed 255 characters.',
                        
                        'image.required' => 'The image is required.',
                        'image.image' => 'The image must be a valid image file.',
                        'image.mimes' => 'The image must be in JPEG, PNG, JPG format.',
                        'image.max' => 'The image size must not exceed 10MB.',
                        'image.min' => 'The image size must not be less than 5KB.',
                    ];

  
          try {
              $validation = Validator::make($request->all(), $rules, $messages);
              
              if ($validation->fails()) {
                  return redirect('add-purchases')
                      ->withInput()
                      ->withErrors($validation);
              } else {
                  $add_record = $this->service->addAll($request);
                
                  if ($add_record) {
                      $msg = $add_record['msg'];
                      $status = $add_record['status'];
  
                      if ($status == 'success') {
                          return redirect('list-purchases')->with(compact('msg', 'status'));
                      } else {
                          return redirect('add-purchases')->withInput()->with(compact('msg', 'status'));
                      }
                  }
              }
          } catch (Exception $e) {
              return redirect('add-purchases')->withInput()->with(['msg' => $e->getMessage(), 'status' => 'error']);
          }
      }




  public function edit(Request $request){
    $edit_data_id = base64_decode($request->id);
    $editData = $this->service->getById($edit_data_id);

    return view('organizations.productions.purchases.edit-purchases', compact('editData'));
}


        public function update(Request $request){
           $rules = [
                'name' => 'required|string|max:255',
                'email' => 'required|max:255',
                'price' => 'required|string|max:20',
                'contact' => 'required|string|max:255',
                'image' => 'image|mimes:jpeg,png,jpg|max:10240|min:5',
            ];

            $messages = [
                        'name.required' => 'The design name is required.',
                        'name.string' => 'The design name must be a valid string.',
                        'name.max' => 'The design name must not exceed 255 characters.',
                        
                        'email.required' => 'The design page is required.',
                        'email.max' => 'The design page must not exceed 255 characters.',
                        
                        'price.required' => 'The project name is required.',
                        'price.string' => 'The project name must be a valid string.',
                        'price.max' => 'The project name must not exceed 20 characters.',
                        
                        'contact.required' => 'The time allocation is required.',
                        'contact.string' => 'The time allocation must be a valid string.',
                        'contact.max' => 'The time allocation must not exceed 255 characters.',
                        
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
                            return redirect('list-purchases')->with(compact('msg', 'status'));
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
                        return redirect('list-purchases')->with(compact('msg', 'status'));
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
