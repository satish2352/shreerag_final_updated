<?php

namespace App\Http\Controllers\Admin\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Admin\Dashboard\DashboardService;

use App\Models\{
    NotificationStatus,
    Leaves,
    AdminView
};

class DashboardController extends Controller
{
    protected $service;

    public function __construct(DashboardService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $data = $this->service->getDashboardData();

        return view('admin.pages.dashboard.dashboard', $data);
    }

    // 👇 INSIDE CLASS
    public function getNotification(Request $request)
    {
        $ses_userId = session()->get('user_id');
        $ses_roleId = session()->get('role_id');

        $ses_userId = session()->get('user_id');


        $baseUrl = config('app.url');
        $count = 0;
        $notifications = [];

        /*
    |--------------------------------------------------------------------------
    | OWNER / HIGHER AUTHORITY
    |--------------------------------------------------------------------------
    */
        if ($ses_roleId == config('constants.ROLE_ID.HIGHER_AUTHORITY')) {

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
        }

        /*
    |--------------------------------------------------------------------------
    | DESIGN DEPARTMENT
    |--------------------------------------------------------------------------
    */ elseif ($ses_roleId == config('constants.ROLE_ID.DESIGNER')) {

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
        }

        /*
    |--------------------------------------------------------------------------
    | PRODUCTION DEPARTMENT
    |--------------------------------------------------------------------------
    */ elseif ($ses_roleId == config('constants.ROLE_ID.PRODUCTION')) {

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
        }

        /*
    |--------------------------------------------------------------------------
    | STORE DEPARTMENT
    |--------------------------------------------------------------------------
    */ elseif ($ses_roleId == config('constants.ROLE_ID.STORE')) {

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
        }

        /*
    |--------------------------------------------------------------------------
    | PURCHASE DEPARTMENT
    |--------------------------------------------------------------------------
    */ elseif ($ses_roleId == config('constants.ROLE_ID.PURCHASE')) {

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
        }

        /*
    |--------------------------------------------------------------------------
    | HR DEPARTMENT
    |--------------------------------------------------------------------------
    */ elseif ($ses_roleId == config('constants.ROLE_ID.SECURITY')) {
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
        } elseif ($ses_roleId == config('constants.ROLE_ID.QUALITY')) { //Quality Department

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
        } elseif ($ses_roleId == config('constants.ROLE_ID.FINANCE')) { //finance
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
        }
        /*
    |--------------------------------------------------------------------------
    | HR DEPARTMENT
    |--------------------------------------------------------------------------
    */ elseif ($ses_roleId == config('constants.ROLE_ID.HR')) {

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
        } elseif ($ses_roleId == config('constants.ROLE_ID.LOGISTICS')) {
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
        } elseif ($ses_roleId == config('constants.ROLE_ID.DISPATCH')) {
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
        } elseif ($ses_roleId == config('constants.ROLE_ID.ESTIMATION')) {
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

        /*
    |--------------------------------------------------------------------------
    | DEFAULT
    |--------------------------------------------------------------------------
    */ else {
            $count = 0;
            $notifications = [];
        }

        return response()->json([
            'notification_count' => $count,
            'notifications' => $notifications
        ]);
    }
}
