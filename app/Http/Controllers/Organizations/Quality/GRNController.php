<?php

namespace App\Http\Controllers\Organizations\Quality;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Organizations\Quality\GRNServices;
use Session;
use Validator;
use Config;
use Carbon;
use App\Models\{
    PurchaseOrdersModel,
    PurchaseOrderDetailsModel,
    BusinessApplicationProcesses,
    NotificationStatus,
    Gatepass
};

class GRNController extends Controller
{
    public function __construct()
    {
        $this->service = new GRNServices();
    }



    public function index()
    {
        try {
            $all_gatepass = $this->service->getAll();

            if ($all_gatepass->isNotEmpty()) {
                foreach ($all_gatepass as $data) {
                    $business_details = $data->business_details_id; 
                    if (!empty($business_details)) {
                        $update_data['quality_po_material_visible'] = '1';
                        NotificationStatus::where('quality_po_material_visible', '0')
                            ->where('id', $business_details)
                            ->update($update_data);
                    }
                }
            } else {
                return view('organizations.quality.grn.list-grn', [
                    'data_output' => [],
                    'message' => 'No data found for designs received for correction'
                ]);
            }
            // if ($all_gatepass->isNotEmpty()) {
            //     foreach ($all_gatepass as $data) {
            //         $business_details_id = $data->id; 
                    
            //         if (!empty($business_details_id)) {
            //             $update_data['quality_po_material_visible'] = '1';
            //             NotificationStatus::where('quality_po_material_visible', '0')
            //                 ->where('business_details_id', $business_details_id)
            //                 ->update($update_data);
            //         }
            //     }
            // } else {
            //     return view('organizations.quality.grn.list-grn', [
            //         'data_output' => [],
            //         'message' => 'No data found for designs received for correction'
            //     ]);
            // }
            return view('organizations.quality.grn.list-grn', compact('all_gatepass'));
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function add($purchase_orders_id, $id)
    {
        try {
            $purchase_ordersId = base64_decode($purchase_orders_id);

           $gatepass_id = base64_decode($id);

            // dd($gatepass_id);
            // die();
            $purchase_order_data = PurchaseOrdersModel::where('purchase_orders_id', '=', $purchase_ordersId)->first();
            $po_id = $purchase_order_data->id;
// dd($po_id);
// die();
            $purchase_order_details_data = PurchaseOrderDetailsModel::where('purchase_id', $po_id)
                ->get();
              
                // $gatepassId = Gatepass::join('grn_tbl', 'gatepass.id', '=', 'grn_tbl.gatepass_id')
                // ->where('id', $gatepass_id)
                // // ->first();
                // ->get();
                // dd($gatepassId);
                // die();
                // join('purchase_orders', 'gatepass.purchase_orders_id', '=', 'purchase_orders.purchase_orders_id')
            //     $gatepassId = Gatepass::
            //    ->select('gatepass.id')
                // where('purchase_orders_id', '=', $purchase_ordersId)
    // ->where('gatepass.id', $gatepass_id) // Specify the table name
//     ->first();
//   dd($gatepassId);
                // die();
                $gatepassId = Gatepass::select('gatepass.id')
    // ->join('grn_tbl', 'gatepass.id', '=', 'grn_tbl.gatepass_id')
    ->where('gatepass.id', $gatepass_id) // Specify the table for clarity
    ->first();

// dd($gatepassId);
// die();
            return view('organizations.quality.grn.add-grn', compact('purchase_order_data', 'purchase_order_details_data', 'gatepassId'));
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function store(Request $request)
    {
        $rules = [
            'grn_date' => 'required',
            'purchase_orders_id' => 'required',
            'po_date' => 'required',
        ];

        $messages = [
            'grn_date.required' => 'The Client Name is required.',
            'purchase_orders_id.required' => 'The purchase orders no is required.',
            'po_date.required' => 'The Email is required.',
        ];

        try {
            $validation = Validator::make($request->all(), $rules, $messages);

            if ($validation->fails()) {
                return redirect('quality/add-grn')
                    ->withInput()
                    ->withErrors($validation);
            } else {
                $add_record = $this->service->storeGRN($request);
                if ($add_record) {
                    $msg = $add_record['msg'];
                    $status = $add_record['status'];

                    if ($status == 'success') {
                        return redirect('quality/list-grn')->with(compact('msg', 'status'));
                    } else {
                        return redirect('quality/add-grn')->withInput()->with(compact('msg', 'status'));
                    }
                }
            }
        } catch (Exception $e) {
            return redirect('quality/add-grn')->withInput()->with(['msg' => $e->getMessage(), 'status' => 'error']);
        }
    }



    public function edit()
    {
        try {
            return view('organizations.quality.grn.edit-grn');
        } catch (\Exception $e) {
            return $e;
        }
    }



    public function getAllListMaterialSentFromQuality()
    {
        try {

            $array_to_be_check = [config('constants.QUALITY_DEPARTMENT.PO_CHECKED_OK_GRN_GENRATED_SENT_TO_STORE')];
            // $array_to_be_check_new = ['0'];

            $data_output = BusinessApplicationProcesses::leftJoin('production', function ($join) {
                $join->on('business_application_processes.business_details_id', '=', 'production.business_details_id');
            })
                ->leftJoin('designs', function ($join) {
                    $join->on('business_application_processes.business_details_id', '=', 'designs.business_details_id');
                })
                ->leftJoin('businesses', function ($join) {
                    $join->on('business_application_processes.business_id', '=', 'businesses.id');
                })
                ->leftJoin('businesses_details', function($join) {
                    $join->on('business_application_processes.business_details_id', '=', 'businesses_details.id');
                })
                ->leftJoin('design_revision_for_prod', function ($join) {
                    $join->on('business_application_processes.business_details_id', '=', 'design_revision_for_prod.business_details_id');
                })
                ->leftJoin('purchase_orders', function($join) {
                    $join->on('business_application_processes.business_details_id', '=', 'purchase_orders.business_details_id');
                  })
                ->whereIn('purchase_orders.quality_status_id', $array_to_be_check)
                // ->whereIn('purchase_orders.store_receipt_no', $array_to_be_check_new)
                ->where('businesses.is_active', true)

                ->distinct('businesses.id')
                ->select(
                    'businesses.id',
                    'businesses_details.product_name',
                    'businesses.title',
                    'businesses_details.description',
                    'businesses.remarks',
                    'businesses.is_active',
                    'production.business_id',
                    'production.id as productionId',
                    'design_revision_for_prod.reject_reason_prod',
                    'design_revision_for_prod.id as design_revision_for_prod_id',
                    'designs.bom_image',
                    'designs.design_image',
                    'businesses.updated_at', 

                )->orderBy('businesses.updated_at', 'desc')
                ->get();
               
            if ($data_output->isNotEmpty()) {
                foreach ($data_output as $data) {
                    $business_id = $data->id; 
                    if (!empty($business_id)) {
                        $update_data['quality_create_grn'] = '1';
                        NotificationStatus::where('quality_create_grn', '0')
                            ->where('business_id', $business_id)
                            ->update($update_data);
                    }
                }
            } else {
                return view('organizations.quality.list.list-checked-material-sent-to-store', [
                    'data_output' => [],
                    'message' => 'No data found'
                ]);
            }

            // return $data_output;
            return view('organizations.quality.list.list-checked-material-sent-to-store',compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getAllListMaterialSentFromQualityBusinessWise($id){
        try {
            $data_output = $this->service->getAllListMaterialSentFromQualityBusinessWise($id);
          
            return view('organizations.quality.list.list-checked-material-sent-to-store-businesswise', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getAllRejectedChalanList()
    {
        try {
            $all_gatepass = $this->service->getAllRejectedChalanList();
            return view('organizations.quality.list.list-rejected-chalan-po-wise', compact('all_gatepass'));
        } catch (\Exception $e) {
            return $e;
        }
    }
    

}