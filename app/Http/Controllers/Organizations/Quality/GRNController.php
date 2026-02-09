<?php

namespace App\Http\Controllers\Organizations\Quality;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Services\Organizations\Quality\GRNServices;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Carbon\Carbon;
use Exception;
use App\Models\{
    PurchaseOrdersModel,
    PurchaseOrderDetailsModel,
    BusinessApplicationProcesses,
    NotificationStatus,
    Gatepass,
    GrnPOQuantityTracking
};

class GRNController extends Controller
{
    protected $service;

    public function __construct()
    {
        $this->service = new GRNServices();
    }



    public function index()
    {
        try {
            $all_gatepass = $this->service->getAll();

            if ($all_gatepass instanceof \Illuminate\Support\Collection && $all_gatepass->isNotEmpty()) {
                foreach ($all_gatepass as $data) {
                    $business_id = $data->business_details_id;

                    if (!empty($business_id)) {
                        $update_data['security_create_date_pass'] = '1';
                        NotificationStatus::where('security_create_date_pass', '0')
                            ->where('business_details_id', $business_id)
                            ->update($update_data);
                    }
                }
            }

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
            $purchase_order_data = PurchaseOrdersModel::where('purchase_orders_id', '=', $purchase_ordersId)->first();
            $po_id = $purchase_order_data->id;

            $purchase_order_details_data = PurchaseOrderDetailsModel::leftJoin('tbl_part_item', function ($join) {
                $join->on('purchase_order_details.part_no_id', '=', 'tbl_part_item.id');
            })
                ->leftJoin('tbl_hsn', function ($join) {
                    $join->on('purchase_order_details.hsn_id', '=', 'tbl_hsn.id');
                })
                ->leftJoin('tbl_unit', function ($join) {
                    $join->on('purchase_order_details.unit', '=', 'tbl_unit.id');
                })
                ->leftJoin('tbl_grn_po_quantity_tracking AS t', 't.part_no_id', '=', 'tbl_part_item.id')
                ->where('purchase_order_details.purchase_id', $po_id)
                ->select(
                    'purchase_order_details.*',
                    'purchase_order_details.created_at as po_date',
                    'tbl_part_item.description as part_description',
                    // 'tbl_part_item.part_number as description',
                    'tbl_unit.name as unit_name',
                    'tbl_hsn.name as hsn_name',
                    DB::raw('(SELECT SUM(t2.actual_quantity) 
                          FROM tbl_grn_po_quantity_tracking AS t2 
                          WHERE t2.purchase_order_id = purchase_order_details.purchase_id
                          AND t2.purchase_order_details_id = purchase_order_details.id
                          AND t2.part_no_id = tbl_part_item.id
                         ) AS sum_actual_quantity'),
                    DB::raw('(
    purchase_order_details.quantity - 
    COALESCE((
        SELECT SUM(t2.actual_quantity) 
        FROM tbl_grn_po_quantity_tracking AS t2 
        WHERE t2.purchase_order_id = purchase_order_details.purchase_id
        AND t2.purchase_order_details_id = purchase_order_details.id
        AND t2.part_no_id = tbl_part_item.id
    ),0)
) AS remaining_quantity')

                    //     DB::raw('(purchase_order_details.quantity - (SELECT SUM(t2.actual_quantity) 
                    //                                               FROM tbl_grn_po_quantity_tracking AS t2 
                    //                                               WHERE t2.purchase_order_id = purchase_order_details.purchase_id
                    //                                               AND t2.purchase_order_details_id = purchase_order_details.id
                    //                                               AND t2.part_no_id = tbl_part_item.id
                    //                                              )) AS remaining_quantity')
                )
                ->groupBy(
                    'purchase_order_details.id',
                    'purchase_order_details.purchase_id',
                    'purchase_order_details.part_no_id',
                    'purchase_order_details.description',
                    'purchase_order_details.discount',
                    'purchase_order_details.quantity',
                    'purchase_order_details.unit',
                    'purchase_order_details.hsn_id',
                    'purchase_order_details.actual_quantity',
                    'purchase_order_details.accepted_quantity',
                    'purchase_order_details.rejected_quantity',
                    'purchase_order_details.rate',
                    'purchase_order_details.amount',
                    'purchase_order_details.is_deleted',
                    'purchase_order_details.is_active',
                    'purchase_order_details.created_at',
                    'purchase_order_details.updated_at',
                    'tbl_part_item.id',
                    'tbl_part_item.description',
                    'tbl_part_item.part_number',
                    'tbl_unit.name',
                    'tbl_hsn.name'
                )
                ->get();
            $gatepassId = Gatepass::select('gatepass.id', 'gatepass.gatepass_name')
                ->where('gatepass.id', $gatepass_id) // Specify the table for clarity
                ->first();
            return view('organizations.quality.grn.add-grn', compact('purchase_order_data', 'purchase_order_details_data', 'gatepassId'));
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getBalanceQuantity(Request $request)
    {
        $purchaseOrderId = $request->input('purchase_order_id');
        $partNoId = $request->input('part_no_id');

        try {
            // Calculate the sum of actual quantities
            $sumActualQuantity = GrnPOQuantityTracking::leftJoin('tbl_part_item', function ($join) {
                $join->on('tbl_grn_po_quantity_tracking.part_no_id', '=', 'tbl_part_item.id');  // Updated column name
            })
                ->where('tbl_grn_po_quantity_tracking.purchase_order_id', $purchaseOrderId)
                ->where('tbl_grn_po_quantity_tracking.part_no_id', $partNoId)
                ->sum('tbl_grn_po_quantity_tracking.actual_quantity');

            // Get the total quantity from the purchase order details
            $totalQuantity = PurchaseOrderDetailsModel::leftJoin('tbl_part_item', function ($join) {
                $join->on('purchase_order_details.part_no_id', '=', 'tbl_part_item.id');  // Updated column name
            })
                ->where('purchase_order_details.purchase_id', $purchaseOrderId)
                ->where('purchase_order_details.part_no_id', $partNoId)  // Updated column name
                ->value('purchase_order_details.quantity');

            // Calculate balance quantity
            $balanceQuantity = $totalQuantity - $sumActualQuantity;

            // Return the result as JSON
            return response()->json([
                'balance_quantity' => $balanceQuantity >= 0 ? $balanceQuantity : 0,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $rules = [
            // 'grn_date' => 'required',
            // 'purchase_orders_id' => 'required',
            // 'po_date' => 'required',
        ];

        $messages = [
            // 'grn_date.required' => 'The Client Name is required.',
            // 'purchase_orders_id.required' => 'The purchase orders no is required.',
            // 'po_date.required' => 'The Email is required.',
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
                        return redirect('quality/list-material-sent-to-quality')->with(compact('msg', 'status'));
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
                // ->leftJoin('designs', function ($join) {
                //     $join->on('business_application_processes.business_details_id', '=', 'designs.business_details_id');
                // })
                ->leftJoin('businesses', function ($join) {
                    $join->on('business_application_processes.business_id', '=', 'businesses.id');
                })
                ->leftJoin('businesses_details', function ($join) {
                    $join->on('business_application_processes.business_details_id', '=', 'businesses_details.id');
                })
                // ->leftJoin('design_revision_for_prod', function ($join) {
                //     $join->on('business_application_processes.business_details_id', '=', 'design_revision_for_prod.business_details_id');
                // })
                ->leftJoin('purchase_orders', function ($join) {
                    $join->on('business_application_processes.business_details_id', '=', 'purchase_orders.business_details_id');
                })
                ->whereIn('purchase_orders.quality_status_id', $array_to_be_check)
                // ->whereIn('purchase_orders.store_receipt_no', $array_to_be_check_new)
                ->where('businesses.is_active', true)

                ->distinct('businesses.id')
                ->select(
                    'businesses_details.id',
                    'businesses_details.product_name',
                    'businesses.title',
                    'businesses_details.description',
                    'businesses.remarks',
                    'businesses.is_active',
                    'production.business_id',
                    'production.id as productionId',
                    // 'design_revision_for_prod.reject_reason_prod',
                    // 'design_revision_for_prod.id as design_revision_for_prod_id',
                    // 'designs.bom_image',
                    // 'designs.design_image',
                    'businesses.updated_at',

                )->orderBy('businesses.updated_at', 'desc')
                ->get();

            // if ($data_output->isNotEmpty()) {
            //     foreach ($data_output as $data) {
            //         $business_id = $data->id;
            //         if (!empty($business_id)) {
            //             $update_data['quality_create_grn'] = '1';
            //             NotificationStatus::where('quality_create_grn', '0')
            //                 ->where('business_id', $business_id)
            //                 ->update($update_data);
            //         }
            //     }
            // } else {
            //     return view('organizations.quality.list.list-checked-material-sent-to-store', [
            //         'data_output' => [],
            //         'message' => 'No data found'
            //     ]);
            // }
if ($data_output->isNotEmpty()) {

    // IMPORTANT: $data->id currently is businesses_details.id (because you selected only businesses_details.id)
    // So update should be on business_details_id (NOT business_id)

    $bdIds = $data_output->pluck('id')->filter()->unique()->values();

    if ($bdIds->isNotEmpty()) {
        NotificationStatus::where('quality_create_grn', 0)
            ->whereIn('business_details_id', $bdIds)
            ->update(['quality_create_grn' => 1]);
    }

} else {
    return view('organizations.quality.list.list-checked-material-sent-to-store', [
        'data_output' => [],
        'message' => 'No data found'
    ]);
}

            // return $data_output;
            return view('organizations.quality.list.list-checked-material-sent-to-store', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }

    // public function getAllListMaterialSentFromQualityBusinessWise(Request $request, $id)
    // {
    //     try {
    //         $data_output = $this->service->getAllListMaterialSentFromQualityBusinessWise($request, $id);
    //         if ($data_output->isNotEmpty()) {
    //             foreach ($data_output as $data) {
    //                 $business_id = $data->business_details_id;
    //                 if (!empty($business_id)) {
    //                     $update_data['quality_create_grn'] = '1';
    //                     NotificationStatus::where('quality_create_grn', '0')
    //                         ->where('business_details_id', $business_id)
    //                         ->update($update_data);
    //                 }
    //             }
    //         } else {
    //             return view('organizations.quality.list.list-checked-material-sent-to-store-businesswise', [
    //                 'data_output' => [],
    //                 'message' => 'No data found'
    //             ]);
    //         }

    //         return view('organizations.quality.list.list-checked-material-sent-to-store-businesswise', compact('data_output', 'id'));
    //     } catch (\Exception $e) {
    //         Log::error('Error in Controller: ' . $e->getMessage());
    //         return redirect()->back()->with('error', 'Something went wrong. Please try again.');
    //     }
    // }

public function getAllListMaterialSentFromQualityBusinessWise(Request $request, $id)
{
    try {
        $data_output = $this->service->getAllListMaterialSentFromQualityBusinessWise($request, $id);

        if ($data_output->isEmpty()) {
            return view('organizations.quality.list.list-checked-material-sent-to-store-businesswise', [
                'data_output' => [],
                'message' => 'No data found'
            ]);
        }

        $bdIds = $data_output->pluck('business_details_id')->filter()->unique()->values();

        if ($bdIds->isNotEmpty()) {
            NotificationStatus::where('quality_create_grn', 0)
                ->whereIn('business_details_id', $bdIds)
                ->update(['quality_create_grn' => 1]);
        }

        return view('organizations.quality.list.list-checked-material-sent-to-store-businesswise', compact('data_output', 'id'));
    } catch (\Exception $e) {
        Log::error('Error in Controller: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Something went wrong. Please try again.');
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
