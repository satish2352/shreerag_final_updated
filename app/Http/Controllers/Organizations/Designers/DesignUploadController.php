<?php

namespace App\Http\Controllers\Organizations\Designers;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Organizations\Designers\DesignsServices;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use Exception;
use App\Models\{
    BusinessDetails,
    NotificationStatus,
    DesignModel,
    BomMaterialItem
};

class DesignUploadController extends Controller
{
    protected $service;

    public function __construct()
    {
        $this->service = new DesignsServices();
    }
    public function getAllNewRequirement(Request $request)
    { //checked
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
    public function getAllNewRequirementBusinessWise($id)
    { //checked
        try {
            $data_output = $this->service->getAllNewRequirementBusinessWise($id);
            return view('organizations.designer.design-upload.list-new-requirements-received-for-design-businesswise', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function add($id)
    { //checked
        try {
            $addData = base64_decode($id);
            $business_details_data = BusinessDetails::findOrFail($addData);
            $design_data = DesignModel::where('business_details_id', $addData)->first();

            // T-2026-007: count persisted BOM items for frontend hasBomItems flag
            $bom_items_count = 0;
            if ($design_data) {
                $bom_items_count = BomMaterialItem::where('business_details_id', $design_data->business_details_id)
                    ->where('design_id', $design_data->id)
                    ->where('is_deleted', 0)
                    ->count();
            }

            return view('organizations.designer.design-upload.add-design-upload', [
                'addData'              => $addData,
                'business_details_data' => $business_details_data,
                'design_data'          => $design_data,
                'bom_items_count'      => $bom_items_count,
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['msg' => 'Something went wrong. Please try again.']);
        }
    }
    public function update(Request $request)
    { //checked
        $rules = [
            'design_image' => 'required|mimes:pdf|max:' . Config::get("AllFileValidation.DESIGNS_PDF_MAX_SIZE") . '|min:' . Config::get("AllFileValidation.DESIGNS_PDF_MIN_SIZE"),
            // T-2026-003: changed from required to nullable — BOM Excel field is hidden in the form.
            'bom_image' => 'nullable|mimes:xls,xlsx|max:' . Config::get("AllFileValidation.DESIGNS_IMAGE_MAX_SIZE") . '|min:' . Config::get("AllFileValidation.DESIGNS_IMAGE_MIN_SIZE")
        ];
        $messages = [
            'design_image.required' => 'The design PDF is required.',
            'design_image.mimes' => 'The design PDF must be in PDF format.',
            'design_image.max' => 'The design PDF size must not exceed ' . Config::get("AllFileValidation.DESIGNS_PDF_MAX_SIZE") . ' KB.',
            'design_image.min' => 'The design PDF size must not be less than ' . Config::get("AllFileValidation.DESIGNS_PDF_MIN_SIZE") . ' KB.',
            // T-2026-003: bom_image messages retained for the nullable case (if a file is somehow sent).
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
            }

            // T-2026-007: Backend BOM-empty guard — must have at least one persisted BOM item
            // before the design can be sent to the Estimation Department.
            $businessDetailsId = $request->input('business_id'); // form posts business_id = businesses_details.id
            $design = DesignModel::where('business_details_id', $businessDetailsId)->first();
            $bomCount = 0;
            if ($design) {
                $bomCount = BomMaterialItem::where('business_details_id', $design->business_details_id)
                    ->where('design_id', $design->id)
                    ->where('is_deleted', 0)
                    ->count();
            }
            if ($bomCount === 0) {
                return redirect()->back()
                    ->withInput()
                    ->with([
                        'msg'    => 'Please add at least one BOM Material Item before sending the design to the Estimation Department.',
                        'status' => 'error',
                    ]);
            }

            $update_data = $this->service->updateAll($request);
            if ($update_data['status'] == 'success') {
                return redirect('designdept/list-design-upload')->with($update_data);
            } else {
                return redirect()->back()
                    ->withInput()
                    ->with($update_data);
            }
        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with(['msg' => 'Something went wrong. Please try again.', 'status' => 'error']);
        }
    }
    public function getUploadedDesignSendEstimation()
    { //checked
        try {
            $data_output = $this->service->getUploadedDesignSendEstimation();
            return view('organizations.designer.design-upload.list-design-upload', compact('data_output'));
        } catch (\Exception $e) {
            Log::error('Controller index() error: ' . $e->getMessage());
            return back()->with('status', 'error')->with('msg', 'Something went wrong while fetching data.');
        }
    }
    public function addReUploadDesing($id)
    { //checked
        try {
            $design_revision_for_prod_id = base64_decode($id);
            // T-2026-006: load DesignRevisionForProd to pass business_details_id + design_id
            // needed by the BOM Material Items modal (design_edit mode).
            $designRevision = \App\Models\DesignRevisionForProd::where('id', $design_revision_for_prod_id)->first();
            $business_id         = $designRevision ? $designRevision->business_id : null;
            $business_details_id = $designRevision ? $designRevision->business_details_id : null;
            $design_id           = $designRevision ? $designRevision->design_id : null;

            // T-2026-007: count persisted BOM items for frontend hasBomItems flag
            $bom_items_count = 0;
            if ($business_details_id && $design_id) {
                $bom_items_count = BomMaterialItem::where('business_details_id', $business_details_id)
                    ->where('design_id', $design_id)
                    ->where('is_deleted', 0)
                    ->count();
            }

            return view('organizations.designer.design-upload.add-design-re-submit-upload', compact(
                'design_revision_for_prod_id',
                'business_id',
                'business_details_id',
                'design_id',
                'bom_items_count'
            ));
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function updateReUploadDesign(Request $request)
    { //checked

        $rules = [
            'design_image' => 'required|mimes:pdf|max:' . Config::get("AllFileValidation.DESIGNS_PDF_MAX_SIZE") . '|min:' . Config::get("AllFileValidation.DESIGNS_PDF_MIN_SIZE") . '',
            // T-2026-003: changed from required to nullable — BOM Excel field is hidden in the form.
            'bom_image' => 'nullable|mimes:xls,xlsx|max:' . Config::get("AllFileValidation.DESIGNS_IMAGE_MAX_SIZE") . '|min:' . Config::get("AllFileValidation.DESIGNS_IMAGE_MIN_SIZE") . '',

        ];

        $messages = [
            'design_image.required' => 'The design file is required.',
            'design_image.mimes' => 'The design file must be in PDF format.',
            'design_image.max' => 'The design file size must not exceed' . Config::get("AllFileValidation.DESIGNS_PDF_MAX_SIZE") . 'KB .',
            'design_image.min' => 'The design file size must not be less than' . Config::get("AllFileValidation.DESIGNS_PDF_MIN_SIZE") . 'KB .',
            // T-2026-003: bom_image messages retained for nullable case (if a file is somehow sent).
            'bom_image.mimes' => 'The BOM file must be in XLS or XLSX format.',
            'bom_image.max' => 'The BOM file size must not exceed' . Config::get("AllFileValidation.DESIGNS_IMAGE_MAX_SIZE") . 'KB .',
            'bom_image.min' => 'The BOM file size must not be less than' . Config::get("AllFileValidation.DESIGNS_IMAGE_MIN_SIZE") . 'KB .',
        ];

        try {
            $validation = Validator::make($request->all(), $rules, $messages);
            if ($validation->fails()) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors($validation);
            }

            // T-2026-007: Backend BOM-empty guard for re-submit path.
            // Resolve business_details_id + design_id via DesignRevisionForProd.
            $designRevisionId = $request->input('design_revision_for_prod_id');
            $designRevision   = \App\Models\DesignRevisionForProd::where('id', $designRevisionId)->first();
            $reBomCount       = 0;
            if ($designRevision && $designRevision->business_details_id && $designRevision->design_id) {
                $reBomCount = BomMaterialItem::where('business_details_id', $designRevision->business_details_id)
                    ->where('design_id', $designRevision->design_id)
                    ->where('is_deleted', 0)
                    ->count();
            }
            if ($reBomCount === 0) {
                return redirect()->back()
                    ->withInput()
                    ->with([
                        'msg'    => 'Please add at least one BOM Material Item before sending the design to the Estimation Department.',
                        'status' => 'error',
                    ]);
            }

            $update_data = $this->service->updateReUploadDesign($request);

            if ($update_data) {
                $msg = $update_data['msg'];
                $status = $update_data['status'];
                if ($status == 'success') {
                    // After successfully re-submitting a rejected design, send the
                    // designer to the "Updated Design" list (designdept/list-updated-design).
                    return redirect()->route('list-updated-design')->with(compact('msg', 'status'));
                } else {
                    return redirect()->back()
                        ->withInput()
                        ->with(compact('msg', 'status'));
                }
            }
        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with(['msg' => 'Something went wrong. Please try again.', 'status' => 'error']);
        }
    }
}
