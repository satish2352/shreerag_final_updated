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
    User,
    UnitMaster,
    ItemStock

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
               try {
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
    public function editProductMaterialWiseAddNewReq($id) {
        try {
            $id = $id;
            $editData = $this->service->editProductMaterialWiseAddNewReq($id);
            $dataOutputPartItem = PartItem::where('is_active', true)->get();
            $dataOutputUnitMaster = UnitMaster::where('is_active', true)->get();
            return view('organizations.store.list.edit-material-bom-wise-add-new-req', [
                'productDetails' => $editData['productDetails'],
                'dataGroupedById' => $editData['dataGroupedById'],
                'dataOutputPartItem' => $dataOutputPartItem,
                'dataOutputUnitMaster'=>$dataOutputUnitMaster,
              
                'id' => $id
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with(['status' => 'error', 'msg' => $e->getMessage()]);
        }
    }

    // public function checkStockQuantity(Request $request)
    // {
    //     $partItemId = $request->input('part_item_id');
   
    //     $quantity = $request->input('quantity');
    
    //     // Fetch part item from the database
    //     $partItem = ItemStock::find($partItemId);
    //     dd($partItem);
    //     die();
    //     if (!$partItem) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Part Item not found'
    //         ]);
    //     }
    
    //     // Get the available quantity for the part item
    //     $availableQuantity = $partItem->quantity; // Assuming the field is named `quantity`
    
    //     if ($availableQuantity === null) {
    //         return response()->json([
    //             'status' => 'error',
    //             'available_quantity' => 0
    //         ]);
    //     }
    
    //     if ($quantity > $availableQuantity) {
    //         // If the requested quantity exceeds the available stock
    //         return response()->json([
    //             'status' => 'error',
    //             'available_quantity' => $availableQuantity
    //         ]);
    //     } else {
    //         // If stock is sufficient
    //         return response()->json([
    //             'status' => 'success',
    //             'available_quantity' => $availableQuantity
    //         ]);
    //     }
    // }
    public function checkStockQuantity(Request $request)
{
    try {
        $partItemId = $request->input('part_item_id');
        $quantity = $request->input('quantity');

        // Log incoming values for debugging
        \Log::info('Checking stock quantity', ['part_item_id' => $partItemId, 'quantity' => $quantity]);

        // Validate inputs
        if (!$partItemId || !$quantity) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid inputs. Please provide both part_item_id and quantity.',
            ], 400);
        }

        // Fetch part item from the database
        $partItem = ItemStock::find($partItemId);
// dd($partItem);
// die();
        if (!$partItem) {
            return response()->json([
                'status' => 'error',
                'message' => 'Part Item not found',
            ], 404);
        }

        // Get the available quantity
        $availableQuantity = $partItem->quantity ?? 0; // Ensure it's not null

        if ($quantity > $availableQuantity) {
            return response()->json([
                'status' => 'error',
                'available_quantity' => $availableQuantity,
                'message' => 'Insufficient stock',
            ]);
        }

        // Sufficient stock
        return response()->json([
            'status' => 'success',
            'available_quantity' => $availableQuantity,
        ]);
    } catch (\Exception $e) {
        \Log::error('Error in checkStockQuantity:', ['error' => $e->getMessage()]);
        return response()->json([
            'status' => 'error',
            'message' => 'Internal Server Error',
        ], 500);
    }
}

    
    public function updateProductMaterialWiseAddNewReq(Request $request) {
        $rules = [
        ];
        
        $messages = [
        ];
        
        $validation = Validator::make($request->all(), $rules, $messages);
        
        if ($validation->fails()) {
            return redirect()->back()->withInput()->withErrors($validation);
        }
    
        try {
                       $updateData = $this->service->updateProductMaterialWiseAddNewReq($request);
    
            if ($updateData['status'] == 'success') {
                return redirect('storedept/list-accepted-design-from-prod')->with(['status' => 'success', 'msg' => $updateData['message']]);
            } else {
                return redirect()->back()->withInput()->with(['status' => 'error', 'msg' => $updateData['message']]);
            }
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with(['status' => 'error', 'msg' => $e->getMessage()]);
        }
    }
   
    public function editProductMaterialWiseAdd($purchase_orders_id, $business_id) {
        try {
            $purchase_orders_id = base64_decode($purchase_orders_id);
            
            $business_id = base64_decode($business_id);
            // dd($business_id);
            // die();
            $editData = $this->service->editProductMaterialWiseAdd($purchase_orders_id, $business_id);
           
            // dd($editData);
            // die();
            $dataOutputPartItem = PartItem::where('is_active', true)->get();
            $dataOutputUnitMaster = UnitMaster::where('is_active', true)->get();
            return view('organizations.store.list.edit-material-bom-wise-add', [
                'productDetails' => $editData['productDetails'],
                'dataGroupedById' => $editData['dataGroupedById'],
                'dataOutputPartItem' => $dataOutputPartItem,
                'dataOutputUnitMaster'=>$dataOutputUnitMaster,
                'purchase_orders_id' => $purchase_orders_id,
                'business_id' => $business_id
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
            // $dataOutputUser = User::where('is_active', true)->get();
            $dataOutputUnitMaster = UnitMaster::where('is_active', true)->get();
            return view('organizations.store.list.edit-recived-inprocess-production-material', [
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
                return redirect('storedept/list-product-inprocess-received-from-production')->with(['status' => 'success', 'msg' => $updateData['message']]);
            } else {
                return redirect()->back()->withInput()->with(['status' => 'error', 'msg' => $updateData['message']]);
            }
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with(['status' => 'error', 'msg' => $e->getMessage()]);
        }
    }
    
}
