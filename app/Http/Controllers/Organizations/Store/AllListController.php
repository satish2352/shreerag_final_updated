<?php

namespace App\Http\Controllers\Organizations\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Organizations\Store\AllListServices;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Exception;
use App\Models\{
    PurchaseOrdersModel,
    GRNModel,
    NotificationStatus,
    GrnPOQuantityTracking
};

class AllListController extends Controller
{
    protected $service;

    public function __construct()
    {
        $this->service = new AllListServices();
    }

    public function getAllListDesignRecievedForMaterial(Request $request)
    {
        try {
            $data_output = $this->service->getAllListDesignRecievedForMaterial();
            return view('organizations.store.list.list-accepted-design', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getAllListDesignRecievedForMaterialBusinessWise($business_id, Request $request)
    {
        try {
            $data_output = $this->service->getAllListDesignRecievedForMaterialBusinessWise($business_id);

            if ($data_output->isNotEmpty()) {
                foreach ($data_output as $data) {
                    $business_details_id = $data->business_details_id;
                    if (!empty($business_details_id)) {
                        $update_data['issue_material_send_req_to_store'] = '1';
                        NotificationStatus::where('issue_material_send_req_to_store', '0')
                            ->where('business_details_id', $business_details_id)
                            ->update($update_data);
                    }
                }
            }
            return view('organizations.store.list.list-accepted-design-business-wise', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }


    public function getAllListMaterialSentToProduction(Request $request)
    {
        try {
            $data_output = $this->service->getAllListMaterialSentToProduction();

            if ($data_output->isNotEmpty()) {
                foreach ($data_output as $data) {
                    $business_details_id = $data->id;
                    if (!empty($business_details_id)) {
                        $update_data['material_received_from_store'] = '1';
                        NotificationStatus::where('material_received_from_store', '0')
                            ->where('business_details_id', $business_details_id)
                            ->update($update_data);
                    }
                }
            } else {
                return view('organizations.store.list.list-material-sent-to-prod', [
                    'data_output' => [],
                    'message' => 'No data found for designs received for correction'
                ]);
            }

            return view('organizations.store.list.list-material-sent-to-prod', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }



    public function getAllListMaterialSentToPurchase()
    {

        try {
            $data_output = $this->service->getAllListMaterialSentToPurchase();
            return view('organizations.store.list.list-material-sent-to-purchase', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getAllListMaterialReceivedFromQuality()
    {
        try {
            $data_output = $this->service->getAllListMaterialReceivedFromQuality();
            return view('organizations.store.list.list-material-received-from-quality', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }


    public function submitFinalPurchaseOrder()
    {
        try {
            $data_output = $this->service->getPurchaseOrderBusinessWise();
            if (is_array($data_output) && count($data_output) > 0 || $data_output instanceof \Illuminate\Support\Collection && $data_output->isNotEmpty()) {
                foreach ($data_output as $data) {
                    $business_id = $data->business_details_id;
                    if (!empty($business_id)) {
                        $update_data['quality_create_grn'] = '1';
                        NotificationStatus::where('quality_create_grn', '0')
                            ->where('business_details_id', $business_id)
                            ->update($update_data);
                    }
                }
            } else {
                return view('organizations.store.list.list-material-received-from-quality-businesswise', [
                    'data_output' => [],
                    'message' => 'No data found'
                ]);
            }

            return view('organizations.store.list.list-material-received-from-quality-businesswise', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getAllListMaterialReceivedFromQualityPOTracking()
    {

        try {
            $data_output = $this->service->getAllListMaterialReceivedFromQualityPOTracking();
            return view('organizations.store.list.list-material-received-from-quality-po-tracking', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getAllListMaterialReceivedFromQualityPOTrackingBusinessWise()
    {
        try {
            $data_output = $this->service->getAllListMaterialReceivedFromQualityPOTrackingBusinessWise();

            return view('organizations.store.list.list-material-received-from-quality-businesswise-po-tracking', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getGRNDetails($purchase_orders_id, $business_details_id, $id)
    {
        try {
            $idtoedit = base64_decode($purchase_orders_id);
            $grn_id = base64_decode($id);
            $purchase_order_data = PurchaseOrdersModel::where('purchase_orders_id', '=', $idtoedit)->first();
            $grn_data = GRNModel::leftJoin('gatepass', function ($join) {
                $join->on('grn_tbl.gatepass_id', '=', 'gatepass.id');
            })
                ->where('grn_tbl.purchase_orders_id', '=', $idtoedit)->where('grn_tbl.id', '=', $grn_id)
                ->select(
                    'grn_tbl.*',
                    'gatepass.*',
                    'grn_tbl.remark as grn_remark'
                )
                ->first();
            $po_id = $purchase_order_data->id;
            $purchase_order_details_data = GrnPOQuantityTracking::leftJoin('tbl_part_item', 'tbl_grn_po_quantity_tracking.part_no_id', '=', 'tbl_part_item.id')
                ->leftJoin('purchase_order_details', 'tbl_grn_po_quantity_tracking.purchase_order_details_id', '=', 'purchase_order_details.id')
                ->leftJoin('tbl_unit', 'tbl_grn_po_quantity_tracking.unit', '=', 'tbl_unit.id')
                ->where('tbl_grn_po_quantity_tracking.purchase_order_id', $po_id)
                ->where('tbl_grn_po_quantity_tracking.grn_id', $grn_id)
                ->select(
                    'tbl_grn_po_quantity_tracking.purchase_order_id',
                    'tbl_grn_po_quantity_tracking.part_no_id',
                    'tbl_grn_po_quantity_tracking.purchase_order_details_id',
                    DB::raw('MAX(tbl_grn_po_quantity_tracking.quantity) as max_quantity'),
                    DB::raw('SUM(tbl_grn_po_quantity_tracking.actual_quantity) as sum_actual_quantity'),
                    DB::raw('SUM(tbl_grn_po_quantity_tracking.accepted_quantity) as tracking_accepted_quantity'),
                    DB::raw('SUM(tbl_grn_po_quantity_tracking.rejected_quantity) as tracking_rejected_quantity'),

                    DB::raw('(SELECT SUM(t2.actual_quantity) 
                  FROM tbl_grn_po_quantity_tracking AS t2 
                  WHERE t2.purchase_order_id = tbl_grn_po_quantity_tracking.purchase_order_id
                  AND t2.purchase_order_details_id = tbl_grn_po_quantity_tracking.purchase_order_details_id
                  AND t2.part_no_id = tbl_grn_po_quantity_tracking.part_no_id
                  AND t2.created_at <= tbl_grn_po_quantity_tracking.created_at) AS sum_grn_actual_quantity'),

                    DB::raw('(
            purchase_order_details.quantity - 
            (SELECT SUM(t2.actual_quantity) 
             FROM tbl_grn_po_quantity_tracking AS t2 
             WHERE t2.purchase_order_id = tbl_grn_po_quantity_tracking.purchase_order_id
             AND t2.purchase_order_details_id = tbl_grn_po_quantity_tracking.purchase_order_details_id
             AND t2.part_no_id = tbl_grn_po_quantity_tracking.part_no_id
             AND t2.created_at <= tbl_grn_po_quantity_tracking.created_at)
        ) AS remaining_quantity'),

                    'tbl_part_item.description as part_description',
                    'tbl_part_item.part_number',
                    'tbl_unit.name as unit_name',
                    DB::raw('MAX(purchase_order_details.description) as po_description'),
                    DB::raw('MAX(purchase_order_details.rate) as po_rate'),
                    DB::raw('MAX(purchase_order_details.discount) as po_discount'),
                    'tbl_grn_po_quantity_tracking.created_at', // Add created_at to SELECT
                    'tbl_grn_po_quantity_tracking.updated_at'
                )
                ->groupBy(
                    'tbl_grn_po_quantity_tracking.purchase_order_id',
                    'tbl_grn_po_quantity_tracking.part_no_id',
                    'tbl_grn_po_quantity_tracking.purchase_order_details_id',
                    'tbl_part_item.id',
                    'tbl_part_item.description',
                    'tbl_part_item.part_number',
                    'tbl_unit.name',
                    'purchase_order_details.quantity',
                    'tbl_grn_po_quantity_tracking.created_at', // Add created_at to GROUP BY
                    'tbl_grn_po_quantity_tracking.updated_at'
                )
                ->orderBy('tbl_grn_po_quantity_tracking.updated_at', 'desc')
                ->get();
            return view('organizations.store.list.list-grn', compact('purchase_order_data', 'purchase_order_details_data', 'grn_data', 'grn_id'));
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getGRNDetailsPOTracking($purchase_orders_id, $business_details_id, $id)
    {
        try {
            $business_details = $business_details_id;
            $idtoedit = base64_decode($purchase_orders_id);
            $grn_id = base64_decode($id);
            $purchase_order_data = PurchaseOrdersModel::where('purchase_orders_id', '=', $idtoedit)->first();
            $grn_data = GRNModel::leftJoin('gatepass', function ($join) {
                $join->on('grn_tbl.gatepass_id', '=', 'gatepass.id');
            })
                ->where('grn_tbl.purchase_orders_id', '=', $idtoedit)->where('grn_tbl.id', '=', $grn_id)
                ->select(
                    'grn_tbl.*',
                    'gatepass.*',
                    'grn_tbl.remark as grn_remark'
                )
                ->first();
            $po_id = $purchase_order_data->id;
            $purchase_order_details_data = GrnPOQuantityTracking::leftJoin('tbl_part_item', 'tbl_grn_po_quantity_tracking.part_no_id', '=', 'tbl_part_item.id')
                ->leftJoin('purchase_order_details', 'tbl_grn_po_quantity_tracking.purchase_order_details_id', '=', 'purchase_order_details.id')
                ->leftJoin('tbl_unit', 'tbl_grn_po_quantity_tracking.unit', '=', 'tbl_unit.id')
                ->where('tbl_grn_po_quantity_tracking.purchase_order_id', $po_id)
                ->where('tbl_grn_po_quantity_tracking.grn_id', $grn_id)
                ->select(
                    'tbl_grn_po_quantity_tracking.purchase_order_id',
                    'tbl_grn_po_quantity_tracking.part_no_id',
                    'tbl_grn_po_quantity_tracking.purchase_order_details_id',
                    DB::raw('MAX(tbl_grn_po_quantity_tracking.quantity) as max_quantity'),
                    DB::raw('SUM(tbl_grn_po_quantity_tracking.actual_quantity) as sum_actual_quantity'),
                    DB::raw('SUM(tbl_grn_po_quantity_tracking.accepted_quantity) as tracking_accepted_quantity'),
                    DB::raw('SUM(tbl_grn_po_quantity_tracking.rejected_quantity) as tracking_rejected_quantity'),

                    DB::raw('(SELECT SUM(t2.actual_quantity) 
                          FROM tbl_grn_po_quantity_tracking AS t2 
                          WHERE t2.purchase_order_id = tbl_grn_po_quantity_tracking.purchase_order_id
                          AND t2.purchase_order_details_id = tbl_grn_po_quantity_tracking.purchase_order_details_id
                          AND t2.part_no_id = tbl_grn_po_quantity_tracking.part_no_id
                          AND t2.created_at <= tbl_grn_po_quantity_tracking.created_at) AS sum_grn_actual_quantity'),

                    DB::raw('(
                    purchase_order_details.quantity - 
                    (SELECT SUM(t2.actual_quantity) 
                     FROM tbl_grn_po_quantity_tracking AS t2 
                     WHERE t2.purchase_order_id = tbl_grn_po_quantity_tracking.purchase_order_id
                     AND t2.purchase_order_details_id = tbl_grn_po_quantity_tracking.purchase_order_details_id
                     AND t2.part_no_id = tbl_grn_po_quantity_tracking.part_no_id
                     AND t2.created_at <= tbl_grn_po_quantity_tracking.created_at)
                ) AS remaining_quantity'),

                    'tbl_part_item.description as part_description',
                    'tbl_part_item.part_number',
                    'tbl_unit.name as unit_name',
                    DB::raw('MAX(purchase_order_details.description) as po_description'),
                    DB::raw('MAX(purchase_order_details.rate) as po_rate'),
                    DB::raw('MAX(purchase_order_details.discount) as po_discount'),
                    'tbl_grn_po_quantity_tracking.created_at' // Add created_at to SELECT
                )
                ->groupBy(
                    'tbl_grn_po_quantity_tracking.purchase_order_id',
                    'tbl_grn_po_quantity_tracking.part_no_id',
                    'tbl_grn_po_quantity_tracking.purchase_order_details_id',
                    'tbl_part_item.id',
                    'tbl_part_item.description',
                    'tbl_part_item.part_number',
                    'tbl_unit.name',
                    'purchase_order_details.quantity',
                    'tbl_grn_po_quantity_tracking.created_at' // Add created_at to GROUP BY
                )
                ->get();
            return view('organizations.store.list.list-grn-po-tracking', compact('purchase_order_data', 'purchase_order_details_data', 'grn_data', 'business_details'));
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getAllInprocessProductProduction()
    {
        try {
            $data_output = $this->service->getAllInprocessProductProduction();

            return view('organizations.store.list.list-material-received-from-production-inprocess', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }
}
