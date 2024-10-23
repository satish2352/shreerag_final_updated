<?php

namespace App\Http\Controllers\Organizations\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Organizations\Store\StoreServices;
use Session;
use Validator;
use Config;
use Carbon;
use App\Models\ {
    PartItem,
    User

};


class StoreController extends Controller
{
    public function __construct()
    {
        $this->service = new StoreServices();
    }



    public function orderAcceptedAndMaterialForwareded($id)
    {
        try {
            $acceptdesign = $id;
            $update_data = $this->service->orderAcceptedAndMaterialForwareded($acceptdesign);
            return redirect('storedept/list-accepted-design-from-prod');
        } catch (\Exception $e) {
            return $e;
        }
    }


    public function createRequesition($createRequesition)
    {
        try {
            return view('organizations.store.requistion.add-requistion', compact('createRequesition'));
        } catch (\Exception $e) {
            return $e;
        }
    }



    public function storeRequesition(Request $request)
    {
        // $rules = [
        //     'production_id' => 'required',
        //     // 'bom_file_req' => 'required|image|mimes:jpeg,png,jpg|',
        // ];

        // $rules['bom_file_req'] = 'required|image|mimes:xls,xlsx|max:' . Config::get("AllFileValidation.REQUISITION_IMAGE_MAX_SIZE") . '|min:' . Config::get("AllFileValidation.REQUISITION_IMAGE_MIN_SIZE");


        // $messages = [
        //     'production_id.required' => '',

        //     // 'bom_file_req.required' => 'The image is required.',
        //     // 'bom_file_req.mimes' => 'The image must be excel format.',
        //     // 'bom_file_req.max' => 'The image size must not exceed ' . Config::get("AllFileValidation.REQUISITION_IMAGE_MAX_SIZE") . 'KB .',
        //     // 'bom_file_req.min' => 'The image size must not be less than ' . Config::get("AllFileValidation.REQUISITION_IMAGE_MIN_SIZE") . 'KB .',
        // ];


        try {
            // $validation = Validator::make($request->all(), $rules, $messages);

            // if ($validation->fails()) {
            //     return redirect('storedept/add-requistion')
            //         ->withInput()
            //         ->withErrors($validation);
            // } else {
            $add_record = $this->service->storeRequesition($request);

            if ($add_record) {
                $msg = $add_record['msg'];
                $status = $add_record['status'];

                if ($status == 'success') {
                    return redirect('storedept/list-material-sent-to-purchase')->with(compact('msg', 'status'));
                } else {
                    return redirect('storedept/add-requistion')->withInput()->with(compact('msg', 'status'));
                }
            }
            // }
        } catch (Exception $e) {
            return redirect('storedept/add-requistion')->withInput()->with(['msg' => $e->getMessage(), 'status' => 'error']);
        }
    }



    public function genrateStoreReciptAndForwardMaterialToTheProduction($purchase_orders_id, $business_id)
    {
        try {
            $acceptdesign = base64_decode($purchase_orders_id);
            $acceptbusinessId = base64_decode($business_id);
            $update_data = $this->service->genrateStoreReciptAndForwardMaterialToTheProduction($acceptdesign, $acceptbusinessId);
            return redirect('storedept/list-accepted-design-from-prod');
        } catch (\Exception $e) {
            return $e;
        }
    }


    // public function editProduct($id) {
    //     try {
    //         $editData = $this->service->editProduct($id);
          
