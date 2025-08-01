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
    AdminView,
    NotificationStatus
};
class DesignUploadController extends Controller
{ 
    public function __construct(){
        $this->service = new DesignsServices();
    }
    
    public function getAllNewRequirement(Request $request){
        try {
            $data_output = $this->service->getAllNewRequirement();

            $first_business_id = optional($data_output->first())->id;

            if ($first_business_id) {
            $update_data['design_is_view'] = '1';
            NotificationStatus::where('design_is_view', '0')
                ->where('business_id', $first_business_id) 
                ->update($update_data);
        }
            return view('organizations.designer.design-upload.list-new-requirements-received-for-design', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    } 

    public function getAllNewRequirementBusinessWise($id){
        try {
            $data_output = $this->service->getAllNewRequirementBusinessWise($id);     
            return view('organizations.designer.design-upload.list-new-requirements-received-for-design-businesswise', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    } 
public function index() {
    try {
        $data_output = $this->service->getAll();
        return view('organizations.designer.design-upload.list-design-upload', compact('data_output'));
    } catch (\Exception $e) {
        \Log::error('Controller index() error: ' . $e->getMessage());
        return back()->with('status', 'error')->with('msg', 'Something went wrong while fetching data.');
    }
}

    
    // public function index(){
    //     try {
    //         $data_output = $this->service->getAll();
    //         return view('organizations.designer.design-upload.list-design-upload', compact('data_output'));
    //     } catch (\Exception $e) {
    //         return $e;
    //     }
    // }     
    public function add($id)
    {
        try {
            $addData = base64_decode($id);
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
            // 'bom_image' => 'required|mimes:xls,xlsx|max:'.Config::get("AllFileValidation.DESIGNS_IMAGE_MAX_SIZE").'|min:'.Config::get("AllFileValidation.DESIGNS_IMAGE_MIN_SIZE").'',
            
         ];

         $messages = [
            'design_image.required' => 'The design file is required.',
            'design_image.mimes' => 'The design file must be in PDF format.',
            'design_image.max' => 'The design file size must not exceed'.Config::get("AllFileValidation.DESIGNS_PDF_MAX_SIZE").'KB .',
            'design_image.min' => 'The design file size must not be less than'.Config::get("AllFileValidation.DESIGNS_PDF_MIN_SIZE").'KB .',
            // 'bom_image.required' => 'The BOM file is required.',
            // 'bom_image.mimes' => 'The BOM file must be in XLS or XLSX format.',
            // 'bom_image.max' => 'The BOM file size must not exceed'.Config::get("AllFileValidation.DESIGNS_IMAGE_MAX_SIZE").'KB .',
            // 'bom_image.min' => 'The BOM file size must not be less than'.Config::get("AllFileValidation.DESIGNS_IMAGE_MIN_SIZE").'KB .',
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