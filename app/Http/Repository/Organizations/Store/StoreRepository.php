<?php

namespace App\Http\Repository\Organizations\Store;

use Illuminate\Support\Facades\Log;
use App\Models\{
    BusinessApplicationProcesses,
    ProductionModel,
    Requisition,
    PurchaseOrderModel,
    AdminView,
    BusinessDetails,
    ProductionDetails,
    ItemStock,
    NotificationStatus,
    PartItem
};

class StoreRepository
{

    public function orderAcceptedAndMaterialForwareded($id)
    {
        try {
            $id = base64_decode($id); // Assuming $encodedId is base64 encoded

            $business_application = BusinessApplicationProcesses::where('business_details_id', $id)->first();
            if ($business_application) {
                $business_application->business_status_id = config('constants.HIGHER_AUTHORITY.LIST_BOM_PART_MATERIAL_SENT_TO_PROD_DEPT_FOR_PRODUCTION');
                $business_application->design_status_id = config('constants.DESIGN_DEPARTMENT.ACCEPTED_DESIGN_BY_PRODUCTION');
                $business_application->production_status_id = config('constants.PRODUCTION_DEPARTMENT.LIST_BOM_PART_MATERIAL_RECIVED_FROM_STORE_DEPT_FOR_PRODUCTION');
                $business_application->store_material_sent_date = date('Y-m-d');
                $business_application->store_status_id = config('constants.STORE_DEPARTMENT.LIST_BOM_PART_MATERIAL_SENT_TO_PROD_DEPT_FOR_PRODUCTION');
                $business_application->save();
            }
        } catch (\Exception $e) {
            return $e;
        }
    }

    // public function storeRequesition($request)
    // {
    //     try {

    //         $production_id = base64_decode($request->production_id);
    //         $businessDetails = BusinessDetails::where('id', $production_id)->first();
    //         if (!$businessDetails) {
    //             return [
    //                 'msg' => 'Business details not found.',
    //                 'status' => 'error',
    //             ];
    //         }

    //         $productName = $businessDetails->product_name;
    //         // $business_application = BusinessApplicationProcesses::where('design_id', $production_id)->first();
    //         $business_application = BusinessApplicationProcesses::where('business_details_id', $businessDetails->id)->first();
    //         $dataOutput = new Requisition();
    //         $dataOutput->business_id = $business_application->business_id;
    //         $dataOutput->business_details_id = $business_application->business_details_id;
    //         $dataOutput->design_id = $business_application->design_id;
    //         $dataOutput->production_id = $business_application->production_id;
    //         $dataOutput->req_name = "";
    //         $dataOutput->req_date = date('Y-m-d');
    //         $dataOutput->bom_file = 'null';
    //         $dataOutput->save();
    //         $last_insert_id = $dataOutput->id;

    //         // Updating image name in requisition
    //         $imageName = $last_insert_id . '_' . $productName . '_' . rand(100000, 999999) . '_requisition_bom.' . $request->bom_file_req->getClientOriginalExtension();
    //         $finalOutput = Requisition::find($last_insert_id);
    //         $finalOutput->bom_file = $imageName;
    //         $finalOutput->save();
    //         if ($business_application) {
    //             $business_application->business_status_id = config('constants.HIGHER_AUTHORITY.LIST_REQUEST_NOTE_RECIEVED_FROM_STORE_DEPT_FOR_PURCHASE');
    //             $business_application->design_status_id = config('constants.DESIGN_DEPARTMENT.ACCEPTED_DESIGN_BY_PRODUCTION');
    //             $business_application->production_status_id = config('constants.PRODUCTION_DEPARTMENT.BOM_SENT_TO_STORE_DEPT_FOR_CHECKING');
    //             $business_application->store_status_id = config('constants.STORE_DEPARTMENT.LIST_REQUEST_NOTE_SENT_FROM_STORE_DEPT_FOR_PURCHASE');
    //             $business_application->requisition_id = $last_insert_id;
    //             $business_application->purchase_order_id = '0';
    //             $dataOutput->purchase_dept_req_sent_date = date('Y-m-d');
    //             $business_application->save();