    //         $dataOutputPartItem = PartItem::where('is_active', true)->get();
    //         return view('organizations.store.list.edit-recived-inprocess-production-material', compact('editData', 'dataOutputPartItem'));
    //     } catch (\Exception $e) {
    //         return redirect()->back()->with(['status' => 'error', 'msg' => $e->getMessage()]);
    //     }
    // }
    // public function editProduct($id) {
    //     try {
    //         $editData = $this->service->editProduct($id);
    //         $dataOutputPartItem = PartItem::where('is_active', true)->get();
    //         return view('organizations.store.list.edit-recived-inprocess-production-material', compact('editData', 'dataOutputPartItem', 'id'));
    //     } catch (\Exception $e) {
    //         return redirect()->back()->with(['status' => 'error', 'msg' => $e->getMessage()]);
    //     }
    // }
    public function editProductMaterialWiseAdd($id) {
        try {
            $editData = $this->service->editProductMaterialWiseAdd($id);
            $dataOutputPartItem = PartItem::where('is_active', true)->get();
            
            return view('organizations.store.list.edit-material-bom-wise-add', [
                'productDetails' => $editData['productDetails'],
                'dataGroupedById' => $editData['dataGroupedById'],
                'dataOutputPartItem' => $dataOutputPartItem,
                'id' => $id
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with(['status' => 'error', 'msg' => $e->getMessage()]);
        }
    }
    public function updateProductMaterialWiseAdd(Request $request) {
        $rules = [
        ];
        
        $messages = [
        ];
        
        $validation = Validator::make($request->all(), $rules, $messages);
        
        if ($validation->fails()) {
            return redirect()->back()->withInput()->withErrors($validation);
        }
    
        try {
            // $completed_quantity = $request->input('completed_quantity'); // Assuming it is passed from form
             // Ensure $completed_quantity has a valid value
        // if (!$completed_quantity) {
        //     throw new \Exception('Completed quantity is not provided or calculated.');
        // }

            $updateData = $this->service->updateProductMaterialWiseAdd($request);
    
            if ($updateData['status'] == 'success') {
                return redirect('storedept/list-accepted-design-from-prod')->with(['status' => 'success', 'msg' => $updateData['message']]);
            } else {
                return redirect()->back()->withInput()->with(['status' => 'error', 'msg' => $updateData['message']]);
            }
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with(['status' => 'error', 'msg' => $e->getMessage()]);
        }
    }
    
    public function editProduct($id) {
        try {
            $editData = $this->service->editProduct($id);
            $dataOutputPartItem = PartItem::where('is_active', true)->get();
            $dataOutputUser = User::where('is_active', true)->get();
            return view('organizations.store.list.edit-recived-inprocess-production-material', [
                'productDetails' => $editData['productDetails'],
                'dataGroupedById' => $editData['dataGroupedById'],
                'dataOutputPartItem' => $dataOutputPartItem,
                // 'dataOutputUser'=>$dataOutputUser,
                'id' => $id
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with(['status' => 'error', 'msg' => $e->getMessage()]);
        }
    }
    
    // public function updateProductMaterial(Request $request) {
    //     $rules = [
    //         // 'business_details_id' => 'required|exists:business_details,id',
    //         // 'addmore.*.part_no_id' => 'required|exists:parts,id',
    //         // 'addmore.*.quantity' => 'required|numeric|min:1',
    //         // 'addmore.*.unit' => 'required|string',
    //     ];
    
    //     $messages = [
    //         // 'business_details_id.required' => 'The business details ID is required.',
    //         // 'business_details_id.exists' => 'The business details ID does not exist.',
    //         // 'addmore.*.part_no_id.required' => 'Each item must have a part number.',
    //         // 'addmore.*.part_no_id.exists' => 'The part number does not exist.',
    //         // 'addmore.*.quantity.required' => 'Each item must have a quantity.',
    //         // 'addmore.*.quantity.numeric' => 'The quantity must be a number.',
    //         // 'addmore.*.quantity.min' => 'The quantity must be at least 1.',
    //         // 'addmore.*.unit.required' => 'Each item must have a unit.',
    //         // 'addmore.*.unit.string' => 'The unit must be a string.',
    //     ];
    
    //     $validation = Validator::make($request->all(), $rules, $messages);
    
    //     if ($validation->fails()) {
    //         return redirect()->back()->withInput()->withErrors($validation);
    //     }
    
    //     try {
    //         $updateData = $this->service->updateProductMaterial($request);
    
    //         if ($updateData['status'] == 'success') {
    //             return redirect('storedept/list-product-inprocess-received-from-production')->with(['status' => 'success', 'msg' => $updateData['message']]);
    //         } else {
    //             return redirect()->back()->withInput()->with(['status' => 'error', 'msg' => $updateData['message']]);
    //         }
    //     } catch (\Exception $e) {
    //         return redirect()->back()->withInput()->with(['status' => 'error', 'msg' => $e->getMessage()]);
    //     }
    // }
    
    public function updateProductMaterial(Request $request) {
        $rules = [
            // 'business_details_id' => 'required|exists:business_details,id',
            // 'addmore.*.part_no_id' => 'required|exists:parts,id',
            // 'addmore.*.quantity' => 'required|numeric|min:1',
            // 'addmore.*.unit' => 'required|string',
        ];
        
        $messages = [
            // 'business_details_id.required' => 'The business details ID is required.',
            // 'business_details_id.exists' => 'The business details ID does not exist.',
            // 'addmore.*.part_no_id.required' => 'Each item must have a part number.',
            // 'addmore.*.part_no_id.exists' => 'The part number does not exist.',
            // 'addmore.*.quantity.required' => 'Each item must have a quantity.',
            // 'addmore.*.quantity.numeric' => 'The quantity must be a number.',
            // 'addmore.*.quantity.min' => 'The quantity must be at least 1.',
            // 'addmore.*.unit.required' => 'Each item must have a unit.',
            // 'addmore.*.unit.string' => 'The unit must be a string.',
        ];
        
        $validation = Validator::make($request->all(), $rules, $messages);
        
        if ($validation->fails()) {
            return redirect()->back()->withInput()->withErrors($validation);
        }
    
        try {
            $updateData = $this->service->updateProductMaterial($request);
    
            if ($updateData['status'] == 'success') {
                return redirect('storedept/list-product-inprocess-received-from-production')->with(['status' => 'success', 'msg' => $updateData['message']]);
            } else {
                return redirect()->back()->withInput()->with(['status' => 'error', 'msg' => $updateData['message']]);
            }
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with(['status' => 'error', 'msg' => $e->getMessage()]);
        }
    }
    
}
