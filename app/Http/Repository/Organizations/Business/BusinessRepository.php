<?php
namespace App\Http\Repository\Organizations\Business;

use Illuminate\Database\QueryException;
use DB;
use Illuminate\Support\Carbon;
use App\Models\{
    Business,
    DesignModel,
    BusinessApplicationProcesses,
    PurchaseOrderDetailsModel,
    PurchaseOrdersModel,
    OrganizationModel,
    Vendors,
    BusinessDetails
};
use Config;
use App\Http\Controllers\Organizations\CommanController;

class BusinessRepository
{

    public function __construct(){
        $this->serviceCommon = new CommanController();
    }


    public function getAll()
    {
        try {
            $data_output = Business::get();
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
            $business_data->title = $request->title;
            // $business_data->product_name = $request->product_name;
            // $business_data->quantity = $request->quantity;
            $business_data->remarks = $request->remarks;
            $business_data->po_validity = $request->po_validity;
            // $business_data->hsn_number = $request->hsn_number;
            $business_data->organization_id = $data_output;
            if (isset($request['customer_payment_terms'])) {
                $business_data->customer_payment_terms = $request['customer_payment_terms'];
            } 
            // if (isset($request['descriptions'])) {
            //     $business_data->descriptions = $request['descriptions'];
            // }
            if (isset($request['customer_terms_condition'])) {
                $business_data->customer_terms_condition = $request['customer_terms_condition'];
            }  
            // if (isset($request['remarks'])) {
            //     $business_data->remarks = $request['remarks'];
            // } 
            $business_data->save();
            $last_insert_id = $business_data->id;
            // Save data into DesignDetailsModel
            foreach ($request->addmore as $index => $item) {
                $businessDetails = new BusinessDetails();
                $businessDetails->business_id = $last_insert_id;
                $businessDetails->product_name = $item['product_name'];
                $businessDetails->description = $item['description'];
                $businessDetails->quantity = $item['quantity'];
                $businessDetails->rate = $item['rate'];
                // $businessDetails->unit = $item['unit'];
                $businessDetails->save();
            
            $design_data = new DesignModel();
            $design_data->business_id = $business_data->id;
            $design_data->business_details_id = $businessDetails->id;
            $design_data->design_image = '';
            $design_data->bom_image = '';
            $design_data->save();


            $business_application = new BusinessApplicationProcesses();
            $business_application->business_id = $business_data->id;
            $business_application->business_details_id = $businessDetails->id;
            $business_application->business_status_id = config('constants.HIGHER_AUTHORITY.NEW_REQUIREMENTS_SENT_TO_DESIGN_DEPARTMENT');
            $business_application->design_id = $design_data->id;
            $business_application->design_status_id = config('constants.DESIGN_DEPARTMENT.LIST_NEW_REQUIREMENTS_RECEIVED_FOR_DESIGN');
            $business_application->production_id = '0';
            $business_application->production_status_id = '0';
            $business_application->save();
        }

        

            return [
                'msg' => 'This business send to Design Department Successfully',
                'status' => 'success'
            ];

        } catch (\Exception $e) {
            
            return [
                'msg' => $e->getMessage(),
                'status' => 'error'
            ];
        }
    }

    // public function getById($id)
    // {
    //     try {
    //         $dataOutputByid = Business::find($id);
    //         if ($dataOutputByid) {
    //             return $dataOutputByid;
    //         } else {
    //             return null;
    //         }
    //     } catch (\Exception $e) {
    //         return [
    //             'msg' => $e,
    //             'status' => 'error'
    //         ];
    //     }
    public function getById($id) {
        try {
            $designData = Business::leftJoin('businesses_details', 'businesses.id', '=', 'businesses_details.business_id')
                ->select('businesses_details.*',
                'businesses_details.id as businesses_details_id',
                    'businesses.id as business_main_id',
                    'businesses.customer_po_number',
                    'businesses.title',
                    'businesses.po_validity',
                    'businesses.customer_payment_terms',
                    'businesses.customer_terms_condition',
                    'businesses.remarks')
                ->where('businesses.id', $id)
                ->get();
               
            if ($designData->isEmpty()) {
                return null;
            } else {
                return $designData;
            }
        } catch (\Exception $e) {
            return [
                'msg' => 'Failed to get by id Citizen Volunteer.',
                'status' => 'error',
                'error' => $e->getMessage(), 
            ];
        }
    }

    // public function getById($id) {
    //     try {
    //         $designData = Business::leftJoin('businesses_details', 'businesses.id', '=', 'businesses_details.business_id')
    //             ->select(
    //                 'businesses_details.*',
    //                 'businesses_details.id as businesses_details_id',
    //                 'businesses.id as business_main_id',
    //                 'businesses.customer_po_number',
    //                 'businesses.title',
    //                 'businesses.po_validity',
    //                 'businesses.customer_payment_terms',
    //                 'businesses.customer_terms_condition',
    //                 'businesses.remarks'
    //             )
    //             ->where('businesses_details.id', $id) // Checking by businesses_details.id

    //             ->first(); // Use first() instead of get() to retrieve a single record
            