    //             $update_data_admin['off_canvas_status'] = 16;
    //             $update_data_admin['is_view'] = '0';
    //             $update_data_business['off_canvas_status'] = 16;
    //             $update_data_business['purchase_is_view'] = 0;
    //             AdminView::where('business_details_id', $business_application->business_details_id)
    //                 // ->where('business_details_id', $production_data->business_details_id) // Corrected the condition here
    //                 ->update($update_data_admin);

    //             NotificationStatus::where('business_details_id', $business_application->business_details_id)
    //                 // ->where('business_details_id', $production_data->business_details_id) // Corrected the condition here
    //                 ->update($update_data_business);
    //         }
    //         // Updating off_canvas_status for the business application
    //         $business_application->off_canvas_status = 16;
    //         $business_application->save();

    //         // PurchaseOrderModel::where('business_id', $business_application->business_id)->update(['purchase_status_from_purchase', config('constants.PUCHASE_DEPARTMENT.LIST_REQUEST_NOTE_RECIEVED_FROM_STORE_DEPT_FOR_PURCHASE')]);

    //         return [
    //             'ImageName' => $imageName,
    //             'status' => 'success'
    //         ];
    //     } catch (\Exception $e) {
    //         return [
    //             'msg' => $e->getMessage(),
    //             'status' => 'error'
    //         ];
    //     }
    // }

    public function storeRequesition($request)
    {
        try {

            $production_id = base64_decode($request->production_id);

            $businessDetails = BusinessDetails::where('id', $production_id)->first();
            if (!$businessDetails) {
                return [
                    'msg' => 'Business details not found.',
                    'status' => 'error',
                ];
            }

            $productName = $businessDetails->product_name;

            $business_application = BusinessApplicationProcesses::where('business_details_id', $businessDetails->id)->first();
            if (!$business_application) {
                return [
                    'msg' => 'Business application not found.',
                    'status' => 'error',
                ];
            }

            //----------------------------------------------------------------
            // 1️⃣ CHECK IF REQUISITION ALREADY EXISTS
            //----------------------------------------------------------------
            $requisition = Requisition::where('business_details_id', $business_application->business_details_id)->first();

            if ($requisition) {
                // existing requisition → update
                $last_insert_id = $requisition->id;
            } else {
                // create new requisition
                $requisition = new Requisition();
                $requisition->business_id = $business_application->business_id;
                $requisition->business_details_id = $business_application->business_details_id;
                $requisition->design_id = $business_application->design_id;
                $requisition->production_id = $business_application->production_id;
                $requisition->req_name = "";
                $requisition->req_date = date('Y-m-d');
                $requisition->bom_file = null;  // file set later
                $requisition->save();

                $last_insert_id = $requisition->id;
            }

            //----------------------------------------------------------------
            // 2️⃣ GENERATE FILE NAME ONLY (NO UPLOAD HERE)
            //----------------------------------------------------------------
            $imageName = $requisition->bom_file; // default old file name

            if ($request->hasFile('bom_file_req')) {

                $imageName = $last_insert_id . '_' . $productName . '_' . rand(100000, 999999)
                    . '_requisition_bom.' . $request->bom_file_req->getClientOriginalExtension();

                // store new file name
                $requisition->bom_file = $imageName;
            }

            $requisition->save();

            //----------------------------------------------------------------
            // 3️⃣ UPDATE BUSINESS APPLICATION STATUS
            //----------------------------------------------------------------
            $business_application->business_status_id   = config('constants.HIGHER_AUTHORITY.LIST_REQUEST_NOTE_RECIEVED_FROM_STORE_DEPT_FOR_PURCHASE');
            $business_application->design_status_id     = config('constants.DESIGN_DEPARTMENT.ACCEPTED_DESIGN_BY_PRODUCTION');
            $business_application->production_status_id = config('constants.PRODUCTION_DEPARTMENT.BOM_SENT_TO_STORE_DEPT_FOR_CHECKING');
            $business_application->store_status_id      = config('constants.STORE_DEPARTMENT.LIST_REQUEST_NOTE_SENT_FROM_STORE_DEPT_FOR_PURCHASE');

            $business_application->requisition_id = $last_insert_id;
            $business_application->purchase_order_id = 0;
            $business_application->off_canvas_status = 16;
            $business_application->save();

            //----------------------------------------------------------------
            // 4️⃣ UPDATE ADMIN VIEW & NOTIFICATION
            //----------------------------------------------------------------
            AdminView::where('business_details_id', $business_application->business_details_id)
                ->update([
                    'off_canvas_status' => 16,
                    'is_view' => 0
                ]);

            NotificationStatus::where('business_details_id', $business_application->business_details_id)
                ->update([
                    'off_canvas_status' => 16,
                    'purchase_is_view' => 0
                ]);


            return [
                'ImageName' => $imageName, // return file name to service
                'status' => 'success'
            ];
        } catch (\Exception $e) {
            return [
                'msg' => $e->getMessage(),
                'status' => 'error'
            ];
        }
    }


