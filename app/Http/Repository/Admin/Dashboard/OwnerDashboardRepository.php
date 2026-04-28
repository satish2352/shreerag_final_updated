<?php

namespace App\Http\Repository\Admin\Dashboard;

use Illuminate\Support\Facades\DB;
use App\Models\{
    User,
    Business,
    BusinessDetails,
    BusinessApplicationProcesses,
    EstimationModel,
    ProductionDetails,
    Dispatch,
    RolesModel,
    AdminView
};

class OwnerDashboardRepository
{
    public function getCounts()
    {

        $user_active_count = User::leftJoin('tbl_roles', function ($join) {
            $join->on('users.role_id', '=', 'tbl_roles.id');
        })
            ->where('users.is_active', 1)
            ->where('users.is_deleted', 0)
            ->where('users.id', '>', 1)
            ->count();
        $active_count = Business::where('is_active', 1)->where('is_deleted', 0)->count();
        $business_details_count = BusinessDetails::where('is_active', 1)->where('is_deleted', 0)->count();
        $product_completed_count = Dispatch::leftJoin('business_application_processes', function ($join) {
            $join->on('tbl_dispatch.business_details_id', '=', 'business_application_processes.business_details_id');
        })
            ->leftJoin('tbl_customer_product_quantity_tracking', function ($join) {
                $join->on('tbl_dispatch.quantity_tracking_id', '=', 'tbl_customer_product_quantity_tracking.id');
            })
            ->leftJoin('businesses', function ($join) {
                $join->on('tbl_dispatch.business_id', '=', 'businesses.id');
            })
            ->where('tbl_customer_product_quantity_tracking.quantity_tracking_status', 3005)
            ->where('businesses.is_active', true)
            ->where('businesses.is_deleted', 0)
            ->distinct('tbl_dispatch.business_details_id')
            ->count('tbl_dispatch.business_details_id');


        $business_completed_count = BusinessApplicationProcesses::where('business_application_processes.is_active', 1)
            ->join('businesses_details', 'business_application_processes.business_details_id', '=', 'businesses_details.id')
            ->where('business_application_processes.dispatch_status_id', 1140)
            ->count();
        $business_inprocess_count = BusinessApplicationProcesses::where('is_active', 1)->where('is_deleted', 0)
            ->where(function ($query) {
                $query->orWhere('business_status_id', 1118)
                    ->orWhere('design_status_id', 1114)
                    ->orWhere('production_status_id', 1121)
                    ->orWhere('store_status_id', 1123)
                    ->orWhere('purchase_status_from_purchase', 1129)
                    ->orWhere('quality_status_id', 1134)
                    ->orWhere('logistics_status_id', 1145);
            })
            ->count();

        $product_inprocess_count = BusinessApplicationProcesses::where('is_active', 1)->where('is_deleted', 0)
            ->where(function ($query) {
                $query->orWhere('business_status_id', 1118)
                    ->orWhere('design_status_id', 1114)
                    ->orWhere('production_status_id', 1121)
                    ->orWhere('store_status_id', 1123)
                    ->orWhere('purchase_status_from_purchase', 1129)
                    ->orWhere('quality_status_id', 1134)
                    ->orWhere('logistics_status_id', 1145);
            })
            ->count();

        $total_revenu_count = EstimationModel::leftJoin('business_application_processes', function ($join) {
            $join->on('estimation.business_details_id', '=', 'business_application_processes.business_details_id');
        })
            ->where('business_application_processes.dispatch_status_id', 1154)
            ->where('business_application_processes.off_canvas_status', 22)
            ->sum('total_estimation_amount');

        $total_utilize_materila_amount = ProductionDetails::join('business_application_processes', function ($join) {
            $join->on('production_details.business_details_id', '=', 'business_application_processes.business_details_id');
        })
            ->where('business_application_processes.dispatch_status_id', 1154)
            ->where('business_application_processes.off_canvas_status', 22)
            ->sum('production_details.items_used_total_amount');


        $offcanvas = BusinessApplicationProcesses::leftJoin(
            'businesses',
            'business_application_processes.business_id',
            '=',
            'businesses.id'
        )
            ->leftJoin(
                'businesses_details',
                'business_application_processes.business_details_id',
                '=',
                'businesses_details.id'
            )
            ->leftJoin(
                'tbl_customer_product_quantity_tracking',
                'business_application_processes.business_details_id',
                '=',
                'tbl_customer_product_quantity_tracking.business_details_id'
            )
            ->leftJoin(
                'gatepass',
                'business_application_processes.business_details_id',
                '=',
                'gatepass.business_details_id'
            )
            ->where('businesses.is_active', 1)
            ->where('businesses.is_deleted', 0)
            ->select(
                'businesses.customer_po_number',
                'businesses.title',
                'businesses_details.product_name',
                'businesses.updated_at',
                'business_application_processes.off_canvas_status',
                'business_application_processes.dispatch_status_id',
                'tbl_customer_product_quantity_tracking.quantity_tracking_status',
                'tbl_customer_product_quantity_tracking.completed_quantity',
                'gatepass.po_tracking_status',
            )
            ->orderBy('businesses.updated_at', 'desc')
            ->get()
            ->groupBy('customer_po_number');


        // $total_revenue = EstimationModel::sum('total_estimation_amount');
        $total_revenue = EstimationModel::join(
            'business_application_processes',
            'estimation.business_details_id',
            '=',
            'business_application_processes.business_details_id'
        )
            ->where('business_application_processes.dispatch_status_id', 1154)
            ->sum('estimation.total_estimation_amount');

        // Total Material Amount
        $total_material_amount = ProductionDetails::join(
            'business_application_processes',
            'production_details.business_details_id',
            '=',
            'business_application_processes.business_details_id'
        )
            ->where('business_application_processes.dispatch_status_id', 1154)
            ->sum('production_details.items_used_total_amount');

        // Profit
        if ($total_revenue > 0) {
            $profit = $total_revenue - $total_material_amount;
        } else {
            $profit = 0; // ✅ no negative profit
        }


        // $total_material_amount = ProductionDetails::sum('items_used_total_amount');

        // $profit = $total_revenue - $total_material_amount;


        $department_count = RolesModel::where('is_active', 1)->where('is_deleted', 0)->count();

        // -------------------------------------------------------
        // Owner KPIs — real-time financial + operational metrics
        // -------------------------------------------------------

        // 1. Total business value (sum of all active customer PO grand totals)
        $total_business_value = (float) DB::table('businesses')
            ->where('is_active', 1)
            ->where('is_deleted', 0)
            ->sum('grand_total_amount');

        // 2. Total estimation (all active estimations, no dispatch filter)
        $total_estimation_all = (float) DB::table('estimation')
            ->join('business_application_processes', 'estimation.business_details_id', '=', 'business_application_processes.business_details_id')
            ->where('business_application_processes.is_active', 1)
            ->where('business_application_processes.is_deleted', 0)
            ->sum('estimation.total_estimation_amount');

        // 3. Total PO amount (quantity × rate across all non-deleted purchase orders)
        $total_po_amount = (float) DB::table('purchase_order_details')
            ->join('purchase_orders', 'purchase_order_details.purchase_id', '=', 'purchase_orders.id')
            ->where('purchase_orders.is_deleted', 0)
            ->selectRaw('SUM(purchase_order_details.quantity * purchase_order_details.rate) as total')
            ->value('total');

        // 4. GRN accepted amount (accepted_quantity × rate from tracking table)
        $total_grn_accepted_amount = (float) DB::table('tbl_grn_po_quantity_tracking')
            ->join('purchase_order_details', 'tbl_grn_po_quantity_tracking.purchase_order_details_id', '=', 'purchase_order_details.id')
            ->where('tbl_grn_po_quantity_tracking.is_deleted', 0)
            ->selectRaw('SUM(tbl_grn_po_quantity_tracking.accepted_quantity * purchase_order_details.rate) as total')
            ->value('total');

        // 5. Pending owner actions: PO approval (23), estimation review (28), exceed amount (50)
        $pending_owner_actions = (int) AdminView::where('is_view', 0)
            ->where('is_deleted', 0)
            ->whereIn('off_canvas_status', [23, 28, 50])
            ->count();

        return [


            'return_data' => [
                'user active count' => $user_active_count,
                'active_businesses' => $active_count,
                'business_details' => $business_details_count,
                'business_completed' => $business_completed_count ?? 0,
                'product_completed'  => $product_completed_count ?? 0,
                'business_inprocess' => $business_inprocess_count ?? 0,
                'product_inprocess'  => $product_inprocess_count ?? 0,
                // 'business_completed' => $business_completed_count,
                // 'product_completed' => $product_completed_count,
                // 'business_inprocess' => $business_inprocess_count,
                // 'product_inprocess' => $product_inprocess_count


            ],
            'business_total_amount' => [
                'total_revenu_count' => $total_revenu_count,
                'total_utilize_materila_amount' => $total_utilize_materila_amount,
                'profit' =>  $profit
            ],
            'offcanvas' => [
                'offcanvas' => $offcanvas,
            ],
            $department_count => [
                'department_total_count' => $department_count,
            ],
            'owner_kpis' => [
                'total_business_value'      => $total_business_value,
                'total_estimation_all'      => $total_estimation_all,
                'total_po_amount'           => $total_po_amount,
                'total_grn_accepted_amount' => $total_grn_accepted_amount,
                'pending_owner_actions'     => $pending_owner_actions,
                'products_in_progress'      => $business_inprocess_count,
                'products_completed'        => $product_completed_count,
            ],

        ];
    }
}
