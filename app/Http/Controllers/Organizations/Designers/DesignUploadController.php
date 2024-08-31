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
use App\Models\{
    Business,
    BusinessDetails,
    BusinessApplicationProcesses,
    AdminView
};
class DesignUploadController extends Controller
{ 
    public function __construct(){
        $this->service = new DesignsServices();
    }
    
    public function getAllNewRequirement(Request $request){
        try {
            $data_output = $this->service->getAllNewRequirement();

            $update_data['design_is_view'] = '1';
            BusinessApplicationProcesses::where('business_status_id', 1112)
                                          ->where('design_status_id', 1111)
                                          ->where('design_is_view', '0')
                                          ->update($update_data);
     
            return view('organizations.designer.design-upload.list-new-requirements-received-for-design', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    } 

    public function getAllNewRequirementBusinessWise($id){
        try {
            $data_output = $this->service->getAllNewRequirementBusinessWise($id);

            $update_data['design_is_view'] = '1';
            BusinessApplicationProcesses::where('business_status_id', 1112)
                                          ->where('design_status_id', 1111)
                                          ->where('design_is_view', '0')
                                          ->update($update_data);
     
            return view('organizations.designer.design-upload.list-new-requirements-received-for-design-businesswise', compact('data_output'));
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

    // public function add($id){
    //     try {
    //         $addData = base64_decode($id);
    //         $business_data = Business::where('id', '=', $addData)->first();
    //         $b_id = $business_data->id;
    //         $business_details_data = BusinessDetails::where('business_id', $b_id)
    //             ->get();
    //         return view('organizations.designer.design-upload.add-design-upload', compact('addData', 'business_data', 'business_details_data'));
    //     } catch (\Exception $e) {
    //         return $e;
    //     }
    // } 
    public function add($id)
    {
        try {
            $addData = base64_decode($id);

            // $business_data = Business::where('id', $addData)->get();
           
            // $business_details_data = BusinessDetails::where('id', $addData)->get();
            // return view('organizations.designer.design-upload.add-design-upload', compact('addData', 'business_details_data'));
       
            $business_details_data = BusinessDetails::findOrFail($addData);

            return view('organizations.designer.design-upload.add-design-upload', [
                'addData' => $addData,
                'business_details_data' => $business_details_data
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['msg' => $e->getMessage()]);
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


    // public function update(Request $request){
            
    //     $rules = [];

    //     // Loop through each item in 'addmore' and apply validation rules
    //     foreach ($this->request->get('addmore') as $key => $value) {
    //         $rules["addmore.{$key}.design_image"] = 'required|mimes:pdf|max:' . Config::get("AllFileValidation.DESIGNS_IMAGE_MAX_SIZE") . '|min:' . Config::get("AllFileValidation.DESIGNS_IMAGE_MIN_SIZE");
    //         $rules["addmore.{$key}.bom_image"] = 'required|mimes:xls,xlsx|max:' . Config::get("AllFileValidation.DESIGNS_IMAGE_MAX_SIZE") . '|min:' . Config::get("AllFileValidation.DESIGNS_IMAGE_MIN_SIZE");
    //     }

    //     $messages = [];

    //     // Loop through each item in 'addmore' and apply custom error messages
    //     foreach ($this->request->get('addmore') as $key => $value) {
    //         $messages["addmore.{$key}.design_image.required"] = "The design PDF in row {$key} is required.";
    //         $messages["addmore.{$key}.design_image.mimes"] = "The design PDF in row {$key} must be in PDF format.";
    //         $messages["addmore.{$key}.design_image.max"] = "The design PDF size in row {$key} must not exceed " . Config::get("AllFileValidation.DESIGNS_IMAGE_MAX_SIZE") . "KB.";
    //         $messages["addmore.{$key}.design_image.min"] = "The design PDF size in row {$key} must not be less than " . Config::get("AllFileValidation.DESIGNS_IMAGE_MIN_SIZE") . "KB.";
    //         $messages["addmore.{$key}.bom_image.required"] = "The bill of material Excel sheet in row {$key} is required.";
    //         $messages["addmore.{$key}.bom_image.mimes"] = "The bill of material in row {$key} must be in XLS or XLSX format.";
    //         $messages["addmore.{$key}.bom_image.max"] = "The bill of material size in row {$key} must not exceed " . Config::get("AllFileValidation.DESIGNS_IMAGE_MAX_SIZE") . "KB.";
    //         $messages["addmore.{$key}.bom_image.min"] = "The bill of material size in row {$key} must not be less than " . Config::get("AllFileValidation.DESIGNS_IMAGE_MIN_SIZE") . "KB.";
    //     }
 
    //      try {
    //          $validation = Validator::make($request->all(),$rules, $messages);
    //          if ($validation->fails()) {
    //              return redirect()->back()
    //                  ->withInput()
    //                  ->withErrors($validation);
    //          } else {
                 
    //              $update_data = $this->service->updateAll($request);
                 
    //              if ($update_data) {
    //                  $msg = $update_data['msg'];
    //                  $status = $update_data['status'];
    //                  if ($status == 'success') {
    //                      return redirect('designdept/list-design-upload')->with(compact('msg', 'status'));
    //                  } else {
    //                      return redirect()->back()
    //                          ->withInput()
    //                          ->with(compact('msg', 'status'));
    //                  }
    //              }
    //          }
    //      } catch (Exception $e) {
    //          return redirect()->back()
    //              ->withInput()
    //              ->with(['msg' => $e->getMessage(), 'status' => 'error']);
    //      }
    //  }

    // public function update(Request $request)
    // {
    //     // Initialize rules array
    //     $rules = [];
        
    //     // Fetch the `addmore` data from the request
    //     $addmoreData = $request->get('addmore');
    
    //     // Validate if `addmore` exists and is an array
    //     if (is_array($addmoreData)) {
    //         foreach ($addmoreData as $key => $value) {
    //             // Apply validation rules
    //             $rules["addmore.{$key}.design_image"] = 'required|mimes:pdf|max:' . Config::get("AllFileValidation.DESIGNS_IMAGE_MAX_SIZE") . '|min:' . Config::get("AllFileValidation.DESIGNS_IMAGE_MIN_SIZE");
    //             $rules["addmore.{$key}.bom_image"] = 'required|mimes:xls,xlsx|max:' . Config::get("AllFileValidation.DESIGNS_IMAGE_MAX_SIZE") . '|min:' . Config::get("AllFileValidation.DESIGNS_IMAGE_MIN_SIZE");
    //         }
    //     } else {
    //         // Handle the case where `addmore` is not present or not an array
    //         return redirect()->back()
    //             ->withInput()
    //             ->with(['msg' => 'Invalid input data.', 'status' => 'error']);
    //     }
    
    //     // Initialize custom error messages array
    //     $messages = [];
        
    //     foreach ($addmoreData as $key => $value) {
    //         $messages["addmore.{$key}.design_image.required"] = "The design PDF in row {$key} is required.";
    //         $messages["addmore.{$key}.design_image.mimes"] = "The design PDF in row {$key} must be in PDF format.";
    //         $messages["addmore.{$key}.design_image.max"] = "The design PDF size in row {$key} must not exceed " . Config::get("AllFileValidation.DESIGNS_IMAGE_MAX_SIZE") . " KB.";
    //         $messages["addmore.{$key}.design_image.min"] = "The design PDF size in row {$key} must not be less than " . Config::get("AllFileValidation.DESIGNS_IMAGE_MIN_SIZE") . " KB.";
    //         $messages["addmore.{$key}.bom_image.required"] = "The bill of material Excel sheet in row {$key} is required.";
    //         $messages["addmore.{$key}.bom_image.mimes"] = "The bill of material in row {$key} must be in XLS or XLSX format.";
    //         $messages["addmore.{$key}.bom_image.max"] = "The bill of material size in row {$key} must not exceed " . Config::get("AllFileValidation.DESIGNS_IMAGE_MAX_SIZE") . " KB.";
    //         $messages["addmore.{$key}.bom_image.min"] = "The bill of material size in row {$key} must not be less than " . Config::get("AllFileValidation.DESIGNS_IMAGE_MIN_SIZE") . " KB.";
    //     }
    
    //     try {
    //         // Validate the request
    //         $validation = Validator::make($request->all(), $rules, $messages);
            
    //         if ($validation->fails()) {
    //             // Redirect back with validation errors
    //             return redirect()->back()
    //                 ->withInput()
    //                 ->withErrors($validation);
    //         } else {
    //             // Call the update service
    //             $update_data = $this->service->updateAll($request);
                
    //             if ($update_data) {
    //                 $msg = $update_data['msg'];
    //                 $status = $update_data['status'];
                    
    //                 if ($status == 'success') {
    //                     // Redirect to the success page with success message
    //                     return redirect('designdept/list-design-upload')->with(compact('msg', 'status'));
    //                 } else {
    //                     // Redirect back with error message
    //                     return redirect()->back()
    //                         ->withInput()
    //                         ->with(compact('msg', 'status'));
    //                 }
    //             }
    //         }
    //     } catch (Exception $e) {
    //         // Handle exception and redirect back with error message
    //         return redirect()->back()
    //             ->withInput()
    //             ->with(['msg' => $e->getMessage(), 'status' => 'error']);
    //     }
    // }
    public function update(Request $request)
{
    // Validate the request
    $rules = [
        'design_image' => 'required|mimes:pdf|max:' . Config::get("AllFileValidation.DESIGNS_PDF_MAX_SIZE") . '|min:' . Config::get("AllFileValidation.DESIGNS_PDF_MIN_SIZE"),
        'bom_image' => 'required|mimes:xls,xlsx|max:' . Config::get("AllFileValidation.DESIGNS_IMAGE_MAX_SIZE") . '|min:' . Config::get("AllFileValidation.DESIGNS_IMAGE_MIN_SIZE")
    ];

    $messages = [
        'design_image.required' => 'The design PDF is required.',
        'design_image.mimes' => 'The design PDF must be in PDF format.',
        'design_image.max' => 'The design PDF size must not exceed ' . Config::get("AllFileValidation.DESIGNS_PDF_MAX_SIZE") . ' KB.',
        'design_image.min' => 'The design PDF size must not be less than ' . Config::get("AllFileValidation.DESIGNS_PDF_MIN_SIZE") . ' KB.',
        'bom_image.required' => 'The bill of material Excel sheet is required.',
        'bom_image.mimes' => 'The bill of material must be in XLS or XLSX format.',
        'bom_image.max' => 'The bill of material size must not exceed ' . Config::get("AllFileValidation.DESIGNS_IMAGE_MAX_SIZE") . ' KB.',
        'bom_image.min' => 'The bill of material size must not be less than ' . Config::get("AllFileValidation.DESIGNS_IMAGE_MIN_SIZE") . ' KB.'
    ];

    try {
        $validation = Validator::make($request->all(), $rules, $messages);

        if ($validation->fails()) {
            return redirect()->back()
                ->withInput()
                ->withErrors($validation);
        } else {
            $update_data = $this->service->updateAll($request);

            if ($update_data['status'] == 'success') {
                return redirect('designdept/list-design-upload')->with($update_data);
            } else {
                return redirect()->back()
                    ->withInput()
                    ->with($update_data);
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
            'design_image' => 'required|mimes:pdf|max:'.Config::get("AllFileValidation.DESIGNS_PDF_MAX_SIZE").'|min:'.Config::get("AllFileValidation.DESIGNS_PDF_MIN_SIZE").'',
            'bom_image' => 'required|mimes:xls,xlsx|max:'.Config::get("AllFileValidation.DESIGNS_IMAGE_MAX_SIZE").'|min:'.Config::get("AllFileValidation.DESIGNS_IMAGE_MIN_SIZE").'',
            
         ];

         $messages = [
            'design_image.required' => 'The design file is required.',
            'design_image.mimes' => 'The design file must be in PDF format.',
            'design_image.max' => 'The design file size must not exceed'.Config::get("AllFileValidation.DESIGNS_PDF_MAX_SIZE").'KB .',
            'design_image.min' => 'The design file size must not be less than'.Config::get("AllFileValidation.DESIGNS_PDF_MIN_SIZE").'KB .',
            'bom_image.required' => 'The BOM file is required.',
            'bom_image.mimes' => 'The BOM file must be in XLS or XLSX format.',
            'bom_image.max' => 'The BOM file size must not exceed'.Config::get("AllFileValidation.DESIGNS_IMAGE_MAX_SIZE").'KB .',
            'bom_image.min' => 'The BOM file size must not be less than'.Config::get("AllFileValidation.DESIGNS_IMAGE_MIN_SIZE").'KB .',
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
                         return redirect('designdept/list-design-upload')->with(compact('msg', 'status'));
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