    public function genrateStoreReciptAndForwardMaterialToTheProduction($purchase_orders_id, $business_id)
    {
        try {


            // Fetch the purchase order and business application based on the production ID
            $purchase_order = PurchaseOrderModel::where('purchase_orders_id', $purchase_orders_id)->first();

            $business_application = BusinessApplicationProcesses::where('business_details_id', $business_id)->first();

            // Generate a store receipt number
            $store_receipt_no = str_replace(array("-", ":"), "", date('Y-m-d') . time());

            // Check if the business application exists
            if ($business_application  &&  $purchase_order) {
                // Update the business application statuses and dates
                $business_application->business_status_id = config('constants.HIGHER_AUTHORITY.LIST_BOM_PART_MATERIAL_SENT_TO_PROD_DEPT_FOR_PRODUCTION');
                $business_application->design_status_id = config('constants.DESIGN_DEPARTMENT.ACCEPTED_DESIGN_BY_PRODUCTION');
                $business_application->production_status_id = config('constants.PRODUCTION_DEPARTMENT.LIST_BOM_PART_MATERIAL_RECIVED_FROM_STORE_DEPT_FOR_PRODUCTION');
                $business_application->store_material_sent_date = date('Y-m-d');
                $business_application->off_canvas_status = 17;
                // Update the purchase order statuses, receipt number, and dates
                $purchase_order->store_status_id = config('constants.STORE_DEPARTMENT.LIST_BOM_PART_MATERIAL_SENT_TO_PROD_DEPT_FOR_PRODUCTION');
                $purchase_order->store_receipt_no = $store_receipt_no;
                $purchase_order->finanace_store_receipt_generate_date = date('Y-m-d');
                $purchase_order->finanace_store_receipt_status_id = config('constants.FINANCE_DEPARTMENT.LIST_STORE_RECIEPT_AND_GRN_RECEIVED_FROM_STORE_DEAPRTMENT');

                // Save the updated business application and purchase order
                $business_application->save();
                $purchase_order->save();
            }


            return "ok";
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    public function editProductMaterialWiseAddNewReq($id)
    { //checked
        try {
            $id = base64_decode($id);
            $dataOutputByid = BusinessApplicationProcesses::leftJoin('production', function ($join) {
                $join->on('business_application_processes.business_details_id', '=', 'production.business_details_id');
            })
                ->leftJoin('businesses_details', function ($join) {
                    $join->on('business_application_processes.business_details_id', '=', 'businesses_details.id');
                })
                ->leftJoin('production_details', function ($join) {
                    $join->on('business_application_processes.business_details_id', '=', 'production_details.business_details_id');
                })
                ->where('businesses_details.id', $id)
                ->where('businesses_details.is_active', true)
                ->where('production_details.is_deleted', 0)
                ->select(
                    'businesses_details.id',
                    // 'gatepass.id',
                    'production_details.id',
                    'businesses_details.product_name',
                    'businesses_details.quantity',
                    'businesses_details.description',
                    'production_details.part_item_id',
                    'production_details.quantity',
                    'production_details.unit',
                    'production_details.quantity_minus_status',
                    'production_details.basic_rate',
                    'production_details.material_send_production',
                    'business_application_processes.store_material_sent_date',
                    'production_details.updated_at',
                )
                ->get();
            $productDetails = $dataOutputByid->first();
            $dataGroupedById = $dataOutputByid->groupBy('business_details_id');

            return [
                'productDetails' => $productDetails,
                'dataGroupedById' => $dataGroupedById
            ];
            // return  $dataOutputByid;
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'msg' => $e->getMessage()
            ];
        }
    }
    public function updateProductMaterialWiseAddNewReq($request)
    {
        try {
            $business_details_id = base64_decode($request->business_details_id);
            // Fetch production details
            $dataOutput_Production = ProductionModel::where('business_details_id', $business_details_id)->firstOrFail();
            $dataOutput_Production->production_status_quantity_tracking = 'incomplete';
            $dataOutput_Production->save();

            $dataOutput_ProductionDetails = ProductionDetails::where('business_details_id', $dataOutput_Production->business_details_id)->firstOrFail();
            $business_details_id = $dataOutput_ProductionDetails->business_details_id;
            $business_application = BusinessApplicationProcesses::where('business_details_id', $business_details_id)->first();

            if (!$business_application) {
                return [
                    'msg' => 'Business Application not found.',
                    'status' => 'error'
                ];
            }

            $errorMessages = [];

            foreach ($request->addmore as $index => $item) {

                Log::info("Iteration: $index", ['item' => $item]);

                if ($index == 4) {
                }

                $quantity_minus_status = isset($item['quantity_minus_status']) ? $item['quantity_minus_status'] : null;
                $material_send_production = isset($item['material_send_production']) ? $item['material_send_production'] : null;

                $existingEntry = ProductionDetails::where('business_details_id', $business_application->business_details_id)
                    ->where('quantity_minus_status', 'pending')
                    ->where('material_send_production', 0)
                    ->where('is_deleted', 0)
                    ->first();

                $partItemData = PartItem::where('id', $item['part_item_id'])->first();

                $basicRate = $partItemData ? $partItemData->basic_rate : 0; // default 0 if not found

                // Calculate total amount
                $totalAmount = isset($item['items_used_total_amount'])
                    ? $item['items_used_total_amount']
                    : ($basicRate * $item['quantity']);


                if ($existingEntry) {
                    if ($item['quantity_minus_status'] == 'pending' && $item['quantity_minus_status'] != 'done') {
                        // if ($existingEntry->quantity_minus_status == 'pending' && $existingEntry->material_send_production == '0') {
                        $existingEntry->part_item_id = $item['part_item_id'];
                        $existingEntry->quantity = $item['quantity'];  // Set the new quantity (replacing the existing one)
                        $existingEntry->unit = $item['unit'];  // Update the unit if needed
                        $existingEntry->basic_rate = $basicRate; // <-- auto insert basic_rate
                        $existingEntry->items_used_total_amount = $totalAmount; // auto insert items_used_total_amount
                        $existingEntry->quantity_minus_status = 'pending';  // Ensure it's 'pending' for new request
                        $existingEntry->material_send_production = 0;  // Reset material_send_production
                        $existingEntry->save();

                        $partItemId = $existingEntry->part_item_id;
                        $itemStock = ItemStock::where('part_item_id', $partItemId)->first();

                        if ($itemStock) {
                            if (
                                $itemStock->quantity >= $existingEntry->quantity &&
                                $existingEntry->material_send_production == 0 &&
                                $existingEntry->quantity_minus_status == 'pending'
                            ) {
                                $itemStock->quantity -= $existingEntry->quantity;
                                $itemStock->save();

                                // Mark the entry as done and material sent to production
                                if ($existingEntry) {
                                    $existingEntry->material_send_production = 1;
                                    $existingEntry->quantity_minus_status = 'done';  // Set to done
                                } else {
                                    // For newly created entry, mark as 'done' as well
                                    $newEntry->material_send_production = 1;
                                    $newEntry->quantity_minus_status = 'done';  // Set to done
                                }
                                // Save updated entry (after stock deduction)
                                $existingEntry ? $existingEntry->save() : $newEntry->save();
                            } else {
                                // Log error if not enough stock
                                $errorMessages[] = "Not enough stock for part item ID: " . $item['part_item_id'];
                            }
                        } else {
                            // Log error if stock record not found
                            $errorMessages[] = "Item stock not found for part item ID: " . $item['part_item_id'];
                        }
                        // }
                        // else{
                        //     echo "hii";
                        // }

                    }
                } else {
                    if ($item['material_send_production'] == 1 && !array_key_exists('quantity_minus_status', $item)) {
                        // If no matching record exists, create a new entry
                        $newEntry = new ProductionDetails();
                        $newEntry->part_item_id = $item['part_item_id'];
                        $newEntry->quantity = $item['quantity'];
                        $newEntry->unit = $item['unit'];
                        $newEntry->basic_rate = $basicRate; // <-- auto insert basic_rate
                        $newEntry->items_used_total_amount = $totalAmount; // auto insert items_used_total_amount
                        $newEntry->quantity_minus_status = 'pending';  // Status as 'pending'
                        $newEntry->material_send_production = 0;  // Not yet sent for production
                        $newEntry->business_id = $dataOutput_ProductionDetails->business_id;
                        $newEntry->design_id = $dataOutput_ProductionDetails->design_id;
                        $newEntry->business_details_id = $dataOutput_ProductionDetails->business_details_id;
                        $newEntry->production_id = $dataOutput_ProductionDetails->production_id;
                        $newEntry->save();
                        $partItemId = $newEntry->part_item_id;
                        $itemStock = ItemStock::where('part_item_id', $partItemId)->first();

                        if ($itemStock) {
                            // if ($itemStock->quantity >= $newEntry->quantity) {
                            if (
                                $itemStock->quantity >= $newEntry->quantity &&
                                $newEntry->material_send_production == 0 &&
                                $newEntry->quantity_minus_status == 'pending'
                            ) {
                                // Deduct stock and update statuses
                                $itemStock->quantity -= $newEntry->quantity;
                                $itemStock->save();

                                // Mark the entry as done and material sent to production
                                if ($newEntry) {
                                    $newEntry->material_send_production = 1;
                                    $newEntry->quantity_minus_status = 'done';  // Set to done
                                } else {
                                    // For newly created entry, mark as 'done' as well
                                    $newEntry->material_send_production = 1;
                                    $newEntry->quantity_minus_status = 'done';  // Set to done
                                }
                                // Save updated entry (after stock deduction)
                                $newEntry ? $newEntry->save() : $newEntry->save();
                            } else {
                                // Log error if not enough stock
                                $errorMessages[] = "Not enough stock for part item ID: " . $item['part_item_id'];
                            }
                        } else {
                            // Log error if stock record not found
                            $errorMessages[] = "Item stock not found for part item ID: " . $item['part_item_id'];
                        }
                    }
                }
            }

            // Update BusinessApplicationProcesses and related statuses
            $business_application->off_canvas_status = 17;
            $business_application->save();

            AdminView::where('business_details_id', $business_application->business_details_id)
                ->update(['off_canvas_status' => 17, 'is_view' => '0']);
            NotificationStatus::where('business_details_id', $business_application->business_details_id)
                ->update(['off_canvas_status' => 17, 'material_received_from_store' => '0']);

            // If errors occurred, return them
            if (!empty($errorMessages)) {
                return [
                    'status' => 'error',
                    'errors' => $errorMessages
                ];
            }

            // Update production status
            $businessOutput = BusinessApplicationProcesses::where('business_details_id', $dataOutput_ProductionDetails->business_details_id)->firstOrFail();
            $businessOutput->product_production_inprocess_status_id = config('constants.PRODUCTION_DEPARTMENT.ACTUAL_WORK_INPROCESS_FOR_PRODUCTION');
            $businessOutput->save();

            return [
                'status' => 'success',
                'message' => 'Production materials updated successfully.',
                'updated_details' => $request->all()
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'error' => $e->getMessage()
            ];
        }
    }

    public function editProductMaterialWiseAdd($purchase_orders_id, $business_id)
    {
        try {
            $dataOutputByid = BusinessApplicationProcesses::leftJoin('production', function ($join) {
                $join->on('business_application_processes.business_details_id', '=', 'production.business_details_id');
            })
                ->leftJoin('designs', function ($join) {
                    $join->on('business_application_processes.business_details_id', '=', 'designs.business_details_id');
                })
                ->leftJoin('businesses_details', function ($join) {
                    $join->on('business_application_processes.business_details_id', '=', 'businesses_details.id');
                })
                ->leftJoin('design_revision_for_prod', function ($join) {
                    $join->on('business_application_processes.business_details_id', '=', 'design_revision_for_prod.business_details_id');
                })
                ->leftJoin('purchase_orders', function ($join) {
                    $join->on('business_application_processes.business_details_id', '=', 'purchase_orders.business_details_id');
                })
                ->leftJoin('production_details', function ($join) {
                    $join->on('business_application_processes.business_details_id', '=', 'production_details.business_details_id');
                })
                ->leftJoin('grn_tbl', function ($join) {
                    $join->on('purchase_orders.purchase_orders_id', '=', 'grn_tbl.purchase_orders_id');
                })
                ->leftJoin('gatepass', function ($join) {
                    $join->on('grn_tbl.gatepass_id', '=', 'gatepass.id');
                })
                ->where('purchase_orders.purchase_orders_id', $purchase_orders_id)
                ->where('businesses_details.is_active', true)
                ->select(
                    'gatepass.id',
                    'businesses_details.product_name',
                    'businesses_details.quantity',
                    'businesses_details.description',
                    'production_details.part_item_id',
                    'production_details.quantity',
                    'production_details.unit',
                    'production_details.material_send_production',
                    'designs.bom_image',
                    'designs.design_image',
                    'business_application_processes.store_material_sent_date'
                )
                ->get();
            $productDetails = $dataOutputByid->first();
            $dataGroupedById = $dataOutputByid->groupBy('business_details_id');

            return [
                'productDetails' => $productDetails,
                'dataGroupedById' => $dataGroupedById
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'msg' => $e->getMessage()
            ];
        }
    }
    // public function updateProductMaterialWiseAdd($request)
    // {
    //     try {

    //         $gatepassId = $request->id;
    //         $business_details_id = $request->business_details_id;
    //         $dataOutput_Production = ProductionModel::where('business_details_id', $business_details_id)->firstOrFail();
    //         $dataOutput_Production->production_status_quantity_tracking = 'incomplete';
    //         $dataOutput_Production->save();
    //         $dataOutput_ProductionDetails = ProductionDetails::where('business_details_id', $dataOutput_Production->business_details_id)->firstOrFail();
    //         $business_details_id = $dataOutput_ProductionDetails->business_details_id;
    //         $business_application = BusinessApplicationProcesses::where('business_details_id', $business_details_id)->first();
    //         if (!$business_application) {
    //             return [
    //                 'msg' => 'Business Application not found.',
    //                 'status' => 'error'
    //             ];
    //         }
    //         ProductionDetails::where('business_details_id', $dataOutput_ProductionDetails->business_details_id)->delete();
    //         $errorMessages = [];
    //         foreach ($request->addmore as $item) {

    //             $dataOutput = new ProductionDetails();
    //             // $dataOutput->part_item_id = $item['id'];
    //             $dataOutput->part_item_id = $item['part_item_id'];

    //             $dataOutput->quantity = $item['quantity'];
    //             $dataOutput->unit = $item['unit'];
    //             $dataOutput->material_send_production = isset($item['material_send_production']) && $item['material_send_production'] == '1' ? 1 : 0;
    //             $dataOutput->business_id = $dataOutput_ProductionDetails->business_id;
    //             $dataOutput->design_id = $dataOutput_ProductionDetails->design_id;
    //             $dataOutput->business_details_id = $dataOutput_ProductionDetails->business_details_id;
    //             $dataOutput->production_id = $dataOutput_ProductionDetails->production_id;
    //             $dataOutput->save();
    //             $existingEntry = ProductionDetails::find($dataOutput->id);
    //             if ($existingEntry && isset($existingEntry->part_item_id)) {

    //                 $partItemId = $existingEntry->part_item_id; // Get the part_item_id from the existing entry
    //                 $itemStock = ItemStock::where('part_item_id', $partItemId)->first();
    //             } else {
    //                 $errorMessages[] = "Production detail not found or part_item_id is missing.";
    //             }
    //         }
    //         $business_application->off_canvas_status = 17;
    //         $business_application->save();
    //         $update_data_admin['off_canvas_status'] = 17;
    //         $update_data_admin['is_view'] = '0';
    //         $update_data_business['off_canvas_status'] = 17;
    //         AdminView::where('business_details_id', $business_application->business_details_id)
    //             ->update($update_data_admin);
    //         NotificationStatus::where('business_details_id', $business_application->business_details_id)
    //             ->update($update_data_business);
    //         if (!empty($errorMessages)) {
    //             return [
    //                 'status' => 'error',
    //                 'errors' => $errorMessages
    //             ];
    //         }

    //         $businessOutput = BusinessApplicationProcesses::where('business_details_id', $dataOutput_ProductionDetails->business_details_id)
    //             ->firstOrFail();
    //         $businessOutput->product_production_inprocess_status_id = config('constants.PRODUCTION_DEPARTMENT.ACTUAL_WORK_INPROCESS_FOR_PRODUCTION');
    //         $businessOutput->save();
    //         return [
    //             'status' => 'success',
    //             'message' => 'Production materials updated successfully.',
    //             'updated_details' => $request->all()
    //         ];
    //     } catch (\Exception $e) {
    //         return [
    //             'status' => 'error',
    //             'error' => $e->getMessage()
    //         ];
    //     }
    // }
    public function updateProductMaterialWiseAdd($request)
    {
        try {

            // ----------------------------------------------------
            // 1) Fetch Production Header Data
            // ----------------------------------------------------
            $businessDetailsId = $request->business_details_id;

            $production = ProductionModel::where('business_details_id', $businessDetailsId)->firstOrFail();
            $production->production_status_quantity_tracking = 'incomplete';
            $production->save();

            // ----------------------------------------------------
            // 2) Fetch Production Details Header (same business_details_id)
            // ----------------------------------------------------
            $productionDetailsHeader = ProductionDetails::where('business_details_id', $businessDetailsId)->firstOrFail();

            // ----------------------------------------------------
            // 3) Fetch Business Application
            // ----------------------------------------------------
            $businessApplication = BusinessApplicationProcesses::where('business_details_id', $businessDetailsId)->first();
            if (!$businessApplication) {
                return [
                    'status' => 'error',
                    'msg' => 'Business Application not found.'
                ];
            }

            // ----------------------------------------------------
            // 4) Collect all detail_ids submitted in the form
            // ----------------------------------------------------
            $submittedIds = collect($request->addmore)
                ->pluck('detail_id')
                ->filter()
                ->toArray();

            // ----------------------------------------------------
            // 5) DELETE rows which user removed in UI
            // ----------------------------------------------------
            ProductionDetails::where('business_details_id', $businessDetailsId)
                ->whereNotIn('id', $submittedIds)
                ->delete();

            // ----------------------------------------------------
            // 6) START Updating or Inserting rows
            // ----------------------------------------------------
            $errorMessages = [];

            foreach ($request->addmore as $item) {

                // ----------------------------------------------
                // CASE A → Row already exists → UPDATE
                // ----------------------------------------------
                if (!empty($item['detail_id'])) {

                    $detail = ProductionDetails::find($item['detail_id']);

                    if ($detail) {

                        $detail->part_item_id = $item['part_item_id'];     // <- RIGHT FIELD
                        $detail->quantity = $item['quantity'];
                        $detail->unit = $item['unit'];
                        $detail->material_send_production =
                            isset($item['material_send_production']) ? 1 : 0;

                        $detail->save();
                    } else {
                        $errorMessages[] = "Detail ID {$item['detail_id']} not found.";
                    }
                }

                // ----------------------------------------------
                // CASE B → New Row → INSERT
                // ----------------------------------------------
                else {

                    $newDetail = new ProductionDetails();
                    $newDetail->part_item_id = $item['part_item_id'];     // <- RIGHT FIELD
                    $newDetail->quantity = $item['quantity'];
                    $newDetail->unit = $item['unit'];
                    $newDetail->material_send_production =
                        isset($item['material_send_production']) ? 1 : 0;

                    // Inherit fields from the existing header row
                    $newDetail->business_id = $productionDetailsHeader->business_id;
                    $newDetail->design_id = $productionDetailsHeader->design_id;
                    $newDetail->business_details_id = $productionDetailsHeader->business_details_id;
                    $newDetail->production_id = $productionDetailsHeader->production_id;

                    $newDetail->save();
                }
            }

            // ----------------------------------------------------
            // 7) Update Business Application Status
            // ----------------------------------------------------
            $businessApplication->off_canvas_status = 17;
            $businessApplication->save();

            // Update Admin View + Notification
            AdminView::where('business_details_id', $businessDetailsId)
                ->update([
                    'off_canvas_status' => 17,
                    'is_view' => '0'
                ]);

            NotificationStatus::where('business_details_id', $businessDetailsId)
                ->update([
                    'off_canvas_status' => 17
                ]);

            // ----------------------------------------------------
            // 8) If any errors occurred
            // ----------------------------------------------------
            if (!empty($errorMessages)) {
                return [
                    'status' => 'error',
                    'errors' => $errorMessages
                ];
            }

            // ----------------------------------------------------
            // 9) Update production workflow
            // ----------------------------------------------------
            $businessOutput = BusinessApplicationProcesses::where('business_details_id', $businessDetailsId)
                ->firstOrFail();

            $businessOutput->product_production_inprocess_status_id =
                config('constants.PRODUCTION_DEPARTMENT.ACTUAL_WORK_INPROCESS_FOR_PRODUCTION');

            $businessOutput->save();

            // ----------------------------------------------------
            // 10) Final Response
            // ----------------------------------------------------
            return [
                'status' => 'success',
                'message' => 'Production materials updated successfully.',
                'updated_details' => $request->all()
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'error' => $e->getMessage()
            ];
        }
    }

    public function destroyAddmoreStoreItem($id)
    {
        try {
            $deleteDataById = ProductionDetails::find($id);

            if ($deleteDataById) {
                // Perform a soft delete by setting is_deleted = 1
                $deleteDataById->is_deleted = 1;
                $deleteDataById->save();

                return [
                    'status' => 'success',
                    'message' => 'Record marked as deleted successfully.',
                    'data' => $deleteDataById
                ];
            } else {
                return [
                    'status' => 'error',
                    'message' => 'Record not found.'
                ];
            }
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }
}