    //             // dd($designData);
    //             // die();
    //         if (!$designData) {
    //             return null;
    //         } else {
    //             return $designData;
    //         }
    //     } catch (\Exception $e) {
    //         return [
    //             'msg' => 'Failed to get by id Citizen Volunteer.',
    //             'status' => 'error',
    //             'error' => $e->getMessage(), 
    //         ];
    //     }
    // }
    
    
        public function updateAll($request)
        {
            try {

                for ($i = 0; $i <= $request->design_count; $i++) {
                    $designDetails = BusinessDetails::findOrFail($request->input("design_id_" . $i));
                    $designDetails->product_name = $request->input("product_name_" . $i);
                    $designDetails->description = $request->input("description_" . $i);
                    $designDetails->quantity = $request->input("quantity_" . $i);
                    $designDetails->rate = $request->input("rate_" . $i);


                    $designDetails->save();
                }


                // Update existing design details
                // for ($i = 0; $i < $request->design_count; $i++) { // Change <= to <
                //     $designId = $request->input("design_id_" . $i);
                //     $designDetails = BusinessDetails::find($designId);
                //     dd($designDetails);
                //     die();
                //     if ($designDetails) {
                //         $designDetails->product_name = $request->input("product_name_" . $i);
                //         $designDetails->description = $request->input("description_" . $i);
                //         $designDetails->quantity = $request->input("quantity_" . $i);
                //         $designDetails->rate = $request->input("rate_" . $i);
                //         $designDetails->save();
                //     } else {
                //         // Handle the case where the design detail is not found
                //         return [
                //             'msg' => "Design detail with ID $designId not found.",
                //             'status' => 'error'
                //         ];
                //     }
                // }
        
                // Update main design data
                $dataOutput = Business::findOrFail($request->business_main_id);
                $dataOutput->customer_po_number = $request->customer_po_number;
                $dataOutput->title = $request->title;
                $dataOutput->po_validity = $request->po_validity;
                $dataOutput->remarks = $request->remarks;
                if (isset($request['customer_payment_terms'])) {
                    $dataOutput->customer_payment_terms = $request['customer_payment_terms'];
                } 
                if (isset($request['customer_terms_condition'])) {
                    $dataOutput->customer_terms_condition = $request['customer_terms_condition'];
                }  
                // if (isset($request['remarks'])) {
                //     $dataOutput->remarks = $request['remarks'];
                // } 

                
                $dataOutput->save();
        
                // Add new design details
                if ($request->has('addmore')) {
                    foreach ($request->addmore as $key => $item) {
                        $addDetails = new BusinessDetails();
                        $addDetails->business_id = $request->business_main_id; // Set the parent design ID
                        $addDetails->product_name = $item['product_name'];
                        $addDetails->description = $item['description'];
                        $addDetails->quantity = $item['quantity'];
                        $addDetails->rate = $item['rate'];

                        
                        $addDetails->save();
                    }
                }
                $last_insert_id = $dataOutput->id;
                $return_data['last_insert_id'] = $last_insert_id;
        
                // Returning success message
                return [
                    'msg' => 'Data updated successfully.',
                    'status' => 'success',
                    'addDetails' => $request->all()
                ];
            } catch (\Exception $e) {
                return [
                    'msg' => 'Failed to update data.',
                    'status' => 'error',
                    'error' => $e->getMessage()
                ];
            }
        }
        
        public function deleteByIdAddmore($id){
            try {
                $rti = BusinessDetails::find($id);
              
                if ($rti) {
                    $rti->delete();           
                    return $rti;
                } else {
                    return null;
                }
            } catch (\Exception $e) {
                return $e;
            }
        }
    public function deleteById($id)
    {
        try {
            $deleteDataById = Business::find($id);

            if ($deleteDataById) {
                $deleteDataById->delete();
                return $deleteDataById;
            } else {
                return null;
            }
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function acceptPurchaseOrder($purchase_order_id, $business_id)
    {
        try {
            $business_application = BusinessApplicationProcesses::where('business_details_id', $business_id)->first();
            $po_count = $this->serviceCommon->getNumberOfPOCount($business_id, $purchase_order_id);
            if ($business_application) {

                if($po_count > 0) {
                    $business_application->business_status_id = config('constants.HIGHER_AUTHORITY.HALF_APPROVED_PO_FROM_PURCHASE');
                } else {
                    $business_application->business_status_id = config('constants.HIGHER_AUTHORITY.APPROVED_PO_FROM_PURCHASE');
                }
                $business_application->save();
            }
            $PurchaseOrdersData = PurchaseOrdersModel::where('purchase_orders_id', $purchase_order_id)->first();
            $PurchaseOrdersData->owner_po_action_date= date('Y-m-d');
            $PurchaseOrdersData->finanace_store_receipt_status_id = config('constants.FINANCE_DEPARTMENT.INVOICE_APPROVED_FROM_HIGHER_AUTHORITY');
            $PurchaseOrdersData->save();

            return $business_application;

        } catch (\Exception $e) {
            return $e;
        }
    }

    
    public function getPurchaseOrderDetails($purchase_order_id)
{
    try {
        // Fetch the Purchase Order
        $purchaseOrder = PurchaseOrdersModel::where('purchase_orders_id', $purchase_order_id)
            ->select(
                'id', 'purchase_orders_id', 'requisition_id', 'business_id', 'production_id',
                'po_date', 'vendor_id', 'terms_condition', 'transport_dispatch', 'image',
                'client_name', 'phone_number', 'email', 'tax_type','tax_id', 'invoice_date', 'gst_number',
                'payment_terms', 'client_address', 'discount', 'note','created_at'
            )
            ->first();
            
        // Fetch related Purchase Order Details
        $purchaseOrderDetails = PurchaseOrderDetailsModel::where('purchase_id', $purchaseOrder->id)
            ->select(
                'purchase_id', 'part_no_id', 'description','due_date',
                 'quantity', 'actual_quantity', 'accepted_quantity',
                'rejected_quantity', 'rate', 'amount'
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

        ->get(); // Added to execute the query and get results
       
        return $data_output;
    } catch (\Exception $e) {
        return $e->getMessage(); // Changed to return the error message string
    }
}

public function getAllOrganizationData(){
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


public function getByIdOrg($id){
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