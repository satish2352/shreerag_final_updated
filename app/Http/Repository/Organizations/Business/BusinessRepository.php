<?php

namespace App\Http\Repository\Organizations\Business;

use Illuminate\Support\Facades\Log;
use App\Models\{
    Business,
    DesignModel,
    BusinessApplicationProcesses,
    PurchaseOrderDetailsModel,
    PurchaseOrdersModel,
    OrganizationModel,
    BusinessDetails,
    AdminView,
    NotificationStatus,
    GRNModel,
    Gatepass,
    GrnPOQuantityTracking,
    RejectedChalan,
    EstimationModel,
    DesignRevisionForProd
};
use App\Http\Controllers\Organizations\CommanController;

class BusinessRepository
{

    protected $serviceCommon;

    public function __construct()
    {
        $this->serviceCommon = new CommanController();
    }
    public function getAll()
    {
        try {
            $data_output = Business::orderBy('businesses.updated_at', 'desc')
                ->where('is_deleted', 0)
                ->get();
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function addAll($request)
    {
        try {

            $data_output = OrganizationModel::pluck('id')->first();

            if (!$data_output) {
                throw new \Exception('No organization found');
            }
            $business_data = new Business();
            $business_data->customer_po_number = $request->customer_po_number;
            $business_data->project_name = $request->project_name;
            $business_data->title = $request->title;
            $business_data->remarks = $request->remarks;
            $business_data->po_validity = $request->po_validity;
            $business_data->organization_id = $data_output;
            if (isset($request['customer_payment_terms'])) {
                $business_data->customer_payment_terms = $request['customer_payment_terms'];
            }
            if (isset($request['customer_terms_condition'])) {
                $business_data->customer_terms_condition = $request['customer_terms_condition'];
            }
            $projectName = $business_data->project_name;
            /*  FIX START */
            if ($request->hasFile('business_pdf')) {
                $formattedProjectName = preg_replace('/_+/', '_', $projectName);
                $bomImageName = rand(100000, 999999) . '_' . $formattedProjectName . '_' .
                    $request->file('business_pdf')->getClientOriginalExtension();

                $business_data->business_pdf = $bomImageName;
            } else {
                $business_data->business_pdf = ''; // or null if allowed
            }
            /*  FIX END */
            $business_data->save();
            $last_insert_id = $business_data->id;

            $grandTotal = 0;

            foreach ($request->addmore as $index => $item) {
                // Check if all required fields exist and are not empty
                if (
                    isset($item['product_name']) && !empty($item['product_name']) &&
                    isset($item['description']) && !empty($item['description']) &&
                    isset($item['quantity']) && !empty($item['quantity']) &&
                    isset($item['rate']) && !empty($item['rate'])
                ) {
                    // Calculate total_amount for this row
                    $totalAmount = $item['quantity'] * $item['rate'];
                    $grandTotal += $totalAmount;

                    $businessDetails = new BusinessDetails();
                    $businessDetails->business_id = $last_insert_id;
                    $businessDetails->product_name = $item['product_name'];
                    $businessDetails->description = $item['description'];
                    $businessDetails->quantity = $item['quantity'];
                    $businessDetails->rate = $item['rate'];
                    $businessDetails->total_amount = $totalAmount;
                    $businessDetails->save();

                    $design_data = new DesignModel();
                    $design_data->business_id = $business_data->id;
                    $design_data->business_details_id = $businessDetails->id;
                    $design_data->design_image = '';
                    $design_data->bom_image = '';
                    $design_data->save();

                    $admin_view_data = new AdminView();
                    $admin_view_data->business_id = $last_insert_id;
                    $admin_view_data->business_details_id = $businessDetails->id;
                    $admin_view_data->off_canvas_status = 11;
                    $admin_view_data->save();

                    $notification_status = new NotificationStatus();
                    $notification_status->business_id = $last_insert_id;
                    $notification_status->business_details_id = $businessDetails->id;
                    $notification_status->off_canvas_status = 11;
                    $notification_status->save();

                    $business_application = new BusinessApplicationProcesses();
                    $business_application->business_id = $business_data->id;
                    $business_application->business_details_id = $businessDetails->id;
                    $business_application->off_canvas_status = 11;
                    $business_application->business_status_id = config('constants.HIGHER_AUTHORITY.NEW_REQUIREMENTS_SENT_TO_DESIGN_DEPARTMENT');
                    $business_application->design_id = $design_data->id;
                    $business_application->design_status_id = config('constants.DESIGN_DEPARTMENT.LIST_NEW_REQUIREMENTS_RECEIVED_FOR_DESIGN');
                    $business_application->production_id = '0';
                    $business_application->production_status_id = '0';
                    $business_application->save();
                } else {
                    Log::warning("Missing required fields in row $index", $item);
                }
                $business_data->grand_total_amount = $grandTotal;
                $business_data->save();
            }
            return [
                'msg' => 'This business send to Design Department Successfully',
                'status' => 'success',
                'last_insert_id' => $last_insert_id,
                'business_pdf' => $business_data->business_pdf
            ];
        } catch (\Exception $e) {

            return [
                'msg' => $e->getMessage(),
                'status' => 'error'
            ];
        }
    }
    public function getById($id)
    {
        try {
            $designData = Business::leftJoin('businesses_details', 'businesses.id', '=', 'businesses_details.business_id')
                ->leftJoin('business_application_processes', 'business_application_processes.business_details_id', '=', 'businesses_details.id')
                ->select(
                    'businesses_details.*',
                    'businesses_details.id as businesses_details_id',
                    'businesses.id as business_main_id',
                    'businesses.project_name',
                    'businesses.customer_po_number',
                    'businesses.title',
                    'businesses.po_validity',
                    'businesses.customer_payment_terms',
                    'businesses.customer_terms_condition',
                    'businesses.remarks',
                    'businesses.business_pdf',
                    'businesses.grand_total_amount',
                    'business_application_processes.business_status_id',
                    'business_application_processes.design_status_id',
                    'business_application_processes.production_status_id',


                )
                ->where('businesses.id', $id)
                ->where('businesses_details.is_deleted', 0)
                ->get();

            if ($designData->isEmpty()) {
                return null;
            }

            // Calculate total_amount of existing products
            $totalAmount = $designData->sum(function ($item) {
                return $item->quantity * $item->rate;
            });

            return [
                'designData' => $designData,
                'total_amount' => $totalAmount,
                'grand_total_amount' => $designData[0]->grand_total_amount,
            ];
        } catch (\Exception $e) {
            return [
                'msg' => 'Failed to get by id.',
                'status' => 'error',
                'error' => $e->getMessage(),
            ];
        }
    }
    public function updateAll($request)
    {
        try {

            $return_data = array();

            $dataOutput = Business::find($request->business_main_id);

            if (!$dataOutput) {
                return [
                    'msg' => 'Update Data not found.',
                    'status' => 'error'
                ];
            }
            // Store the previous image names
            $previousEnglishImage = $dataOutput->business_pdf;

            Log::info('Request Data:', $request->all());

            $dataOutput = Business::findOrFail($request->business_main_id);
            $dataOutput->customer_po_number = $request->customer_po_number;
            $dataOutput->title = $request->title;
            $dataOutput->project_name = $request->project_name;
            $dataOutput->po_validity = $request->po_validity;
            $dataOutput->remarks = $request->remarks;

            if ($request->has('customer_payment_terms')) {
                $dataOutput->customer_payment_terms = $request['customer_payment_terms'];
            }
            if ($request->has('customer_terms_condition')) {
                $dataOutput->customer_terms_condition = $request['customer_terms_condition'];
            }

            $dataOutput->save();

            // Initialize grand total
            $grandTotal = 0;

            // Update existing rows and calculate their total
            for ($i = 0; $i < $request->design_count; $i++) {
                $designId = $request->input("design_id_" . $i);
                $productName = $request->input("product_name_" . $i);
                $description = $request->input("description_" . $i);
                $quantity = $request->input("quantity_" . $i);
                $rate = $request->input("rate_" . $i);
                // $total_amount = $request->input("total_amount_" . $i);

                // 👇 Calculate total_amount backend-side (ignore frontend calculation)
                $total_amount = $quantity * $rate;

                if ($designId && !is_null($productName)) {
                    $designDetails = BusinessDetails::findOrFail($designId);
                    $designDetails->product_name = $productName;
                    $designDetails->description = $description;
                    $designDetails->quantity = $quantity;
                    $designDetails->rate = $rate;
                    $designDetails->total_amount = $total_amount;
                    $designDetails->save();

                    $grandTotal += $total_amount;
                }
            }

            // Add new details if provided
            if ($request->has('addmore')) {
                foreach ($request->addmore as $item) {
                    $totalAmount = $item['quantity'] * $item['rate'];
                    $grandTotal += $totalAmount;

                    $addDetails = new BusinessDetails();
                    $addDetails->business_id = $request->business_main_id;
                    $addDetails->product_name = $item['product_name'];
                    $addDetails->description = $item['description'];
                    $addDetails->quantity = $item['quantity'];
                    $addDetails->rate = $item['rate'];
                    $addDetails->total_amount = $totalAmount;
                    $addDetails->save();

                    // Save to related models
                    $design_data = new DesignModel();
                    $design_data->business_id = $dataOutput->id;
                    $design_data->business_details_id = $addDetails->id;
                    $design_data->design_image = '';
                    $design_data->bom_image = '';
                    $design_data->save();

                    $business_application = new BusinessApplicationProcesses();
                    $business_application->business_id = $dataOutput->id;
                    $business_application->business_details_id = $addDetails->id;
                    $business_application->off_canvas_status = 11;
                    $business_application->business_status_id = config('constants.HIGHER_AUTHORITY.NEW_REQUIREMENTS_SENT_TO_DESIGN_DEPARTMENT');
                    $business_application->design_id = $design_data->id;
                    $business_application->design_status_id = config('constants.DESIGN_DEPARTMENT.LIST_NEW_REQUIREMENTS_RECEIVED_FOR_DESIGN');
                    $business_application->production_id = '0';
                    $business_application->production_status_id = '0';
                    $business_application->save();

                    $admin_view_data = new AdminView();
                    $admin_view_data->business_id = $dataOutput->id;
                    $admin_view_data->business_details_id = $addDetails->id;
                    $admin_view_data->off_canvas_status = 11;
                    $admin_view_data->save();

                    $notification_status = new NotificationStatus();
                    $notification_status->business_id = $dataOutput->id;
                    $notification_status->business_details_id = $addDetails->id;
                    $notification_status->off_canvas_status = 11;
                    $notification_status->save();
                }
            }

            // Save total to business record
            $dataOutput->grand_total_amount = $grandTotal;
            $return_data['business_pdf'] = $previousEnglishImage;
            $dataOutput->save();

            return [
                'msg' => 'Data updated successfully.',
                'status' => 'success',
                'last_insert_id' => $dataOutput->id,
                'total_amount' => $grandTotal,
                'business_pdf' => $previousEnglishImage,
            ];
        } catch (\Exception $e) {
            Log::error('Update Failed: ' . $e->getMessage());
            return [
                'msg' => 'Failed to update data.',
                'status' => 'error',
                'error' => $e->getMessage()
            ];
        }
    }
    public function deleteByIdAddmore($id)
    {
        try {
            // Fetch record
            $record = BusinessDetails::find($id);

            if (!$record) {
                return false;
            }

            // Soft delete → update is_deleted = 1
            $record->is_deleted = 1;
            $record->save();

            // Now recalculate grand total for business
            $businessId = $record->business_id;

            $newGrandTotal = BusinessDetails::where('business_id', $businessId)
                ->where('is_deleted', 0)
                ->sum('total_amount');

            // Update business table grand total
            Business::where('id', $businessId)->update([
                'grand_total_amount' => $newGrandTotal
            ]);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }






    public function deleteById($id)
    {
        try {
            $business = Business::find($id);

            if ($business) {
                // Use update queries for soft delete by setting `is_deleted` to 1
                $business->businessDetails()->update(['is_deleted' => 1]);
                $business->designModel()->update(['is_deleted' => 1]);
                $business->businessApplicationProcesses()->update(['is_deleted' => 1]);
                $business->designRevisionForProd()->update(['is_deleted' => 1]);
                $business->productionModel()->update(['is_deleted' => 1]);
                $business->productionDetails()->update(['is_deleted' => 1]);
                $business->PurchaseOrdersModel()->update(['is_deleted' => 1]);
                $business->requisition()->update(['is_deleted' => 1]);
                $business->customerProductQuantityTracking()->update(['is_deleted' => 1]);
                $business->deliveryChalan()->update(['is_deleted' => 1]);
                $business->dispatch()->update(['is_deleted' => 1]);
                $business->logistics()->update(['is_deleted' => 1]);
                $business->notificationStatus()->update(['is_deleted' => 1]);
                $business->returnableChalan()->update(['is_deleted' => 1]);
                $business->AdminView()->update(['is_deleted' => 1]);

                $businessDetails = $business->businessDetails;
                foreach ($businessDetails as $businessDetail) {
                    // Update is_deleted in purchase_orders based on business_details_id
                    $purchaseOrders = PurchaseOrdersModel::where('business_details_id', $businessDetail->id)->get();
                    foreach ($purchaseOrders as $purchaseOrder) {
                        $purchaseOrder->update(['is_deleted' => 1]);

                        // Update is_deleted for all gatepass related to purchase_orders_id
                        Gatepass::where('purchase_orders_id', $purchaseOrder->purchase_orders_id)->update(['is_deleted' => 1]);
                        GRNModel::where('purchase_orders_id', $purchaseOrder->purchase_orders_id)->update(['is_deleted' => 1]);
                        GrnPOQuantityTracking::where('purchase_order_id', $purchaseOrder->id)->update(['is_deleted' => 1]);
                        RejectedChalan::where('purchase_orders_id', $purchaseOrder->purchase_orders_id)->update(['is_deleted' => 1]);

                        // Update is_deleted for all purchase_order_details related to purchase_orders_id
                        $purchaseOrder->purchaseOrderDetails()->update(['is_deleted' => 1]);
                    }
                }
                // Update the main business record status to `is_deleted`
                $business->update(['is_deleted' => 1]);

                return true;
            } else {
                return false; // Record not found
            }
        } catch (\Exception $e) {
            Log::error('Error deleting business: ' . $e->getMessage());
            throw $e; // Re-throw the exception to be handled by the service
        }
    }
    public function acceptEstimationBOM($id)
    {
        try {
            $decoded_business_id = base64_decode($id);

            // Find the business application process record
            $business_application = BusinessApplicationProcesses::where('business_details_id', $decoded_business_id)->first();

            if ($business_application) {
                // Update owner BOM accepted status
                $business_application->owner_bom_accepted = config('constants.HIGHER_AUTHORITY.OWNER_BOM_ESTIMATION_ACCEPTED');

                // Optional: update off_canvas_status if needed
                $business_application->off_canvas_status = 32;

                // Save the updates
                $business_application->save();

                // Optional updates for admin and notification views

                // $update_data_admin = [
                //     'off_canvas_status' => 29,
                //     'is_view' => '0',
                // ];

                $update_data_business = [
                    'off_canvas_status' => 32,
                    'accepted_bom_estimated' => 0,
                ];

                // AdminView::where('business_details_id', $business_application->business_details_id)
                //     ->update($update_data_admin);

                NotificationStatus::where('business_details_id', $business_application->business_details_id)
                    ->update($update_data_business);
            }

            return $business_application;
        } catch (\Exception $e) {
            Log::error('Error in acceptEstimationBOM: ' . $e->getMessage());
            return response()->json(['error' => 'Something went wrong.'], 500);
        }
    }
    public function addRejectedEstimationBOM($request)
    {
        try {
            $idtoedit = base64_decode($request->business_id);

            // Get estimation data
            $estimation_data = EstimationModel::where('business_details_id', $idtoedit)->first();

            if (!$estimation_data) {
                throw new \Exception("Estimation data not found.");
            }

            // Get or update DesignRevisionForProd
            $designRevision = DesignRevisionForProd::where('business_details_id', $estimation_data->business_details_id)
                ->orderBy('id', 'desc')
                ->first();

            if ($designRevision) {
                $designRevision->business_id = $estimation_data->business_id;
                $designRevision->business_details_id = $estimation_data->business_details_id;
                $designRevision->design_id = $estimation_data->design_id;
                $designRevision->rejected_remark_by_owner = $request->rejected_remark_by_owner;
                $designRevision->save();
            }

            // Update business application process status
            $business_application = BusinessApplicationProcesses::where('business_details_id', $idtoedit)->first();

            if ($business_application) {
                $business_application->owner_bom_rejected = config('constants.HIGHER_AUTHORITY.OWNER_BOM_ESTIMATION_REJECTED');
                $business_application->off_canvas_status = 30;
                $business_application->save();
            }
            // Update NotificationStatus
            $update_data_business = [
                'off_canvas_status' => 30
            ];

            NotificationStatus::where('business_details_id', $estimation_data->business_details_id)
                ->update($update_data_business);
        } catch (\Exception $e) {
            Log::error('Error in rejectedEstimationBOM: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function acceptPurchaseOrder($purchase_order_id, $business_id)
    {
        try {
            $business_application = BusinessApplicationProcesses::where('business_details_id', $business_id)->first();
            $po_count = $this->serviceCommon->getNumberOfPOCount($business_id, $purchase_order_id);
            if ($business_application) {

                if ($po_count > 0) {
                    $business_application->business_status_id = config('constants.HIGHER_AUTHORITY.HALF_APPROVED_PO_FROM_PURCHASE');
                    $business_application->off_canvas_status = 24;
                } else {
                    $business_application->off_canvas_status = 24;
                }
                $business_application->save();
            }
            $PurchaseOrdersData = PurchaseOrdersModel::where('purchase_orders_id', $purchase_order_id)->first();
            $PurchaseOrdersData->owner_po_action_date = date('Y-m-d');
            $PurchaseOrdersData->finanace_store_receipt_status_id = config('constants.FINANCE_DEPARTMENT.INVOICE_APPROVED_FROM_HIGHER_AUTHORITY');
            $PurchaseOrdersData->save();
            $update_data_admin['off_canvas_status'] = 24;
            $update_data_business['off_canvas_status'] = 24;
            $update_data_admin['is_view'] = '0';
            $update_data_business['purchase_order_is_accepted_by_view'] = 0;
            AdminView::where('business_details_id', $business_application->business_details_id)
                ->update($update_data_admin);
            NotificationStatus::where('business_details_id', $business_application->business_details_id)
                ->update($update_data_business);

            return $business_application;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function rejectedPurchaseOrder($purchase_order_id, $business_id)
    {
        try {
            $business_application = BusinessApplicationProcesses::where('business_details_id', $business_id)->first();
            $po_count = $this->serviceCommon->getNumberOfPOCount($business_id, $purchase_order_id);
            if ($business_application) {

                if ($po_count > 0) {
                    // $business_application->business_status_id = config('constants.HIGHER_AUTHORITY.REJECTED_PO_FROM_OWNER');
                    $business_application->off_canvas_status = 23;
                } else {
                    $business_application->off_canvas_status = 23;
                }
                $business_application->save();
            }
            $PurchaseOrdersData = PurchaseOrdersModel::where('purchase_orders_id', $purchase_order_id)->first();
            $PurchaseOrdersData->owner_po_action_date = date('Y-m-d');
            $PurchaseOrdersData->purchase_status_from_owner = config('constants.HIGHER_AUTHORITY.REJECTED_PO_FROM_OWNER');
            // $PurchaseOrdersData->finanace_store_receipt_status_id = config('constants.FINANCE_DEPARTMENT.INVOICE_APPROVED_FROM_HIGHER_AUTHORITY');
            $PurchaseOrdersData->save();
            $update_data_admin['off_canvas_status'] = 23;
            $update_data_business['off_canvas_status'] = 23;
            $update_data_admin['is_view'] = '0';
            $update_data_business['purchase_order_is_rejected_view'] = 0;
            AdminView::where('business_details_id', $business_application->business_details_id)
                ->update($update_data_admin);
            NotificationStatus::where('business_details_id', $business_application->business_details_id)
                ->update($update_data_business);

            return $business_application;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function acceptPurchaseOrderPaymentRelease($purchase_order_id, $business_id)
    {
        try {
            // Retrieve the purchase order and GRN models
            $purchase_order = PurchaseOrdersModel::where('purchase_orders_id', $purchase_order_id)->first();
            $grn_update = GRNModel::where('id', $business_id)->first(); // Ensure you're fetching the correct GRN based on the business_id
            if ($grn_update) {
                $grn_update->grn_status_sanction = config('constants.HIGHER_AUTHORITY.INVOICE_RECEIVED_FOR_BILL_APPROVAL_TO_HIGHER_AUTHORITY_GRN_WISE');
                $grn_update->save();
                return [
                    'status' => 'success',
                    'message' => 'GRN and Purchase Order updated successfully.',
                    'grn' => $grn_update,
                    // 'purchase_order' => $purchase_order
                ];
            }

            // If either the GRN or purchase order does not exist
            return [
                'status' => 'error',
                'message' => 'GRN or Purchase Order not found.',
            ];
        } catch (\Exception $e) {
            // Catch and return any exceptions
            return [
                'status' => 'error',
                'message' => 'An error occurred while updating the GRN and Purchase Order.',
                'error' => $e->getMessage(),
            ];
        }
    }
    public function getPurchaseOrderDetails($purchase_order_id)
    {
        try {
            // Fetch the Purchase Order
            $purchaseOrder = PurchaseOrdersModel::where('purchase_orders_id', $purchase_order_id)
                ->select(
                    'id',
                    'purchase_orders_id',
                    'requisition_id',
                    'business_id',
                    'production_id',
                    'po_date',
                    'vendor_id',
                    'terms_condition',
                    'transport_dispatch',
                    'image',
                    'client_name',
                    'phone_number',
                    'email',
                    'tax_type',
                    'tax_id',
                    'invoice_date',
                    'gst_number',
                    'payment_terms',
                    'client_address',
                    'discount',
                    'note',
                    'created_at'
                )
                ->first();

            // Fetch related Purchase Order Details
            $purchaseOrderDetails = PurchaseOrderDetailsModel::where('purchase_id', $purchaseOrder->id)
                ->select(
                    'purchase_id',
                    'part_no_id',
                    'description',
                    'due_date',
                    'quantity',
                    'actual_quantity',
                    'accepted_quantity',
                    'rejected_quantity',
                    'rate',
                    'amount'
                )
                ->get();

            return [
                'purchaseOrder' => $purchaseOrder,
                'purchaseOrderDetails' => $purchaseOrderDetails,
            ];
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getPurchaseOrderBusinessWise($id)
    {
        try {
            $data_output = PurchaseOrdersModel::join('vendors', 'vendors.id', '=', 'purchase_orders.vendor_id')
                ->select(
                    'purchase_orders.id',
                    'purchase_orders.business_details_id',
                    'purchase_orders.purchase_orders_id',
                    'vendors.vendor_name',
                    'vendors.vendor_company_name',
                    'vendors.vendor_email',
                    'vendors.vendor_address',
                    'vendors.contact_no',
                    'vendors.gst_no',
                    'purchase_orders.is_active'
                )
                ->where('purchase_orders.business_details_id', $id)
                // ->get(); 

                // ->where('business_id', $id)
                ->whereNull('purchase_status_from_owner')
                ->orWhere('purchase_status_from_owner', config('constants.HIGHER_AUTHORITY.HALF_APPROVED_PO_FROM_PURCHASE'))
                ->orderBy('purchase_orders.updated_at', 'desc')
                ->get(); // Added to execute the query and get results

            return $data_output;
        } catch (\Exception $e) {
            return $e->getMessage(); // Changed to return the error message string
        }
    }
    public function getAllOrganizationData()
    {
        try {
            $dataOutputCategory = Business::join('tbl_organizations', 'tbl_organizations.id', '=', 'businesses.organization_id')
                ->select(
                    'tbl_organizations.id',
                    'tbl_organizations.company_name',
                    'tbl_organizations.email',
                    'tbl_organizations.mobile_number',
                    'tbl_organizations.address',
                    'tbl_organizations.image',
                )
                ->first();

            return $dataOutputCategory;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getByIdOrg($id)
    {
        try {
            $dataOutputByid = OrganizationModel::find($id);
            if ($dataOutputByid) {
                return $dataOutputByid;
            } else {
                return null;
            }
        } catch (\Exception $e) {
            return [
                'msg' => $e,
                'status' => 'error'
            ];
        }
    }
}
