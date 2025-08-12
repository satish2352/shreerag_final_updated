<?php

namespace App\Http\Controllers\Organizations\Estimation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Organizations\Estimation\EstimationServices;
use App\Http\Controllers\Organizations\Estimation\AllListController;
use Illuminate\Validation\Rule;
use Session;
use Validator;
use Config;
use Carbon;
use App\Models\ {
    PartItem,
    UnitMaster,
    BusinessDetails,
    BusinessApplicationProcesses
}
;

class EstimationController extends Controller
 {
    private $listapi;
    public function __construct( AllListController $listapi ) {
        $this->service = new EstimationServices();
        $this->listapiservice = new AllListController();
    }
    public function editEstimation($id){ //checked
    try {
        $addData = base64_decode($id);
        $business_details_data = BusinessDetails::findOrFail($addData);
        return view('organizations.estimation.estimation-upload.edit-estimation-upload', [
            'addData' => $addData,
            'business_details_data' => $business_details_data
        ]);
    } catch (\Exception $e) {
        return redirect()->back()->withErrors(['msg' => $e->getMessage()]);
    }
    }
    public function updateEstimation(Request $request){ //checked
        $rules = [
            'bom_image' => 'required|mimes:xls,xlsx|max:' . Config::get("AllFileValidation.DESIGNS_IMAGE_MAX_SIZE") . '|min:' . Config::get("AllFileValidation.DESIGNS_IMAGE_MIN_SIZE"),
            'total_estimation_amount' => 'required|',
        ];
        $messages = [
            'bom_image.required' => 'The bill of material Excel sheet is required.',
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
                $update_data = $this->service->updateAll($request);
                if ($update_data['status'] == 'success') {
                    return redirect('estimationdept/list-updated-estimation-send-to-owner')->with($update_data);
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
    public function editRevisedEstimation($id){ //checked
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
                'businesses_details.product_name',
                'businesses_details.quantity',
                'businesses_details.description',
                'designs.bom_image',
                'designs.design_image',
                'estimation.total_estimation_amount'
            )
            ->first(); 

          
        if (!$business_details_data) {
            return redirect()->back()->withErrors(['msg' => 'No matching business details found.']);
        }

        return view('organizations.estimation.estimation-upload.edit-revised-estimation-upload', [
            'addData' => $addData,
            'business_details_data' => $business_details_data
        ]);
    } catch (\Exception $e) {
        return redirect()->back()->withErrors(['msg' => $e->getMessage()]);
    }
}


    public function updateRevisedEstimation(Request $request){ //checked
   
    $rules = [
        'bom_image' => 'required|mimes:xls,xlsx|max:' . Config::get("AllFileValidation.DESIGNS_IMAGE_MAX_SIZE") . '|min:' . Config::get("AllFileValidation.DESIGNS_IMAGE_MIN_SIZE"),
         'total_estimation_amount' => 'required|',
    ];

    $messages = [
        'bom_image.required' => 'The bill of material Excel sheet is required.',
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
                return redirect('estimationdept/list-new-requirements-received-for-estimation')->with($update_data);
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

     public function sendToProduction($id){ //checked
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
                'msg' => $e->getMessage(),
                'status' => 'error',
            ]);
    }
}



    public function acceptdesign( $id ) {
        try {
            $acceptdesign = base64_decode( $id );

            $update_data = $this->service->acceptdesign( $acceptdesign );
            return redirect( 'proddept/list-accept-design' );
        } catch ( \Exception $e ) {
            return $e;
        }
    }

    public function rejectdesignedit( $idtoedit ) {
        try {

            return view( 'organizations.productions.product.reject-design', compact( 'idtoedit' ) );
        } catch ( \Exception $e ) {
            return $e;
        }
    }

    public function rejectdesign( Request $request ) {
        try {
            $update_data = $this->service->rejectdesign( $request );
            return redirect( 'proddept/list-reject-design' );
        } catch ( \Exception $e ) {
            return $e;
        }
    }

    public function editProductQuantityTracking( $id ) {
        try {
            $editData = $this->service->editProductQuantityTracking( $id );
            $dataOutputPartItem = PartItem::where( 'is_active', true )->get();

            return view( 'organizations.productions.product.edit-recived-bussinesswise-quantity-tracking', [
                'productDetails' => $editData[ 'productDetails' ],
                'dataGroupedById' => $editData[ 'dataGroupedById' ],
                'dataOutputPartItem' => $dataOutputPartItem,
                'id' => $id
            ] );
        } catch ( \Exception $e ) {
            return $e;
        }
    }

    public function acceptProductionCompleted( Request $request, $id )
 {
        try {
            // Get the completed quantity from the form request
            $completed_quantity = $request->input( 'completed_quantity' );

            // Call the service layer with both $id and $completed_quantity
            $update_data = $this->service->acceptProductionCompleted( $id, $completed_quantity );

            return redirect( 'proddept/list-final-production-completed' )->with( 'update_data', $update_data );

        } catch ( \Exception $e ) {
            return redirect()->back()->with( 'error', 'An error occurred: ' . $e->getMessage() );
        }
    }
    public function editProduct($id) {
        try {
            $editData = $this->service->editProduct($id);
            $dataOutputPartItem = PartItem::where('is_active', true)->get();
            // $dataOutputUser = User::where('is_active', true)->get();
            $dataOutputUnitMaster = UnitMaster::where('is_active', true)->get();
            return view('organizations.productions.product.edit-recived-inprocess-production-material', [
                'productDetails' => $editData['productDetails'],
                'dataGroupedById' => $editData['dataGroupedById'],
                'dataOutputPartItem' => $dataOutputPartItem,
                'dataOutputUnitMaster'=>$dataOutputUnitMaster,
                // 'dataOutputUser'=>$dataOutputUser,
                'id' => $id
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with(['status' => 'error', 'msg' => $e->getMessage()]);
        }
    }
    public function updateProductMaterial(Request $request) {
        $rules = [
        ];
        
        $messages = [
        ];
        
        $validation = Validator::make($request->all(), $rules, $messages);
        
        if ($validation->fails()) {
            return redirect()->back()->withInput()->withErrors($validation);
        }
    
        try {
            $updateData = $this->service->updateProductMaterial($request);
    
            if ($updateData['status'] == 'success') {
                return redirect('proddept/list-material-recived')->with(['status' => 'success', 'msg' => $updateData['message']]);
            } else {
                return redirect()->back()->withInput()->with(['status' => 'error', 'msg' => $updateData['message']]);
            }
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with(['status' => 'error', 'msg' => $e->getMessage()]);
        }
    }
    public function destroyAddmoreStoreItem( Request $request )
    {
   
           $delete_data_id = $request->delete_id;
           // Get the delete ID from the request
   
           try {
               $delete_record = $this->service->destroyAddmoreStoreItem( $delete_data_id );
               if ( $delete_record ) {
                   $msg = $delete_record[ 'msg' ];
                   $status = $delete_record[ 'status' ];
                   if ( $status == 'success' ) {
                       return redirect( 'proddept/list-material-recived' )->with( compact( 'msg', 'status' ) );
                   } else {
                       return redirect()->back()->withInput()->with( compact( 'msg', 'status' ) );
                   }
               }
           } catch ( \Exception $e ) {
               return $e;
           }
       }
}