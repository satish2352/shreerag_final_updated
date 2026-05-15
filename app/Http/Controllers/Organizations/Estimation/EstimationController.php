<?php

namespace App\Http\Controllers\Organizations\Estimation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Organizations\Estimation\EstimationServices;
use App\Http\Controllers\Organizations\Estimation\AllListController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Models\{
    PartItem,
    UnitMaster,
    BusinessDetails,
    BusinessApplicationProcesses,
    EstimationModel,
    BomMaterialItem
};
use App\Support\BomTotalCalculator;

class EstimationController extends Controller
{
    private $service;
    private $listapiservice;
    public function __construct(AllListController $listapi)
    {
        $this->service = new EstimationServices();
        $this->listapiservice = new AllListController();
    }
    public function editEstimation($id)
    { //checked
        try {
            $addData = base64_decode($id);
            $business_details_data = BusinessDetails::findOrFail($addData);
            // Fetch estimation to detect owner-suggested-amount (BAP status 1301)
            $estimation_data = EstimationModel::where('business_details_id', $addData)
                ->where('is_deleted', 0)
                ->first();

            // T-2026-046: Compute BOM Final Total using the unit-aware formula that
            // mirrors the JS modal logic:
            //   piece units (NOS/PCS/SET/EACH) → rate × quantity × trolley_qty
            //   length units (MTR/METER/etc.)   → rate × mtr_for_01_nos_trolley × trolley_qty
            // Previously used the naive rate × quantity (T-2026-010) which ignored
            // trolley_qty and the unit-aware multiplier — causing the Total Estimation
            // Amount readonly field to show the wrong number.
            $bom_final_total = 0.0;
            if ($estimation_data && $estimation_data->design_id) {
                $activeItems = BomMaterialItem::where('business_details_id', (int) $addData)
                    ->where('design_id', (int) $estimation_data->design_id)
                    ->where('is_deleted', 0)
                    ->get();
                // Look up trolley_qty from designs table; fall back to 1 if missing.
                $trolleyQty = (int) (DB::table('designs')
                    ->where('id', (int) $estimation_data->design_id)
                    ->value('trolley_qty') ?? 1);
                if ($trolleyQty < 1) {
                    $trolleyQty = 1;
                }
                $bom_final_total = BomTotalCalculator::finalTotal($activeItems, $trolleyQty);
            }

            return view('organizations.estimation.estimation-upload.edit-estimation-upload', [
                'addData'               => $addData,
                'business_details_data' => $business_details_data,
                'estimation_data'       => $estimation_data,
                'bom_final_total'       => $bom_final_total,
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['msg' => 'Something went wrong. Please try again.']);
        }
    }
    public function checkEstimationAmount(Request $request)
    {
        $businessDetails = BusinessDetails::find($request->business_id);

        if (!$businessDetails) {
            return response()->json([
                'status' => 'error',
                'message' => 'Business details not found',
            ]);
        }

        if ($request->total_estimation_amount > $businessDetails->total_amount) {
            // Return 'warning' — form submission is still allowed; the controller
            // will route to the exceed-approval flow instead of hard-blocking.
            return response()->json([
                'status' => 'warning',
                'message' => 'Estimation amount exceeds Business Total Amount (₹'
                    . number_format($businessDetails->total_amount, 2) . '). '
                    . 'You may still submit — the owner will be asked to approve or suggest a revised amount.',
            ]);
        }

        return response()->json(['status' => 'success']);
    }

    /*
     * EXCEED-AMOUNT FLOW (added 2026-04-25):
     * When total_estimation_amount > businessDetails.total_amount:
     *   → Saves the exceeded amount, flags estimation.is_exceed_pending = 1,
     *     sets BAP.bom_estimation_send_to_owner = 1300, off_canvas = 50,
     *     and notifies the owner to review.
     * When within limit:
     *   → Normal flow unchanged: BAP.bom_estimation_send_to_owner = 1149, off_canvas = 28.
     * See EstimationRepository::updateEstimationExceed() and updateAll() for DB logic.
     */
    public function updateEstimation(Request $request)
    { //checked
        $rules = [
            // T-2026-003: changed from required to nullable — BOM Excel field is hidden in the form.
            'bom_image' => 'nullable|mimes:xls,xlsx|max:' . Config::get("AllFileValidation.DESIGNS_IMAGE_MAX_SIZE") . '|min:' . Config::get("AllFileValidation.DESIGNS_IMAGE_MIN_SIZE"),
            'total_estimation_amount' => 'required|',
        ];
        $messages = [
            // T-2026-003: bom_image messages retained for nullable case (if a file is somehow sent).
            'bom_image.mimes' => 'The bill of material must be in XLS or XLSX format.',
            'bom_image.max' => 'The bill of material size must not exceed ' . Config::get("AllFileValidation.DESIGNS_IMAGE_MAX_SIZE") . ' KB.',
            'bom_image.min' => 'The bill of material size must not be less than ' . Config::get("AllFileValidation.DESIGNS_IMAGE_MIN_SIZE") . ' KB.',
            'total_estimation_amount.required' => 'Enter the Total Estimation Amount',
        ];

        try {
            $validation = Validator::make($request->all(), $rules, $messages);

            if ($validation->fails()) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors($validation);
            }

            $businessDetails = BusinessDetails::find($request->business_id);

            if (!$businessDetails) {
                return redirect()->back()
                    ->withInput()
                    ->with(['status' => 'error', 'msg' => 'Business details not found.']);
            }

            if ($request->total_estimation_amount > $businessDetails->total_amount) {
                // Exceed-amount path: save and route to owner approval workflow.
                $update_data = $this->service->updateEstimationExceed($request);
                if ($update_data['status'] == 'success') {
                    return redirect('estimationdept/list-updated-estimation-send-to-owner')->with($update_data);
                } else {
                    return redirect()->back()->withInput()->with($update_data);
                }
            }

            // Within-limit path: existing flow unchanged.
            $update_data = $this->service->updateAll($request);
            if ($update_data['status'] == 'success') {
                return redirect('estimationdept/list-updated-estimation-send-to-owner')->with($update_data);
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

    /**
     * Estimator accepts the owner-suggested amount and re-enters standard BOM approval flow.
     * Route: GET estimationdept/accept-owner-suggested-amount/{id}
     * $id = base64_encoded business_details_id
     */
    public function acceptOwnerSuggestedAmount($id)
    {
        try {
            $business_details_id = base64_decode($id);
            $update_data = $this->service->acceptOwnerSuggestedAmount($business_details_id);
            if ($update_data['status'] == 'success') {
                return redirect()->route('list-bom-exceed-owner-suggested')->with($update_data);
            } else {
                return redirect()->back()->withInput()->with($update_data);
            }
        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with(['msg' => 'Something went wrong. Please try again.', 'status' => 'error']);
        }
    }
    public function editRevisedEstimation($id)
    { //checked
        try {
            $addData = base64_decode($id);

            $business_details_data = BusinessApplicationProcesses::leftJoin('businesses_details', function ($join) {
                $join->on('business_application_processes.business_details_id', '=', 'businesses_details.id');
            })
                ->leftJoin('designs', function ($join) {
                    $join->on('business_application_processes.business_details_id', '=', 'designs.business_details_id');
                })
                ->leftJoin('estimation', function ($join) {
                    $join->on('business_application_processes.business_details_id', '=', 'estimation.business_details_id');
                })
                ->where('business_application_processes.business_details_id', $addData)
                ->select(
                    'businesses_details.id',
                    \DB::raw('businesses_details.id as business_details_id'),
                    'businesses_details.product_name',
                    'businesses_details.quantity',
                    'businesses_details.description',
                    'designs.bom_image',
                    'designs.design_image',
                    'estimation.id as estimation_id',
                    'estimation.design_id',
                    'estimation.business_id',
                    'estimation.business_details_id as estimation_business_details_id',
                    'estimation.total_estimation_amount'
                )
                ->first();


            if (!$business_details_data) {
                return redirect()->back()->withErrors(['msg' => 'No matching business details found.']);
            }

            // T-2026-046: Compute BOM Final Total using the unit-aware formula
            // (same fix as editEstimation — previously T-2026-011 used naive rate × quantity).
            $bom_final_total = 0.0;
            if ($business_details_data->design_id) {
                $activeItems = BomMaterialItem::where('business_details_id', (int) $addData)
                    ->where('design_id', (int) $business_details_data->design_id)
                    ->where('is_deleted', 0)
                    ->get();
                // Look up trolley_qty from designs table; fall back to 1 if missing.
                $trolleyQty = (int) (DB::table('designs')
                    ->where('id', (int) $business_details_data->design_id)
                    ->value('trolley_qty') ?? 1);
                if ($trolleyQty < 1) {
                    $trolleyQty = 1;
                }
                $bom_final_total = BomTotalCalculator::finalTotal($activeItems, $trolleyQty);
            }

            return view('organizations.estimation.estimation-upload.edit-revised-estimation-upload', [
                'addData'               => $addData,
                'business_details_data' => $business_details_data,
                'bom_final_total'       => $bom_final_total,
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['msg' => 'Something went wrong. Please try again.']);
        }
    }


    public function updateRevisedEstimation(Request $request)
    { //checked

        $rules = [
            // T-2026-003: changed from required to nullable — BOM Excel field is hidden in the form.
            'bom_image' => 'nullable|mimes:xls,xlsx|max:' . Config::get("AllFileValidation.DESIGNS_IMAGE_MAX_SIZE") . '|min:' . Config::get("AllFileValidation.DESIGNS_IMAGE_MIN_SIZE"),
            'total_estimation_amount' => 'required|',
        ];

        $messages = [
            // T-2026-003: bom_image messages retained for nullable case (if a file is somehow sent).
            'bom_image.mimes' => 'The bill of material must be in XLS or XLSX format.',
            'bom_image.max' => 'The bill of material size must not exceed ' . Config::get("AllFileValidation.DESIGNS_IMAGE_MAX_SIZE") . ' KB.',
            'bom_image.min' => 'The bill of material size must not be less than ' . Config::get("AllFileValidation.DESIGNS_IMAGE_MIN_SIZE") . ' KB.',
            'total_estimation_amount.required' => 'Enter the Total Estimation Amount',
        ];

        try {
            $validation = Validator::make($request->all(), $rules, $messages);

            if ($validation->fails()) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors($validation);
            } else {
                $update_data = $this->service->updateRevisedEstimation($request);
                if ($update_data['status'] == 'success') {
                    // After re-submitting a previously-rejected estimation, send the
                    // estimator to "Updated Estimation Sent to Owner" — the page that
                    // actually lists this re-submitted item awaiting owner approval.
                    return redirect()->route('list-updated-estimation-send-to-owner')->with($update_data);
                } else {
                    return redirect()->back()
                        ->withInput()
                        ->with($update_data);
                }
            }
        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with(['msg' => 'Something went wrong. Please try again.', 'status' => 'error']);
        }
    }

    public function sendToProduction($id)
    { //checked
        try {
            $id = base64_encode($id);
            $update_data = $this->service->sendToProduction($id);
            if (!empty($update_data) && isset($update_data['status']) && $update_data['status'] === 'success') {
                return redirect('estimationdept/list-send-to-production')->with($update_data);
            } else {
                return redirect()->back()
                    ->withInput()
                    ->with($update_data ?? ['status' => 'error', 'msg' => 'Unknown error occurred.']);
            }
        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with([
                    'msg' => 'Something went wrong. Please try again.',
                    'status' => 'error',
                ]);
        }
    }



    public function acceptdesign($id)
    {
        try {
            $acceptdesign = base64_decode($id);

            $update_data = $this->service->acceptdesign($acceptdesign);
            return redirect('proddept/list-accept-design');
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function rejectdesignedit($idtoedit)
    {
        try {

            return view('organizations.productions.product.reject-design', compact('idtoedit'));
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function rejectdesign(Request $request)
    {
        try {
            $update_data = $this->service->rejectdesign($request);
            return redirect('proddept/list-reject-design');
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function editProductQuantityTracking($id)
    {
        try {
            $editData = $this->service->editProductQuantityTracking($id);
            $dataOutputPartItem = PartItem::where('is_active', true)->get();

            return view('organizations.productions.product.edit-recived-bussinesswise-quantity-tracking', [
                'productDetails' => $editData['productDetails'],
                'dataGroupedById' => $editData['dataGroupedById'],
                'dataOutputPartItem' => $dataOutputPartItem,
                'id' => $id
            ]);
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function acceptProductionCompleted(Request $request, $id)
    {
        try {
            // Get the completed quantity from the form request
            $completed_quantity = $request->input('completed_quantity');

            // Call the service layer with both $id and $completed_quantity
            $update_data = $this->service->acceptProductionCompleted($id, $completed_quantity);

            return redirect('proddept/list-final-production-completed')->with('update_data', $update_data);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }
    public function editProduct($id)
    {
        try {
            $editData = $this->service->editProduct($id);
            $dataOutputPartItem = PartItem::where('is_active', true)->get();
            // $dataOutputUser = User::where('is_active', true)->get();
            $dataOutputUnitMaster = UnitMaster::where('is_active', true)->get();
            return view('organizations.productions.product.edit-recived-inprocess-production-material', [
                'productDetails' => $editData['productDetails'],
                'dataGroupedById' => $editData['dataGroupedById'],
                'dataOutputPartItem' => $dataOutputPartItem,
                'dataOutputUnitMaster' => $dataOutputUnitMaster,
                // 'dataOutputUser'=>$dataOutputUser,
                'id' => $id
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with(['status' => 'error', 'msg' => 'Something went wrong. Please try again.']);
        }
    }
    public function updateProductMaterial(Request $request)
    {
        $rules = [];

        $messages = [];

        $validation = Validator::make($request->all(), $rules, $messages);

        if ($validation->fails()) {
            return redirect()->back()->withInput()->withErrors($validation);
        }

        try {
            $updateData = $this->service->updateProductMaterial($request);

            if ($updateData['status'] == 'success') {
                return redirect('proddept/list-material-received')->with(['status' => 'success', 'msg' => $updateData['message']]);
            } else {
                return redirect()->back()->withInput()->with(['status' => 'error', 'msg' => $updateData['message']]);
            }
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with(['status' => 'error', 'msg' => 'Something went wrong. Please try again.']);
        }
    }
    public function destroyAddmoreStoreItem(Request $request)
    {

        $delete_data_id = $request->delete_id;
        // Get the delete ID from the request

        try {
            $delete_record = $this->service->destroyAddmoreStoreItem($delete_data_id);
            if ($delete_record) {
                $msg = $delete_record['msg'];
                $status = $delete_record['status'];
                if ($status == 'success') {
                    return redirect('proddept/list-material-received')->with(compact('msg', 'status'));
                } else {
                    return redirect()->back()->withInput()->with(compact('msg', 'status'));
                }
            }
        } catch (\Exception $e) {
            return $e;
        }
    }
}
