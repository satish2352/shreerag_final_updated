<?php

namespace App\Http\Controllers\Admin\Dashboard;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
// use App\Http\Services\DashboardServices;
use Illuminate\Support\Facades\DB;
use App\Models\{
    User,
    Business,
    BusinessApplicationProcesses,
    AdminView,
    Products,
    Testimonial,
    ProductServices,
    Team,
    DirectorDesk,
    ContactUs,
    VisionMission,
    VehicleType,
    DesignModel,
    Vendors,
    Tax,
    PartItem,
    PurchaseOrderModel,
    Gatepass,
    RejectedChalan,
    Leaves,
    LeaveManagement,
    Notice,
    TransportName,
    NotificationStatus,
    RolesModel,
    ProductionModel,
    DeliveryChalan,
    ReturnableChalan,
    BusinessDetails,
    Logistics,
    CustomerProductQuantityTracking,
    PurchaseOrdersModel,
    EstimationModel,
    ProductionDetails,
    Dispatch
};
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    /**
     * Topic constructor.
     */
    protected $service;

    public function __construct()
    {
        // $this->service = new DashboardServices();
    }
    public function index()
    {
        try {

            // Get the counts
            $department_count = RolesModel::where('is_active', 1)->where('is_deleted', 0)->count();

            // start owner====================
            $total_revenu_count = EstimationModel::leftJoin('business_application_processes', function ($join) {
                $join->on('estimation.business_details_id', '=', 'business_application_processes.business_details_id');
            })
                ->where('business_application_processes.dispatch_status_id', 1148)
                ->where('business_application_processes.off_canvas_status', 22)
                ->sum('total_estimation_amount');

            $total_utilize_materila_amount = ProductionDetails::join('business_application_processes', function ($join) {
                $join->on('production_details.business_details_id', '=', 'business_application_processes.business_details_id');
            })
                ->where('business_application_processes.dispatch_status_id', 1148)
                ->where('business_application_processes.off_canvas_status', 22)
                ->sum('production_details.items_used_total_amount');

            $user_active_count = User::leftJoin('tbl_roles', function ($join) {
                $join->on('users.role_id', '=', 'tbl_roles.id');
            })
                ->where('users.is_active', 1)
                ->where('users.is_deleted', 0)
                ->where('users.id', '>', 1)
                ->count();

            $profit = $total_revenu_count - $total_utilize_materila_amount;

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
            $offcanvas = BusinessApplicationProcesses::leftJoin('businesses', 'business_application_processes.business_id', '=', 'businesses.id')
                ->leftJoin('businesses_details', 'business_application_processes.business_details_id', '=', 'businesses_details.id')
                ->leftJoin('tbl_customer_product_quantity_tracking', 'business_application_processes.business_details_id', '=', 'tbl_customer_product_quantity_tracking.business_details_id')
                ->leftJoin('gatepass', 'business_application_processes.business_details_id', '=', 'gatepass.business_details_id')
                ->where('businesses.is_active', 1)
                ->where('businesses.is_deleted', 0)
                ->select(
                    'businesses.customer_po_number',
                    'businesses.title',
                    'businesses_details.product_name',
                    'businesses.updated_at',
                    'business_application_processes.off_canvas_status',
                    'tbl_customer_product_quantity_tracking.quantity_tracking_status',
                    'tbl_customer_product_quantity_tracking.completed_quantity',
                    'gatepass.po_tracking_status',
                )
                ->orderBy('businesses.updated_at', 'desc')
                ->get()
                ->groupBy('customer_po_number');

            $product_count = Products::where('is_active', 1)->where('is_deleted', 0)->count();
            // end owner========================

            $testimonial_count = Testimonial::where('is_active', 1)->where('is_deleted', 0)->count();
            $product_services_count = ProductServices::where('is_active', 1)->where('is_deleted', 0)->count();
            $team_count = Team::where('is_active', 1)->count();
            $contact_us_count = ContactUs::where('is_active', 1)->count();
            $vision_mission_count = VisionMission::where('is_active', 1)->count();
            $director_desk_count = DirectorDesk::where('is_active', 1)->count();
            $need_to_check_for_payment = PurchaseOrdersModel::leftJoin('grn_tbl', function ($join) {
                $join->on('purchase_orders.purchase_orders_id', '=', 'grn_tbl.purchase_orders_id');
            })
                ->where('grn_tbl.grn_status_sanction', 6000)
                ->whereNotNull('grn_tbl.grn_no_generate')
                ->whereNotNull('grn_tbl.store_receipt_no_generate')
                ->whereNotNull('grn_tbl.store_remark')
                ->where('grn_tbl.is_active', 1)
                ->where('grn_tbl.is_deleted', 0)
                ->count();
            $production_completed_prod_dept_logisitics = PurchaseOrdersModel::leftJoin('grn_tbl', function ($join) {
                $join->on('purchase_orders.purchase_orders_id', '=', 'grn_tbl.purchase_orders_id');
            })
                ->where('grn_tbl.grn_status_sanction', 6001)
                ->whereNotNull('grn_tbl.grn_no_generate')
                ->whereNotNull('grn_tbl.store_receipt_no_generate')
                ->whereNotNull('grn_tbl.store_remark')
                ->where('grn_tbl.is_active', 1)
                ->where('grn_tbl.is_deleted', 0)
                ->count();
            $po_pyament_need_to_release = PurchaseOrdersModel::leftJoin('grn_tbl', function ($join) {
                $join->on('purchase_orders.purchase_orders_id', '=', 'grn_tbl.purchase_orders_id');
            })
                ->where('grn_tbl.grn_status_sanction', 6003)
                ->whereNotNull('grn_tbl.grn_no_generate')
                ->whereNotNull('grn_tbl.store_receipt_no_generate')
                ->whereNotNull('grn_tbl.store_remark')
                ->where('grn_tbl.is_active', 1)
                ->where('grn_tbl.is_deleted', 0)
                ->count();
            $logistics_list_count = BusinessApplicationProcesses::where('logistics_status_id', 1145)->where('off_canvas_status', 19)
                ->where('is_active', 1)->count();
            $logistics_send_by_finance_count = Logistics::leftJoin('tbl_customer_product_quantity_tracking', function ($join) {
                $join->on('tbl_logistics.quantity_tracking_id', '=', 'tbl_customer_product_quantity_tracking.id');
            })
                ->leftJoin('businesses', function ($join) {
                    $join->on('tbl_logistics.business_id', '=', 'businesses.id');
                })
                ->where('tbl_customer_product_quantity_tracking.logistics_list_status', 'Send_Fianance')
                ->where('businesses.is_active', true)
                ->where('businesses.is_deleted', 0)
                ->count();

            $vehicle_type_count = VehicleType::where('is_active', 1)->count();
            $transport_name_count = TransportName::where('is_active', 1)->count();
            $logistics_send_by_finance_received_fianance_count = BusinessApplicationProcesses::where('logistics_status_id', 1146)->where('off_canvas_status', 20)
                ->where('is_active', 1)->count();
            $fianance_send_to_dispatch_count = CustomerProductQuantityTracking::leftJoin('tbl_logistics', function ($join) {
                $join->on('tbl_customer_product_quantity_tracking.id', '=', 'tbl_logistics.quantity_tracking_id');
            })
                ->leftJoin('businesses', function ($join) {
                    $join->on('tbl_customer_product_quantity_tracking.business_id', '=', 'businesses.id');
                })

                ->where('businesses.is_active', true)
                ->where('businesses.is_deleted', 0)
                ->where('tbl_customer_product_quantity_tracking.fianace_list_status', 'Send_Dispatch')
                ->count();
            $array_to_be_check = [config('constants.DESIGN_DEPARTMENT.LIST_NEW_REQUIREMENTS_RECEIVED_FOR_DESIGN')];
            $business_received_for_designs = DesignModel::leftJoin('business_application_processes', function ($join) {
                $join->on('designs.business_details_id', '=', 'business_application_processes.business_details_id');
            })
                ->whereIn('business_application_processes.design_status_id', $array_to_be_check)
                ->where('business_application_processes.is_active', true)
                ->where('business_application_processes.is_deleted', 0)
                //   ->distinct('businesses.id')
                ->count();
            $array_to_be_check_send_production = [
                config('constants.DESIGN_DEPARTMENT.LIST_NEW_REQUIREMENTS_RECEIVED_FOR_DESIGN'),
                config('constants.PRODUCTION_DEPARTMENT.LIST_DESIGN_RECEIVED_FOR_PRODUCTION'),
                config('constants.PRODUCTION_DEPARTMENT.LIST_DESIGN_RECIVED_FROM_PRODUCTION_DEPT_REVISED'),
            ];

            $design_sent_for_production = ProductionModel::leftJoin('businesses', function ($join) {
                $join->on('production.business_id', '=', 'businesses.id');
            })
                ->leftJoin('business_application_processes', function ($join) {
                    $join->on('production.business_id', '=', 'business_application_processes.business_id');
                })
                ->leftJoin('designs', function ($join) {
                    $join->on('production.business_details_id', '=', 'designs.business_id');
                })
                ->whereIn('business_application_processes.production_status_id', $array_to_be_check_send_production)
                ->where('businesses.is_active', true)
                ->where('businesses.is_deleted', 0)
                ->selectRaw('COUNT(DISTINCT businesses.id) as total_count')
                ->value('total_count');


            $accepted_design_production_dept = BusinessApplicationProcesses::where('business_status_id', 1112)->where('design_status_id', 1114)
                ->where('production_status_id', 1114)
                ->where('is_deleted', 0)
                ->where('is_active', 1)->count();
            $rejected_design_production_dept = BusinessApplicationProcesses::leftJoin('production', function ($join) {
                $join->on('business_application_processes.business_details_id', '=', 'production.business_details_id');
            })
                ->leftJoin('designs', function ($join) {
                    $join->on('business_application_processes.business_details_id', '=', 'designs.business_details_id');
                })
                ->leftJoin('businesses_details', function ($join) {
                    $join->on('production.business_details_id', '=', 'businesses_details.id');
                })
                ->leftJoin('businesses', function ($join) {
                    $join->on('business_application_processes.business_id', '=', 'businesses.id');
                })
                ->where('business_application_processes.production_status_id', 1115)
                ->where('businesses.is_active', true)
                ->where('businesses.is_deleted', 0)
                ->count();
            $design_recived_for_production = BusinessApplicationProcesses::where('business_status_id', 1112)->where('estimation_send_to_production', 1152)
                ->where('off_canvas_status', 33)
                ->where('is_deleted', 0)
                ->where('is_active', 1)->count();

            $accepted_and_sent_to_store = BusinessApplicationProcesses::where('business_status_id', 1112)->where('design_status_id', 1114)
                ->where('production_status_id', 1114)
                ->where('is_deleted', 0)
                ->where('is_active', 1)->count();
            $rejected_design_list_sent = BusinessApplicationProcesses::leftJoin('production', function ($join) {
                $join->on('business_application_processes.business_details_id', '=', 'production.business_details_id');
            })
                ->leftJoin('designs', function ($join) {
                    $join->on('business_application_processes.business_details_id', '=', 'designs.business_details_id');
                })
                ->leftJoin('businesses_details', function ($join) {
                    $join->on('production.business_details_id', '=', 'businesses_details.id');
                })
                ->leftJoin('businesses', function ($join) {
                    $join->on('business_application_processes.business_id', '=', 'businesses.id');
                })
                ->where('business_application_processes.production_status_id', 1115)
                ->where('businesses.is_active', true)
                ->where('businesses.is_deleted', 0)
                ->count();
            $corected_design_list_recived = BusinessApplicationProcesses::where('business_status_id', 1116)->where('design_status_id', 1116)
                ->where('production_status_id', 1116)
                ->where('is_deleted', 0)
                ->where('is_active', 1)->count();

            $material_received_for_production = BusinessApplicationProcesses::leftJoin('production', function ($join) {
                $join->on('business_application_processes.business_details_id', '=', 'production.business_details_id');
            })
                ->leftJoin('designs', function ($join) {
                    $join->on('business_application_processes.business_details_id', '=', 'designs.business_details_id');
                })
                ->leftJoin('businesses', function ($join) {
                    $join->on('business_application_processes.business_id', '=', 'businesses.id');
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
                ->where('business_application_processes.off_canvas_status', 17)
                ->where('production.production_status_quantity_tracking', 'incomplete')
                ->where('businesses.is_deleted', 0)
                ->where('business_application_processes.is_active', 1)
                ->count();

            $production_completed_prod_dept = CustomerProductQuantityTracking::leftJoin('tbl_logistics', function ($join) {
                $join->on('tbl_customer_product_quantity_tracking.id', '=', 'tbl_logistics.quantity_tracking_id');
            })
                ->leftJoin('businesses', function ($join) {
                    $join->on('tbl_customer_product_quantity_tracking.business_id', '=', 'businesses.id');
                })
                ->where('tbl_customer_product_quantity_tracking.quantity_tracking_status', 3001)
                ->where('businesses.is_active', true)
                ->where('businesses.is_deleted', 0)
                ->count();
            $material_need_to_sent_to_production = BusinessApplicationProcesses::where('business_status_id', 1112)->where('design_status_id', 1114)
                ->where('production_status_id', 1114)->where('off_canvas_status', 15)
                ->where('is_deleted', 0)
                ->where('is_active', 1)->count();
            $material_for_purchase = BusinessApplicationProcesses::where('store_status_id', 1123)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->count();
            $material_received_from_quality = BusinessApplicationProcesses::leftJoin('purchase_orders', function ($join) {
                $join->on('business_application_processes.business_details_id', '=', 'purchase_orders.business_details_id');
            })
                ->leftJoin('businesses', function ($join) {
                    $join->on('business_application_processes.business_id', '=', 'businesses.id');
                })
                ->where('purchase_orders.quality_status_id', 1134)
                ->where('businesses.is_active', true)
                ->where('businesses.is_deleted', 0)
                ->count();

            $rejected_chalan = RejectedChalan::join('grn_tbl', 'grn_tbl.purchase_orders_id', '=', 'tbl_rejected_chalan.purchase_orders_id')
                ->leftJoin('gatepass', function ($join) {
                    $join->on('grn_tbl.gatepass_id', '=', 'gatepass.id');
                })
                ->where('tbl_rejected_chalan.is_active', true)
                ->where('tbl_rejected_chalan.is_deleted', 0)
                ->where('tbl_rejected_chalan.chalan_no', '<>', '')
                ->count();
            $delivery_chalan = DeliveryChalan::where('is_active', 1)->where('is_deleted', 0)->count();
            $returnable_chalan = ReturnableChalan::where('is_active', 1)->where('is_deleted', 0)->count();
            $BOM_recived_for_purchase = PurchaseOrderModel::where('is_active', 1)->count();
            $vendor_list = Vendors::where('is_active', 1)->count();
            $tax = Tax::where('is_active', 1)->count();
            $part_item = PartItem::where('is_active', 1)->count();
            $purchase_order_approved = PurchaseOrderModel::where('purchase_status_from_owner', 1127)->where('purchase_status_from_purchase', 1126)
                ->where('is_active', 1)->count();
            $purchase_order_submited_by_vendor = PurchaseOrderModel::where('purchase_status_from_owner', 1129)->where('purchase_status_from_purchase', 1129)
                ->where('is_active', 1)->count();

            $get_pass = Gatepass::where('is_active', 1)->where('is_deleted', 0)->count();
            $GRN_genration = Gatepass::leftJoin('purchase_orders', function ($join) {
                $join->on('gatepass.purchase_orders_id', '=', 'purchase_orders.purchase_orders_id');
            })
                ->where('gatepass.po_tracking_status', 4001)->where('gatepass.is_active', 1)->where('gatepass.is_deleted', 0)->count();
            $material_need_to_sent_to_store = BusinessApplicationProcesses::leftJoin('purchase_orders', function ($join) {
                $join->on('business_application_processes.business_details_id', '=', 'purchase_orders.business_details_id');
            })
                ->leftJoin('businesses', function ($join) {
                    $join->on('business_application_processes.business_id', '=', 'businesses.id');
                })
                ->where('purchase_orders.quality_status_id', 1134)
                ->where('businesses.is_active', true)
                ->where('businesses.is_deleted', 0)
                ->count();
            $rejected_chalan_po_wise = RejectedChalan::join('grn_tbl', 'grn_tbl.purchase_orders_id', '=', 'tbl_rejected_chalan.purchase_orders_id')
                ->leftJoin('gatepass', function ($join) {
                    $join->on('grn_tbl.gatepass_id', '=', 'gatepass.id');
                })
                ->where('tbl_rejected_chalan.is_active', true)
                ->where('tbl_rejected_chalan.is_deleted', 0)
                ->where('tbl_rejected_chalan.chalan_no', '<>', '')
                ->count();
            $dispatch_received_from_finance = BusinessApplicationProcesses::where('logistics_status_id', 1146)->where('off_canvas_status', 21)
                ->where('dispatch_status_id', 1147)
                ->where('is_active', 1)->count();
            // $dispatch_completed = BusinessApplicationProcesses::where('logistics_status_id', 1146)->where('off_canvas_status',22)
            // ->where('dispatch_status_id', 1148)
            // ->where('is_active',1)->count();

            $dispatch_completed = Logistics::leftJoin('tbl_customer_product_quantity_tracking', function ($join) {
                $join->on('tbl_logistics.quantity_tracking_id', '=', 'tbl_customer_product_quantity_tracking.id');
            })
                ->leftJoin('businesses', function ($join) {
                    $join->on('tbl_logistics.business_id', '=', 'businesses.id');
                })
                ->where('tbl_customer_product_quantity_tracking.quantity_tracking_status', 3005)
                ->where('businesses.is_active', true)
                ->where('businesses.is_deleted', 0)->count();


            $material_need_to_sent_to_production_inventory = BusinessApplicationProcesses::where('business_status_id', 1112)->where('design_status_id', 1114)
                ->where('production_status_id', 1114)->where('off_canvas_status', 15)
                ->where('is_active', 1)->count();
            $part_item_inventory = PartItem::where('is_active', 1)->where('is_deleted', 0)->count();
            $leave_request = Leaves::where(['is_active' => 1, 'is_approved' => 0])->where('is_deleted', 0)->count();
            $accepted_leave_request = Leaves::where(['is_active' => 1, 'is_approved' => 2])->where('is_deleted', 0)->count();
            $rejected__leave_request = Leaves::where(['is_active' => 1, 'is_approved' => 1])->where('is_deleted', 0)->count();
            $total_employee = User::where('is_active', 1)->where('is_deleted', 0)->where('id', '!=', 1)->count();






            $total_leaves_type = LeaveManagement::where('is_active', 1)->where('is_deleted', 0)->count();
           
            $total_notice = Notice::where('is_active', 1)->where('is_deleted', 0)->count();

            $ses_userId = session()->get('user_id');

            $employee_leave_request = Leaves::leftJoin('users', function ($join) {
                $join->on('tbl_leaves.employee_id', '=', 'users.id');
            })
                ->where('users.id', $ses_userId)
                ->where('tbl_leaves.is_active', 1)
                ->where('tbl_leaves.is_approved', 0)
                ->where('tbl_leaves.is_deleted', 0)
                ->count();



            // $user_leaves_status = User::crossJoin('tbl_leave_management') 
            // ->leftJoin('tbl_leaves', function($join) use ($ses_userId) {
            //     $join->on('users.id', '=', 'tbl_leaves.employee_id')
            //         ->on('tbl_leave_management.id', '=', 'tbl_leaves.leave_type_id')
            //         ->where('tbl_leaves.is_approved', 2);
            // })
            // ->where('users.id', $ses_userId)
            // ->where('tbl_leave_management.is_active', 1)
            // ->where('tbl_leave_management.is_deleted', 0)
            // ->select(
            //     'tbl_leave_management.name as leave_type_name',
            //     'tbl_leave_management.leave_count',
            //     DB::raw('COALESCE(SUM(tbl_leaves.leave_count), 0) as total_leaves_taken'),
            //     DB::raw('tbl_leave_management.leave_count - COALESCE(SUM(tbl_leaves.leave_count), 0) as remaining_leaves'),
            // )
            // ->groupBy(
            //     'tbl_leave_management.id',
            //     'tbl_leave_management.name',
            //     'tbl_leave_management.leave_count'
            // )
            // ->get();
         $currentYear = date('Y');
$previousYear = $currentYear - 1;

/* -------------------------------------------
   PREVIOUS YEAR PENDING LEAVES
--------------------------------------------*/
$previousYearPending = DB::table('tbl_leave_management')
    ->leftJoin('tbl_leaves', function ($join) use ($ses_userId, $previousYear) {
        $join->on('tbl_leave_management.id', '=', 'tbl_leaves.leave_type_id')
            ->where('tbl_leaves.employee_id', $ses_userId)
            ->where('tbl_leaves.is_approved', 2)
            ->whereYear('tbl_leaves.leave_start_date', $previousYear);
    })
    ->where('tbl_leave_management.leave_year', $previousYear)
    ->select(
        'tbl_leave_management.name',
        'tbl_leave_management.leave_count',
        DB::raw('tbl_leave_management.leave_count - COALESCE(SUM(tbl_leaves.leave_count), 0) AS pending_carry_forward')
    )
    ->groupBy('tbl_leave_management.name', 'tbl_leave_management.leave_count')
    ->get()
    ->keyBy('name');

/* -------------------------------------------
   CURRENT YEAR LEAVES
--------------------------------------------*/
$user_leaves_status = DB::table('tbl_leave_management')
    ->leftJoin('tbl_leaves', function ($join) use ($ses_userId) {
        $join->on('tbl_leave_management.id', '=', 'tbl_leaves.leave_type_id')
            ->where('tbl_leaves.employee_id', $ses_userId)
            ->where('tbl_leaves.is_approved', 2);
    })
    ->where('tbl_leave_management.leave_year', $currentYear)
    ->where('tbl_leave_management.is_active', 1)
    ->where('tbl_leave_management.is_deleted', 0)
    ->select(
        'tbl_leave_management.id',
        'tbl_leave_management.name as leave_type_name',
        'tbl_leave_management.leave_count as current_year_leave',
        DB::raw('COALESCE(SUM(tbl_leaves.leave_count), 0) AS total_leaves_taken')
    )
    ->groupBy('tbl_leave_management.id', 'tbl_leave_management.name', 'tbl_leave_management.leave_count')
    ->get();

/* -------------------------------------------
   MERGE CARRY FORWARD + FINAL BALANCE
--------------------------------------------*/
foreach ($user_leaves_status as $item) {

    $carryForward = $previousYearPending[$item->leave_type_name]->pending_carry_forward ?? 0;

    $item->carry_forward = $carryForward;

    $item->total_available_leaves = $item->current_year_leave + $carryForward;

    $item->remaining_leaves = 
        $item->total_available_leaves - $item->total_leaves_taken;
}

            // $currentYear = date('Y');

            // $user_leaves_status = DB::table('tbl_leave_management')
            //     ->leftJoin('tbl_leaves', function ($join) use ($ses_userId) {
            //         $join->on('tbl_leave_management.id', '=', 'tbl_leaves.leave_type_id')
            //             ->where('tbl_leaves.employee_id', $ses_userId)
            //             ->where('tbl_leaves.is_approved', 2);
            //     })
            //     ->where('tbl_leave_management.leave_year', $currentYear)
            //     ->where('tbl_leave_management.is_active', 1)
            //     ->where('tbl_leave_management.is_deleted', 0)
            //     ->select(
            //         'tbl_leave_management.name as leave_type_name',
            //         'tbl_leave_management.leave_count',
            //         DB::raw('COALESCE(SUM(tbl_leaves.leave_count), 0) as total_leaves_taken'),
            //         DB::raw('tbl_leave_management.leave_count - COALESCE(SUM(tbl_leaves.leave_count), 0) as remaining_leaves')
            //     )
            //     ->groupBy('tbl_leave_management.id', 'tbl_leave_management.name', 'tbl_leave_management.leave_count')
            //     ->get();

            $employee_accepted_leave_request = Leaves::leftJoin('users', function ($join) {
                $join->on('tbl_leaves.employee_id', '=', 'users.id');
            })
                ->where('users.id', $ses_userId)
                ->where('tbl_leaves.is_active', 1)
                ->where('tbl_leaves.is_approved', 2)
                ->where('tbl_leaves.is_deleted', 0)
                ->count();
            $employee_rejected_leave_request = Leaves::leftJoin('users', function ($join) {
                $join->on('tbl_leaves.employee_id', '=', 'users.id');
            })
                ->where('users.id', $ses_userId)
                ->where('tbl_leaves.is_active', 1)
                ->where('tbl_leaves.is_approved', 1)
                ->where('tbl_leaves.is_deleted', 0)
                ->count();
            // $employee_leave_type= LeaveManagement::where('is_active',1)->get();
            $employee_leave_type = LeaveManagement::where('is_active', 1)->where('is_deleted', 0)
                ->select('name', 'leave_count')
                ->get();

            $total_leaves = LeaveManagement::where('is_active', 1)
                ->where('is_deleted', 0)
                ->sum('leave_count');

            $available_leaves = LeaveManagement::where('leave_year', $currentYear)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->sum('leave_count');

            $pending_leaves = DB::table('tbl_leave_management as lm')
                ->leftJoin('tbl_leaves as l', function ($join) use ($ses_userId) {
                    $join->on('lm.id', '=', 'l.leave_type_id')
                        ->where('l.employee_id', $ses_userId)
                        ->where('l.is_approved', 2)
                        ->where('l.is_active', 1)
                        ->where('l.is_deleted', 0);
                })
                ->where('lm.is_active', 1)
                ->where('lm.is_deleted', 0)
                // ->where('lm.leave_year', date('Y'))
                ->select(DB::raw('SUM(lm.leave_count - COALESCE(l.leave_count, 0)) as remaining_count'))
                ->first()
                ->remaining_count ?? 0;

            // Previous unused = example logic (last year’s remaining)
            $previous_unused_leaves = DB::table('tbl_leave_management')
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->where('leave_year', date('Y') - 1)
                ->sum('leave_count'); // adjust logic if you track remaining from last year

            $design_received = BusinessApplicationProcesses::leftJoin('estimation', function ($join) {
                $join->on('business_application_processes.business_details_id', '=', 'estimation.business_details_id');
            })
                ->leftJoin('businesses_details', function ($join) {
                    $join->on('business_application_processes.business_details_id', '=', 'businesses_details.id');
                })
                ->whereNull('business_application_processes.bom_estimation_send_to_owner')
                ->where('business_application_processes.design_send_to_estimation', 1113)
                ->where('business_application_processes.design_status_id', 1113)
                ->count();

            $estimation_accepted_bom = BusinessApplicationProcesses::leftJoin('businesses', function ($join) {
                $join->on('business_application_processes.business_id', '=', 'businesses.id');
            })
                ->where('business_application_processes.owner_bom_accepted', 1150)
                ->whereNull('business_application_processes.estimation_send_to_production')
                ->count();

            $estimation_rejected_bom = BusinessApplicationProcesses::leftJoin('businesses', function ($join) {
                $join->on('business_application_processes.business_id', '=', 'businesses.id');
            })
                ->where('business_application_processes.owner_bom_rejected', 1151)
                ->whereNull('business_application_processes.owner_bom_accepted')
                ->count();

            $estimation_send_tp_production = BusinessApplicationProcesses::leftJoin('businesses', function ($join) {
                $join->on('business_application_processes.business_id', '=', 'businesses.id');
            })
                ->where('business_application_processes.estimation_send_to_production', 1152)
                ->count();
            $owner_counts = [
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


            ];
            $business_total_amount = [
                'total_revenu_count' => $total_revenu_count,
                'total_utilize_materila_amount' => $total_utilize_materila_amount,
                'profit' =>  $profit
            ];
            $offcanvas = [
                'offcanvas' => $offcanvas,
            ];
            $department_count = [
                'department_total_count' => $department_count,
            ];

            $cms_counts = [
                'product_count' => $product_count,
                'testimonial_count' => $testimonial_count,
                'product_services_count' => $product_services_count,
                'vision_mission_count' => $vision_mission_count,
                'director_desk_count' => $director_desk_count,
                'team_count' => $team_count,
                'contact_us_count' => $contact_us_count,

            ];
            $design_dept_counts = [
                'business_received_for_designs' => $business_received_for_designs,
                // 'business_design_send_to_product' => $business_design_send_to_product,
                'design_sent_for_production' => $design_sent_for_production,
                'accepted_design_production_dept' => $accepted_design_production_dept,
                'rejected_design_production_dept' => $rejected_design_production_dept,
            ];

            $production_dept_counts = [
                'design_recived_for_production' => $design_recived_for_production,
                'accepted_and_sent_to_store' => $accepted_and_sent_to_store,
                'rejected_design_list_sent' => $rejected_design_list_sent,
                'corected_design_list_recived' => $corected_design_list_recived,
                'material_received_for_production' => $material_received_for_production,
                'production_completed_prod_dept' => $production_completed_prod_dept,
            ];
            $store_dept_counts = [
                'material_need_to_sent_to_production' => $material_need_to_sent_to_production,
                // 'material_sent_to_production' => $material_sent_to_production,
                'material_for_purchase' => $material_for_purchase,
                'material_received_from_quality' => $material_received_from_quality,
                'rejected_chalan' => $rejected_chalan,
                'delivery_chalan' => $delivery_chalan,
                'returnable_chalan' => $returnable_chalan,
            ];
            $purchase_dept_counts = [
                'BOM_recived_for_purchase' => $BOM_recived_for_purchase,
                'vendor_list' => $vendor_list,
                'tax' => $tax,
                'part_item' => $part_item,
                'purchase_order_approved' => $purchase_order_approved,
                'purchase_order_submited_by_vendor' => $purchase_order_submited_by_vendor,
            ];
            $secuirty_dept_counts = [
                'get_pass' => $get_pass,
            ];
            $quality_dept_counts = [
                'GRN_genration' => $GRN_genration,
                'material_need_to_sent_to_store' => $material_need_to_sent_to_store,
                'rejected_chalan_po_wise' => $rejected_chalan_po_wise,
            ];
            $logistics_counts = [
                'need_to_check_for_payment' => $need_to_check_for_payment,
                'production_completed_prod_dept_logisitics' => $production_completed_prod_dept_logisitics,
                'po_pyament_need_to_release' => $po_pyament_need_to_release,
                'logistics_list_count' => $logistics_list_count,
                'logistics_send_by_finance_count' => $logistics_send_by_finance_count,
                'vehicle_type_count' => $vehicle_type_count,
                'transport_name_count' => $transport_name_count,
            ];
            $fianance_counts = [
                'logistics_send_by_finance_received_fianance_count' => $logistics_send_by_finance_received_fianance_count,
                'fianance_send_to_dispatch_count' => $fianance_send_to_dispatch_count,
            ];

            $dispatch_counts = [
                'dispatch_received_from_finance' => $dispatch_received_from_finance,
                'dispatch_completed' => $dispatch_completed,

            ];
            $inventory_dept_counts = [

                'material_need_to_sent_to_production_inventory' => $material_need_to_sent_to_production_inventory,
                'part_item_inventory' => $part_item_inventory
            ];
            $hr_counts = [
                'leave_request' => $leave_request,
                'accepted_leave_request' => $accepted_leave_request,
                'rejected__leave_request' => $rejected__leave_request,
                'total_employee' => $total_employee,
                'total_leaves_type' => $total_leaves_type,
                'total_notice' => $total_notice


            ];
            $employee_counts = [
                'employee_leave_request' => $employee_leave_request,
                'employee_accepted_leave_request' => $employee_accepted_leave_request,
                'employee_rejected_leave_request' => $employee_rejected_leave_request,
                'user_leaves_status' => $user_leaves_status,
                'total_leaves' => $total_leaves,
                'available_leaves' => $available_leaves,
                'previous_unused_leaves' => $previous_unused_leaves,
                'pending_leaves' => $pending_leaves,
            ];
            $estimation_counts = [
                'design_received' => $design_received,
                'estimation_accepted_bom' => $estimation_accepted_bom,
                'estimation_rejected_bom' => $estimation_rejected_bom,
                'estimation_send_tp_production' => $estimation_send_tp_production
            ];

            return view('admin.pages.dashboard.dashboard', [
                'return_data' => $owner_counts,
                'offcanvas' => $offcanvas,
                'department_count' => $department_count,
                'logistics_counts' => $logistics_counts,
                'design_dept_counts' => $design_dept_counts,
                'production_dept_counts' => $production_dept_counts,
                'store_dept_counts' => $store_dept_counts,
                'cms_counts' => $cms_counts,
                'purchase_dept_counts' => $purchase_dept_counts,
                'secuirty_dept_counts' => $secuirty_dept_counts,
                'quality_dept_counts' => $quality_dept_counts,
                'fianance_counts' => $fianance_counts,
                'inventory_dept_counts' => $inventory_dept_counts,
                'dispatch_counts' => $dispatch_counts,
                'hr_counts' => $hr_counts,
                'employee_counts' => $employee_counts,
                'employee_leave_type' => $employee_leave_type,
                'estimation_counts' => $estimation_counts,
                'business_total_amount' => $business_total_amount
            ]);
        } catch (\Exception $e) {
            Log::error("Error fetching business data: " . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while fetching data.');
        }
    }


    public function getNotification(Request $request)
    {

        $ses_userId = session()->get('user_id');

        $ses_roleId = session()->get('role_id');
        //    $baseUrl = url('http://localhost/shreerag_final_updated'); // Get the base URL dynamically
        $baseUrl = url('https://report.shreeragengineering.com'); // Get the base URL dynamically
        // $baseUrl = url('https://shreeragengineering.com'); // Get the base URL dynamically
        $count = 0;  // Initialize the $count variable
        $notifications = [];  // Initialize the $notifications array

        if ($ses_userId == '2') { //Owner Department
            $business_data = AdminView::where('off_canvas_status', 11)
                ->where('is_view', '0')
                ->select('id')
                ->get();
            $business_count = $business_data->count();
            $notifications[] = [
                'admin_count' => $business_count,
                'message' => 'Business Sent For Design',
                'url' => $baseUrl . '/owner/list-forwarded-to-design',
            ];

            $estimation_received_design = AdminView::where('off_canvas_status', 12)
                ->where('is_view', '0')
                ->select('id')
                ->get();
            $received_design_for_estimation_count = $estimation_received_design->count();

            $notifications[] = [
                'admin_count' => $received_design_for_estimation_count,
                'message' => 'Design Dept Send Design to Estimation Dept',
                'url' => $baseUrl . '/estimationdept/list-new-requirements-received-for-estimation'
            ];
            $business_data = AdminView::where('off_canvas_status', 28)
                ->where('is_view', '0')
                ->select('id')
                ->get();

            $business_count = $business_data->count();
            $notifications[] = [
                'admin_count' => $business_count,
                'message' => 'Estimation Received For Accept/Reject',
                'url' => $baseUrl . '/owner/list-design-received-estimation',
            ];
            $revised_rejected_list = AdminView::where('off_canvas_status', 31)
                ->where('is_view', '0')
                ->select('id')
                ->get();

            $revised_rejected_list_count = $revised_rejected_list->count();
            $notifications[] = [
                'admin_count' => $revised_rejected_list_count,
                'message' => 'Revised BOM send Estimation Dept',
                'url' => $baseUrl . '/owner/list-revised-bom-estimation',
            ];
            $uploaded_design = AdminView::where('off_canvas_status', '33')
                ->where('is_view', '0')
                ->select('id')
                ->get();
            $uploaded_design_count = $uploaded_design->count();
            $notifications[] = [
                'admin_count' => $uploaded_design_count,
                'message' => 'Design Upload and Received Production Department',
                'url' => $baseUrl . '/owner/list-design-uploaded-owner',
            ];
            $received_correction_design = AdminView::where('off_canvas_status', '13')
                ->where('is_view', '0')
                ->select('id')
                ->get();
            $received_correction_design_count = $received_correction_design->count();
            $notifications[] = [
                'admin_count' => $received_correction_design_count,
                'message' => 'Production Dept Design Rejected and Received Design Dept',
                'url' => $baseUrl . '/owner/list-design-correction',
            ];
            $material_ask_prod_to_store = AdminView::where('off_canvas_status', '15')
                ->where('is_view', '0')
                ->select('id')
                ->get();
            $material_ask_prod_to_store_count = $material_ask_prod_to_store->count();
            $notifications[] = [
                'admin_count' => $material_ask_prod_to_store_count,
                'message' => 'Material Ask By Production To Store',
                'url' => $baseUrl . '/owner/material-ask-by-prod-to-store',
            ];
            $material_ask_by_store_to_purchase = AdminView::where('off_canvas_status', '16')
                ->where('is_view', '0')
                ->select('id')
                ->get();
            $material_ask_by_store_to_purchase_count = $material_ask_by_store_to_purchase->count();
            $notifications[] = [
                'admin_count' => $material_ask_by_store_to_purchase_count,
                'message' => 'Material ask by Store to Purchase',
                'url' => $baseUrl . '/owner/material-ask-by-store-to-purchase',
            ];
            $Purchase_order_need_to_check = AdminView::where('off_canvas_status', 23)
                ->where('is_view', '0')
                ->select('id')
                ->get();
            $Purchase_order_need_to_check_count = $Purchase_order_need_to_check->count();
            $notifications[] = [
                'admin_count' => $Purchase_order_need_to_check_count,
                'message' => 'Purchase order need to check',
                'url' => $baseUrl . '/owner/list-purchase-orders',
            ];
            $purchase_order_approved = AdminView::where('off_canvas_status', '24')
                ->where('is_view', '0')
                ->select('id')
                ->get();
            $purchase_order_approved_count = $purchase_order_approved->count();
            $notifications[] = [
                'admin_count' => $purchase_order_approved_count,
                'message' => 'Purchase Order Approved',
                'url' => $baseUrl . '/owner/list-approved-purchase-orders-owner',
            ];
            $po_send_to_vendor = AdminView::where('off_canvas_status', 25)
                ->where('is_view', '0')
                ->select('id')
                ->get();
            $po_send_to_vendor_count = $po_send_to_vendor->count();
            $notifications[] = [
                'admin_count' => $po_send_to_vendor_count,
                'message' => 'Submitted PO by Vendor',
                'url' => $baseUrl . '/owner/list-owner-submited-po-to-vendor',
            ];
            $gate_pass_generate = AdminView::where('off_canvas_status', 26)
                ->where('is_view', '0')
                ->select('id')
                ->get();
            $gate_pass_generate_count = $gate_pass_generate->count();
            $notifications[] = [
                'admin_count' => $gate_pass_generate_count,
                'message' => 'Security Created Gate Pass',
                'url' => $baseUrl . '/owner/list-owner-gatepass',
            ];

            $quality_dept_material_received_in_store = AdminView::where('off_canvas_status', '27')
                ->where('is_view', '0')
                ->select('id')
                ->get();
            $quality_dept_material_received_in_store_count = $quality_dept_material_received_in_store->count();
            $notifications[] = [
                'admin_count' => $quality_dept_material_received_in_store_count,
                'message' => 'Generated GRN Material send Quality Dept to Store ',
                'url' => $baseUrl . '/owner/list-material-sent-to-store-generated-grn',
            ];

            $production_completed = AdminView::where('off_canvas_status', '18')
                ->where('is_view', '0')
                ->select('id')
                ->get();
            $production_completed_count = $production_completed->count();
            $notifications[] = [
                'admin_count' => $production_completed_count,
                'message' => 'Logistics Dept Received Product completed list',
                'url' => $baseUrl . '/owner/list-owner-final-production-completed-recive-to-logistics',
            ];

            $logistics_send_to_fianance = AdminView::where('off_canvas_status', '20')
                ->where('is_view', '0')
                ->select('id')
                ->get();
            $logistics_send_to_fianance_count = $logistics_send_to_fianance->count();
            $notifications[] = [
                'admin_count' => $logistics_send_to_fianance_count,
                'message' => 'Fianance Dept Product Received from Logistics Dept',
                'url' => $baseUrl . '/owner/recive-owner-logistics-list',
            ];

            // $send_fianance_to_dispatch = AdminView::where('off_canvas_status', '19')
            // ->where('is_view', '0')
            // ->select('id')
            //     ->get();
            // $send_fianance_to_dispatch_count = $send_fianance_to_dispatch->count();
            // $notifications[] = ['admin_count' => $send_fianance_to_dispatch_count,
            // 'message' => 'Fianance Dept Product send to Dispatch dept',
            // 'url' => 'recive-owner-logistics-list',
            // ];

            $received_fianance_to_dispatch = AdminView::where('off_canvas_status', '21')
                ->where('is_view', '0')
                ->select('id')
                ->get();
            $received_fianance_to_dispatch_count = $received_fianance_to_dispatch->count();
            $notifications[] = [
                'admin_count' => $received_fianance_to_dispatch_count,
                'message' => 'Fianance Dept Production Request Send to Dispatch Dept',
                'url' => $baseUrl . '/owner/list-owner-send-to-dispatch',
            ];

            $dispatch_completed = AdminView::where('off_canvas_status', '22')
                ->where('is_view', '0')
                ->select('id')
                ->get();
            $dispatch_completed_count = $dispatch_completed->count();

            $notifications[] = [
                'admin_count' => $dispatch_completed_count,
                'message' => 'Dispatch Dept Production Dispatch Completed',
                'url' => $baseUrl . '/owner/list-product-dispatch-completed',
            ];
            $count = $business_count + $revised_rejected_list_count + $received_design_for_estimation_count + $uploaded_design_count  + $material_ask_prod_to_store_count + $received_correction_design_count + $material_ask_by_store_to_purchase_count + $Purchase_order_need_to_check_count + $purchase_order_approved_count
                + $po_send_to_vendor_count + $gate_pass_generate_count + $quality_dept_material_received_in_store_count + $production_completed_count + $logistics_send_to_fianance_count
                + $received_fianance_to_dispatch_count + $dispatch_completed_count;
        } elseif ($ses_userId == '3') { //Design Department
            $sent_to_prod_data = NotificationStatus::where('off_canvas_status', 11)
                ->where('design_is_view', '0')
                ->select('id')
                ->get();
            $received_for_design = $sent_to_prod_data->count();

            $notifications[] = [
                'admin_count' => $received_for_design,
                'message' => 'Business Received For Design',
                'url' => $baseUrl . '/designdept/list-new-requirements-received-for-design'
            ];

            //    $design_rejected_prod_dept= NotificationStatus::where('off_canvas_status',15)
            //    ->where('prod_design_accepted','0')
            //    ->select('id')
            //    ->get();
            //    $design_rejected_prod_dept_count = $design_rejected_prod_dept->count();

            //    $notifications[] = ['admin_count' => $design_rejected_prod_dept_count,
            //        'message' => 'Product Department Design Accepted',
            //       'url' => $baseUrl . '/designdept/list-accept-design-by-production'
            //    ];


            $design_accepted_prod_dept = NotificationStatus::where('off_canvas_status', 15)
                ->where('designer_is_view_accepted_design', '0')
                ->select('id')
                ->get();
            $design_accepted_prod_count = $design_accepted_prod_dept->count();

            $notifications[] = [
                'admin_count' => $design_accepted_prod_count,
                'message' => 'Product Department Design Accepted',
                'url' => $baseUrl . '/designdept/list-accept-design-by-production'
            ];
            $design_rejected_prod_dept = NotificationStatus::where('off_canvas_status', 13)
                ->where('prod_design_rejected', '0')
                ->select('id')
                ->get();
            $design_rejected_prod_count = $design_rejected_prod_dept->count();

            $notifications[] = [
                'admin_count' => $design_rejected_prod_count,
                'message' => 'Product Department Design Rejected',
                'url' => $baseUrl . '/designdept/list-reject-design-from-prod'
            ];

            $count = $received_for_design + $design_accepted_prod_count + $design_rejected_prod_count;
        } elseif ($ses_userId == '4') { //Production Department
            $received_prod_req = NotificationStatus::where('off_canvas_status', 33)
                ->where('prod_is_view', '0')
                ->select('id')
                ->get();
            $received_design_in_prod = $received_prod_req->count();

            $notifications[] = [
                'admin_count' => $received_design_in_prod,
                'message' => 'New Design Estimated BOM Received ',
                'url' => $baseUrl . '/proddept/list-new-requirements-received-for-production'
            ];
            $revised_received_design = NotificationStatus::where('off_canvas_status', 14)
                ->where('prod_is_view_revised', '0')
                ->select('id')
                ->get();
            $revised_received_design_count = $revised_received_design->count();

            $notifications[] = [
                'admin_count' => $revised_received_design_count,
                'message' => 'Revised Design List',
                'url' => $baseUrl . '/proddept/list-revislist-material-reciveded-design'
            ];

            $material_received_for_production_by_store = NotificationStatus::where('off_canvas_status', 17)
                ->where('material_received_from_store', '0')
                ->select('id')
                ->get();
            $material_received_for_production_by_store_count = $material_received_for_production_by_store->count();

            $notifications[] = [
                'admin_count' => $material_received_for_production_by_store_count,
                'message' => 'Tracking Material',
                'url' => $baseUrl . '/proddept/list-material-received'
            ];
            $count = $received_design_in_prod + $revised_received_design_count + $material_received_for_production_by_store_count;
        } elseif ($ses_userId == '5') { //Store Department

            $store_view_req = NotificationStatus::where('off_canvas_status', 15)
                ->where('store_is_view', '0')
                ->select('id')
                ->get();
            $store_view_req_count = $store_view_req->count();
            $notifications[] = [
                'admin_count' => $store_view_req_count,
                'message' => 'New Design Received ',
                'url' => $baseUrl . '/storedept/list-accepted-design-from-prod'
            ];

            $material_received_by_quality = NotificationStatus::where('off_canvas_status', 27)
                ->where('quality_create_grn', '0')
                ->select('id')
                ->get();
            $material_received_by_quality_count = $material_received_by_quality->count();

            $notifications[] = [
                'admin_count' => $material_received_by_quality_count,
                'message' => 'Material Received From Quality',
                'url' => $baseUrl . '/storedept/list-material-received-from-quality'
            ];

            $material_received_from_store = NotificationStatus::where('off_canvas_status', 17)
                ->where('issue_material_send_req_to_store', '0')
                ->select('id')
                ->get();
            $material_received_from_store_count = $material_received_from_store->count();

            $notifications[] = [
                'admin_count' => $material_received_from_store_count,
                'message' => 'Issue Material List Received',
                'url' => $baseUrl . '/storedept/list-accepted-design-from-prod'
            ];


            // $store_view_req = NotificationStatus::where('off_canvas_status','15')
            // ->where('store_is_view','0')
            // ->select('id')
            // ->get();
            // $store_view_req_form_count = $store_view_req->count();
            // $notifications[] = ['admin_count' => $store_view_req_form_count,
            //     'message' => 'New Design Received ',
            //     'url' => 'list-accepted-design-from-prod'
            // ];

            $count = $store_view_req_count + $material_received_by_quality_count + $material_received_from_store_count;
        } elseif ($ses_userId == '6') { //Purchase Department

            $received_requistion_req = NotificationStatus::where('off_canvas_status', '16')
                ->where('purchase_is_view', '0')
                ->select('id')
                ->get();
            $received_requistion_req_count = $received_requistion_req->count();
            $notifications[] = [
                'admin_count' => $received_requistion_req_count,
                'message' => 'Received Requistion Request',
                'url' => $baseUrl . '/purchase/list-purchase'
            ];
            $list_purchase_orders_sent_to_owner = NotificationStatus::where('off_canvas_status', 23)
                ->where('purchase_order_is_view_po', '0')
                ->select('id')
                ->get();
            $list_purchase_orders_sent_to_owner_count = $list_purchase_orders_sent_to_owner->count();

            $notifications[] = [
                'admin_count' => $list_purchase_orders_sent_to_owner_count,
                'message' => 'Purchase Orders Sent to Owner',
                'url' => $baseUrl . '/purchase/list-purchase-orders-sent-to-owner'
            ];

            $list_purchase_orders_rejected_by_owner = NotificationStatus::where('off_canvas_status', 23)
                ->where('purchase_order_is_rejected_view', '0')
                ->select('id')
                ->get();
            $list_purchase_orders_rejected_by_owner_count = $list_purchase_orders_rejected_by_owner->count();

            $notifications[] = [
                'admin_count' => $list_purchase_orders_rejected_by_owner_count,
                'message' => 'Purchase Order Rejected by Owner',
                'url' => $baseUrl . '/purchase/list-purchase-orders-sent-to-owner'
            ];

            $list_approved_purchase_orders_owner = NotificationStatus::where('off_canvas_status', 24)
                ->where('purchase_order_is_accepted_by_view', '0')
                ->select('id')
                ->get();
            $list_approved_purchase_orders_owner_count = $list_approved_purchase_orders_owner->count();

            $notifications[] = [
                'admin_count' => $list_approved_purchase_orders_owner_count,
                'message' => 'Purchase Orders Approved Owner Side',
                'url' => $baseUrl . '/purchase/list-approved-purchase-orders'
            ];
            $po_send_to_vendor = NotificationStatus::where('off_canvas_status', 25)
                ->where('po_send_to_vendor', '0')
                ->select('id')
                ->get();
            $po_send_to_vendor_count = $po_send_to_vendor->count();

            $notifications[] = [
                'admin_count' => $po_send_to_vendor_count,
                'message' => 'Submited PO by Vendor List',
                'url' => $baseUrl . '/purchase/list-submited-po-to-vendor'
            ];


            $visible_grn_material_received_store_count = NotificationStatus::where('off_canvas_status', 27)
                ->where('visible_purchase_quality_to_store', '0')
                ->select('id')
                ->get();
            $visible_grn_material_received_store_count = $visible_grn_material_received_store_count->count();

            $notifications[] = [
                'admin_count' => $visible_grn_material_received_store_count,
                'message' => 'Material Received Quality to Store Department',
                'url' => $baseUrl . '/storedept/list-material-received-from-quality-po-tracking'
            ];
            $count = $received_requistion_req_count + $list_purchase_orders_sent_to_owner_count + $list_approved_purchase_orders_owner_count + $list_purchase_orders_rejected_by_owner_count + $po_send_to_vendor_count +  $visible_grn_material_received_store_count;
        } elseif ($ses_userId == '7') { //Security Department
            $po_send_to_vendor_visible_security = NotificationStatus::where('off_canvas_status', 25)
                ->where('po_send_to_vendor_visible_security', '0')
                ->select('id')
                ->get();
            $po_send_to_vendor_visible_security_count = $po_send_to_vendor_visible_security->count();

            $notifications[] = [
                'admin_count' => $po_send_to_vendor_visible_security_count,
                'message' => 'Search By PO No',
                'url' => $baseUrl . '/securitydept/search-by-po-no'

            ];
            $count = $po_send_to_vendor_visible_security_count;
        } elseif ($ses_userId == '8') { //Quality Department

            $po_material_received_by_quality = NotificationStatus::where('off_canvas_status', 26)
                ->where('security_create_date_pass', '0')
                ->select('id')
                ->get();
            $po_material_received_by_quality_count = $po_material_received_by_quality->count();

            $notifications[] = [
                'admin_count' => $po_material_received_by_quality_count,
                'message' => 'Received Purchase Order with Material',
                'url' => $baseUrl . '/quality/list-grn'
            ];

            $visible_grn = NotificationStatus::where('off_canvas_status', 27)
                ->where('quality_create_grn', '0')
                ->select('id')
                ->get();
            $visible_grn_count = $visible_grn->count();

            $notifications[] = [
                'admin_count' => $visible_grn_count,
                'message' => 'Material Sent to Store',
                'url' => $baseUrl . '/quality/list-material-sent-to-quality'
            ];
            $count = $po_material_received_by_quality_count + $visible_grn_count;
        } elseif ($ses_userId == '9') { //finance
            $recived_logistics_to_fianance = NotificationStatus::where('off_canvas_status', 20)
                ->where('logistics_to_fianance_visible', '0')
                ->select('id')
                ->get();
            $recived_logistics_to_fianance_count = $recived_logistics_to_fianance->count();

            $notifications[] = [
                'admin_count' => $recived_logistics_to_fianance_count,
                'message' => 'Received Logistics List',
                'url' => $baseUrl . '/financedept/recive-logistics-list'
            ];
            // $recived_store_to_fianance_sr_grn= NotificationStatus::where('off_canvas_status',30)
            // ->where('prod_store_sr_gr_send_fianance','0')
            // ->select('id')
            // ->get();
            // $recived_store_to_fianance_sr_grn_count = $recived_store_to_fianance_sr_grn->count();

            // $notifications[] = ['admin_count' => $recived_store_to_fianance_sr_grn_count,
            //     'message' => 'Received Logistics List',
            //     'url' => $baseUrl . '/financedept/recive-logistics-list'
            // ];
            $count = $recived_logistics_to_fianance_count;
        } elseif ($ses_userId == '10') {
            $leave_notification = Leaves::where('notification_read_status', '0')
                ->where('is_approved', '0')
                ->select('id')
                ->get();
            $leave_notification_count = $leave_notification->count();

            $notifications[] = [
                'admin_count' => $leave_notification_count,
                'message' => 'Leave Request for Approval',
                'url' => $baseUrl . '/hr/list-leaves-acceptedby-hr'
            ];
            $count = $leave_notification_count;
        } elseif ($ses_userId == '11') { //logistics
            $production_completed_by_product_dept = NotificationStatus::where('off_canvas_status', 18)
                ->where('production_completed', '0')
                ->select('id')
                ->get();
            $production_completed_by_product_dept_count = $production_completed_by_product_dept->count();

            $notifications[] = [
                'admin_count' => $production_completed_by_product_dept_count,
                'message' => 'Product Completed by Production Dept',
                'url' => $baseUrl . '/logisticsdept/list-final-production-completed-recive-to-logistics'
            ];
            $count = $production_completed_by_product_dept_count;
        } elseif ($ses_userId == '12') { //dispatch

            $received_fianance_to_dispatch = NotificationStatus::where('off_canvas_status', 21)
                ->where('fianance_to_dispatch_visible', '0')
                ->select('id')
                ->get();
            $received_fianance_to_dispatch_count = $received_fianance_to_dispatch->count();

            $notifications[] = [
                'admin_count' => $received_fianance_to_dispatch_count,
                'message' => 'Received From Finance',
                'url' => $baseUrl . '/dispatchdept/list-final-production-completed-received-from-fianance'
            ];
            $count = $received_fianance_to_dispatch_count;
        } elseif ($ses_userId == '15') {

            $received_design = NotificationStatus::where('off_canvas_status', 12)
                ->where('estimation_view', '0')
                ->select('id')
                ->get();

            $received_design_for_estimation = $received_design->count();

            $notifications[] = [
                'admin_count' => $received_design_for_estimation,
                'message' => 'New Design Received for Estimation',
                'url' => $baseUrl . '/estimationdept/list-new-requirements-received-for-estimation'
            ];

            $estimation_accept_data = NotificationStatus::where('off_canvas_status', 32)
                ->where('accepted_bom_estimated', '0')
                ->select('id')
                ->get();

            $estimation_accept_data_count = $estimation_accept_data->count();
            $notifications[] = [
                'admin_count' => $estimation_accept_data_count,
                'message' => 'Owner Side Estimation Accepted',
                'url' => $baseUrl . '/owner/list-accept-bom-estimation',
            ];

            $estimation_rejected_data = NotificationStatus::where('off_canvas_status', 30)
                ->where('rejected_bom_estimated', '0')
                ->select('id')
                ->get();

            $estimation_rejected_data_count = $estimation_rejected_data->count();
            $notifications[] = [
                'admin_count' => $estimation_rejected_data_count,
                'message' => 'Received Rejected Estimation',
                'url' => $baseUrl . '/owner/list-rejected-bom-estimation',
            ];


            $count = $received_design_for_estimation + $estimation_accept_data_count + $estimation_rejected_data_count;
        }
        return response()->json([
            'notification_count' => $count,
            'notifications' => $notifications
        ]);
    }
}
