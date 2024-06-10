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
    OrganizationModel
};
use Config;

class BusinessRepository
{


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
            $business_data->title = $request->title;
            $business_data->descriptions = $request->descriptions;
            $business_data->remarks = $request->remarks;
            $business_data->organization_id = $data_output;
            $business_data->save();

            $design_data = new DesignModel();
            $design_data->business_id = $business_data->id;
            $design_data->design_image = '';
            $design_data->bom_image = '';
            $design_data->save();


            $business_application = new BusinessApplicationProcesses();
            $business_application->business_id = $business_data->id;
            $business_application->business_status_id = config('constants.HIGHER_AUTHORITY.NEW_REQUIREMENTS_SENT_TO_DESIGN_DEPARTMENT');
            $business_application->design_id = $design_data->id;
            $business_application->design_status_id = config('constants.DESIGN_DEPARTMENT.LIST_NEW_REQUIREMENTS_RECEIVED_FOR_DESIGN');
            $business_application->production_id = '0';
            $business_application->production_status_id = '0';
            $business_application->save();

            return [
                'msg' => 'This business send to Design Department Successfully',
                'status' => 'success'
            ];

        } catch (\Exception $e) {
            dd($e);
            return [
                'msg' => $e->getMessage(),
                'status' => 'error'
            ];
        }
    }

    public function getById($id)
    {
        try {
            $dataOutputByid = Business::find($id);
            // dd($dataOutputByid);
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

    public function updateAll($request)
    {
        try {
            $dataOutput = Business::find($request->id);
            // dd($dataOutput);

            if (!$dataOutput) {
                return [
                    'msg' => 'Update Data not found.',
                    'status' => 'error'
                ];
            }

            $dataOutput->title = $request->title;
            $dataOutput->descriptions = $request->descriptions;
            $dataOutput->remarks = $request->remarks;


            $dataOutput->save();

            return [
                'msg' => 'This business send to Design Department Successfully',
                'status' => 'success'
            ];

        } catch (\Exception $e) {
            return [
                'msg' => 'Failed to Update Data.',
                'status' => 'error',
                'error' => $e->getMessage() // Return the error message for debugging purposes
            ];
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

    public function acceptPurchaseOrder($purchase_status_id)
    {
        try {
            
            $business_application = BusinessApplicationProcesses::where('purchase_order_id', $purchase_status_id)->first();
            // dd($business_application);
            if ($business_application) {
                $business_application->business_status_id = config('constants.HIGHER_AUTHORITY.APPROVED_PO_FROM_PURCHASE');
                $business_application->owner_po_action_date= date('Y-m-d');
                // $business_application->purchase_order_id= '0';
                $business_application->finanace_store_receipt_status_id = config('constants.FINANCE_DEPARTMENT.INVOICE_APPROVED_FROM_HIGHER_AUTHORITY');
                $business_application->save();
            }
            
            return $business_application;

        } catch (\Exception $e) {
            dd($e);
            return $e;
        }
    }

    
    public function getPurchaseOrderDetails($purchase_order_id)
{
    try {
        // Fetch the Purchase Order
        $purchaseOrder = PurchaseOrdersModel::where('id', $purchase_order_id)
            ->select(
                'id', 'purchase_orders_id', 'requisition_id', 'business_id', 'production_id',
                'po_date', 'vendor_id', 'terms_condition', 'transport_dispatch', 'image', 'status',
                'client_name', 'phone_number', 'email', 'tax', 'invoice_date', 'gst_number',
                'payment_terms', 'client_address', 'discount', 'note','created_at'
            )
            ->first();
          
        // Fetch related Purchase Order Details
        $purchaseOrderDetails = PurchaseOrderDetailsModel::where('purchase_id', $purchase_order_id)
            ->select(
                'purchase_id', 'part_no', 'description', 'qc_check_remark', 'due_date',
                'hsn_no', 'quantity', 'actual_quantity', 'accepted_quantity',
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
public function getPurchaseOrderBusinessWise($purchase_order_id)
{
    try {
        $data_output = PurchaseOrdersModel::select(
            'id', 'purchase_orders_id', 'requisition_id', 'business_id', 'production_id',
            'po_date', 'vendor_id', 'terms_condition', 'transport_dispatch', 'image', 'status',
            'client_name', 'phone_number', 'email', 'tax', 'invoice_date', 'gst_number',
            'payment_terms', 'client_address', 'discount', 'note', 'created_at'
        )
        ->where('purchase_orders_id', $purchase_order_id)
        ->get(); // Added to execute the query and get results
// dd($data_output);
// die();
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