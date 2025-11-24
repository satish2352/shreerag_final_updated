<?php

namespace App\Http\Controllers\Organizations\Store;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Organizations\Store\StoreServices;
use Illuminate\Support\Facades\Validator;
use Exception;
use App\Models\{
    PartItem,
    User,
    UnitMaster,
    ItemStock,
    GRNModel
};


class StoreController extends Controller
{
    protected $service;

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
    public function editProductMaterialWiseAddNewReq($id)
    { //checked
        try {
            $id = $id;
            $editData = $this->service->editProductMaterialWiseAddNewReq($id);
            $dataOutputPartItem = PartItem::where('is_active', true)->orderByRaw('LOWER(description) ASC')->get();
            $dataOutputUnitMaster = UnitMaster::where('is_active', true)->get();
            return view('organizations.store.list.edit-material-bom-wise-add-new-req', [
                'productDetails' => $editData['productDetails'],
                'dataGroupedById' => $editData['dataGroupedById'],
                'dataOutputPartItem' => $dataOutputPartItem,
                'dataOutputUnitMaster' => $dataOutputUnitMaster,

                'id' => $id
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with(['status' => 'error', 'msg' => $e->getMessage()]);
        }
    }
    public function checkStockQuantity(Request $request)
    {
        try {
            $partItemId = $request->input('part_item_id');
            $quantity = $request->input('quantity');
            $materialSendProduction = $request->input('material_send_production');
            $quantityMinusStatus = $request->input('quantity_minus_status');
            $isInsertOrUpdate = $request->input('is_insert_or_update', false); // Add a flag to check if it's a new submission

            Log::info('Checking stock quantity', [
                'part_item_id' => $partItemId,
                'quantity' => $quantity,
                'material_send_production' => $materialSendProduction,
                'quantity_minus_status' => $quantityMinusStatus,
                'is_insert_or_update' => $isInsertOrUpdate
            ]);

            // **Bypass stock validation if it's a new insert or update request**
            if ($isInsertOrUpdate) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Stock check skipped (Insert/Update Mode)',
                ]);
            }

            // If already processed, SKIP checking stock
            if ($materialSendProduction == 1 && $quantityMinusStatus == 'done') {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Stock check skipped (already processed)',
                ]);
            }

            // Validate inputs
            if (!$partItemId || !$quantity) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid inputs. Please provide both part_item_id and quantity.',
                ], 400);
            }

            // Fetch the part item stock
            $partItem = ItemStock::find($partItemId);

            if (!$partItem) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Part Item not found',
                ], 404);
            }

            // Get the available quantity
            $availableQuantity = $partItem->quantity ?? 0;

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
            Log::error('Error in checkStockQuantity:', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Internal Server Error',
            ], 500);
        }
    }
    // public function checkStockQuantity(Request $request)
    // {
    //     try {
    //         $partItemId = $request->input('part_item_id');
    //         $quantity = $request->input('quantity');
    //         $materialSendProduction = $request->input('material_send_production');
    //         $quantityMinusStatus = $request->input('quantity_minus_status');

    //         \Log::info('Checking stock quantity', [
    //             'part_item_id' => $partItemId,
    //             'quantity' => $quantity,
    //             'material_send_production' => $materialSendProduction,
    //             'quantity_minus_status' => $quantityMinusStatus
    //         ]);

    //         // If already processed, SKIP checking stock
    //         if ($materialSendProduction == 1 && $quantityMinusStatus == 'done') {
    //             return response()->json([
    //                 'status' => 'success',
    //                 'message' => 'Stock check skipped (already processed)',
    //             ]);
    //         }

    //         // Validate inputs
    //         if (!$partItemId || !$quantity) {
    //             return response()->json([
    //                 'status' => 'error',
    //                 'message' => 'Invalid inputs. Please provide both part_item_id and quantity.',
    //             ], 400);
    //         }

    //         // Fetch the part item stock
    //         $partItem = ItemStock::find($partItemId);

    //         if (!$partItem) {
    //             return response()->json([
    //                 'status' => 'error',
    //                 'message' => 'Part Item not found',
    //             ], 404);
    //         }

    //         // Get the available quantity
    //         $availableQuantity = $partItem->quantity ?? 0;

    //         if ($quantity > $availableQuantity) {
    //             return response()->json([
    //                 'status' => 'error',
    //                 'available_quantity' => $availableQuantity,
    //                 'message' => 'Insufficient stock',
    //             ]);
    //         }

    //         // Sufficient stock
    //         return response()->json([
    //             'status' => 'success',
    //             'available_quantity' => $availableQuantity,
    //         ]);
    //     } catch (\Exception $e) {
    //         \Log::error('Error in checkStockQuantity:', ['error' => $e->getMessage()]);
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Internal Server Error',
    //         ], 500);
    //     }
    // }


    public function updateProductMaterialWiseAddNewReq(Request $request)
    {
        $rules = [];

        $messages = [];

        $validation = Validator::make($request->all(), $rules, $messages);

        if ($validation->fails()) {
            return redirect()->back()->withInput()->withErrors($validation);
        }

        try {
            $updateData = $this->service->updateProductMaterialWiseAddNewReq($request);

            if ($updateData['status'] == 'success') {

                // return redirect('storedept/list-accepted-design-from-prod')->with(['status' => 'success', 'msg' => $updateData['message']]);
                return redirect()->back()
                    ->with(['status' => 'success', 'msg' => $updateData['message']]);
            } else {
                return redirect()->back()->withInput()->with(['status' => 'error', 'msg' => $updateData['message']]);
            }
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with(['status' => 'error', 'msg' => $e->getMessage()]);
        }
    }
    public function getPartItemRate(Request $request)
    {
        $partItem = PartItem::find($request->part_item_id);

        if ($partItem) {
            return response()->json([
                'status' => 'success',
                'basic_rate' => $partItem->basic_rate // adjust field name if different
            ]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Part item not found']);
        }
    }

    public function editProductMaterialWiseAdd($purchase_orders_id, $business_id)
    {
        try {
            $purchase_orders_id = base64_decode($purchase_orders_id);

            $business_id = base64_decode($business_id);
            $editData = $this->service->editProductMaterialWiseAdd($purchase_orders_id, $business_id);
            $dataOutputPartItem = PartItem::where('is_active', true)->orderByRaw('LOWER(description) ASC')->get();
            $dataOutputUnitMaster = UnitMaster::where('is_active', true)->get();
            return view('organizations.store.list.edit-material-bom-wise-add', [
                'productDetails' => $editData['productDetails'],
                'dataGroupedById' => $editData['dataGroupedById'],
                'dataOutputPartItem' => $dataOutputPartItem,
                'dataOutputUnitMaster' => $dataOutputUnitMaster,
                'purchase_orders_id' => $purchase_orders_id,
                'business_id' => $business_id
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with(['status' => 'error', 'msg' => $e->getMessage()]);
        }
    }
    public function updateProductMaterialWiseAdd(Request $request)
    {
        $rules = [];

        $messages = [];

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
    public function generateSRstoreDept(Request $request)
    {
        try {
            if (empty($request->id)) {
                return redirect()->back()->with('error', 'GRN ID is required.');
            }
            $gatepass = GRNModel::where('id', $request->id)->first();

            if (!$gatepass) {
                return redirect()->back()->with('error', 'GRN not found.');
            }
            $store_receipt_no_generate = str_replace(['-', ':', ' '], '', date('YmdHis'));
            $gatepass->store_remark = $request->store_remark;
            $gatepass->store_receipt_no_generate = $store_receipt_no_generate;
            $gatepass->grn_status_sanction = config('constants.STORE_DEPARTMENT.STORE_RECIEPT_GENRATED_SENT_TO_FINANCE_GRN_WISE');

            if ($gatepass->save()) {
                return redirect('storedept/list-material-received-from-quality')
                    ->with('success', 'GRN updated successfully.');
            }
            return redirect()->back()->with('error', 'Failed to update GRN.');
        } catch (\Exception $e) {
            // Log the exception
            Log::error('Error in storeGRN: ' . $e->getMessage());

            return redirect()->back()->with('error', 'An error occurred while updating GRN.');
        }
    }
    // public function destroyAddmoreStoreItem(Request $request)
    // {

    //     $delete_data_id = $request->delete_id;
    //     // Get the delete ID from the request

    //     try {
    //         $delete_record = $this->service->destroyAddmoreStoreItem($delete_data_id);
    //         if ($delete_record) {
    //             $msg = $delete_record['msg'];
    //             $status = $delete_record['status'];
    //             if ($status == 'success') {
    //                 return redirect('storedept/list-accepted-design-from-prod')->with(compact('msg', 'status'));
    //             } else {
    //                 return redirect()->back()->withInput()->with(compact('msg', 'status'));
    //             }
    //         }
    //     } catch (\Exception $e) {
    //         return $e;
    //     }
    // }

    public function destroyAddmoreStoreItem(Request $request)
    {
        try {
            $delete_record = $this->service->destroyAddmoreStoreItem($request->delete_id);

            return response()->json([
                'status' => $delete_record['status'],
                'msg'    => $delete_record['msg']
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'msg'    => $e->getMessage()
            ], 500);
        }
    }
}
