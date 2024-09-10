<?php

namespace App\Http\Controllers\Admin\Dashboard;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// use App\Http\Services\DashboardServices;
use App\Models\ {
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
//     Gallery,
//     AdditionalSolutions,
//     OurSolutions,
//     ResourcesAndInsights,
//     WebsiteContactDetails,
//     AboutUsContact,
//     ContactUs,
//     Subcribers
    BusinessDetails,

};
use Validator;

class DashboardController extends Controller {
    /**
     * Topic constructor.
     */
    public function __construct()
    {
        // $this->service = new DashboardServices();
    }
    public function index(){
    try {
      
        // Get the counts
        $user_active_count = User::where('is_active', 1)->count(); 
        $active_count = Business::where('is_active', 1)->count(); 
        $business_details_count = BusinessDetails::where('is_active', 1)->count(); 
        $product_completed_count = BusinessApplicationProcesses::where('is_active', 1)
            ->where('dispatch_status_id', 1148)
            ->count();
        // $product_completed_count = BusinessApplicationProcesses::where('is_active', 1)
        //     ->where('dispatch_status_id', 1148)
        //     ->count();
        $business_completed_count = BusinessApplicationProcesses::where('business_application_processes.is_active', 1)
        ->join('businesses_details', 'business_application_processes.business_details_id', '=', 'businesses_details.id')
        ->where('business_application_processes.dispatch_status_id', 1140)
        ->count();
        
      

        $business_inprocess_count = BusinessApplicationProcesses::where('is_active', 1)
        ->where(function($query) {
            $query->orWhere('business_status_id', 1118)
                ->orWhere('design_status_id', 1114)
                ->orWhere('production_status_id', 1121)
                ->orWhere('store_status_id', 1123)
                // ->orWhere('purchase_status_from_owner', 1129)
                ->orWhere('purchase_status_from_purchase', 1129)
                // ->orWhere('finanace_store_receipt_status_id', 1136)
                // ->orWhere('security_status_id', 1132)
                ->orWhere('quality_status_id', 1134)
                ->orWhere('logistics_status_id', 1145);
        })
        ->count();
    
        $product_inprocess_count = BusinessApplicationProcesses::where('is_active', 1)
        ->where(function($query) {
            $query->orWhere('business_status_id', 1118)
                ->orWhere('design_status_id', 1114)
                ->orWhere('production_status_id', 1121)
                ->orWhere('store_status_id', 1123)
                // ->orWhere('purchase_status_from_owner', 1129)
                ->orWhere('purchase_status_from_purchase', 1129)
                // ->orWhere('finanace_store_receipt_status_id', 1136)
                // ->orWhere('security_status_id', 1132)
                ->orWhere('quality_status_id', 1134)
                ->orWhere('logistics_status_id', 1145);
        })
            ->count();

        $data_output_offcanvas = BusinessApplicationProcesses::leftJoin('businesses', function ($join) {
                $join->on('business_application_processes.business_id', '=', 'businesses.id');
            })
            ->leftJoin('businesses_details', function ($join) {
                $join->on('business_application_processes.business_details_id', '=', 'businesses_details.id');
            })
            ->leftJoin('purchase_orders', function ($join) {
                $join->on('business_application_processes.business_details_id', '=', 'purchase_orders.business_details_id');
            })
            ->where('businesses.is_active', 1)
            ->select('businesses.customer_po_number','businesses.title','businesses_details.product_name',
            'business_application_processes.business_status_id','businesses.updated_at', 'business_application_processes.design_status_id',
             'business_application_processes.production_status_id', 'business_application_processes.store_status_id','purchase_orders.purchase_status_from_purchase',
             'purchase_orders.finanace_store_receipt_status_id', 'purchase_orders.purchase_status_from_owner',
             'purchase_orders.security_status_id', 'purchase_orders.quality_status_id', 'purchase_orders.finanace_store_receipt_status_id',
             'business_application_processes.logistics_status_id', 'business_application_processes.dispatch_status_id') // Adjust if you need more fields
            ->orderBy('businesses.updated_at', 'desc')
           
            ->get()
            ->groupBy('customer_po_number'); 
            // ->groupBy('businesses.customer_po_number'); 
        
            // dd($data_output_offcanvas);
            // die();
        // Prepare the data for the chart
// dd($data_output_offcanvas);
// die();
        $product_count = Products::where('is_active', 1)->count();
        $testimonial_count = Testimonial::where('is_active', 1)->count();
        $product_services_count = ProductServices::where('is_active', 1)->count();
        $team_count = Team::where('is_active',1)->count();
        $contact_us_count = ContactUs::where('is_active',1)->count();
        $vision_mission_count = VisionMission::where('is_active',1)->count();
        $director_desk_count = DirectorDesk::where('is_active',1)->count();
        $logistics_list_count = BusinessApplicationProcesses::where('logistics_status_id', 1146)
        ->where('is_active',1)->count();
        $logistics_send_by_finance_count = BusinessApplicationProcesses::leftJoin('tbl_logistics', function($join) {
            $join->on('business_application_processes.business_details_id', '=', 'tbl_logistics.business_details_id');
        })->where('business_application_processes.logistics_status_id', 1146)
          ->count();
          $vehicle_type_count = VehicleType::where('is_active',1)->count();
        
          $business_received_for_designs = DesignModel::leftJoin('businesses', function($join) {
            $join->on('designs.business_id', '=', 'businesses.id');
        })
        ->leftJoin('businesses_details', function($join) {
            $join->on('designs.business_details_id', '=', 'businesses_details.id');
        })
        ->leftJoin('business_application_processes', function($join) {
            $join->on('designs.business_details_id', '=', 'business_application_processes.business_details_id');
        })
        ->where('business_application_processes.production_status_id', 0) 
        ->where('business_application_processes.production_id', 0)
        ->count();
        $business_received_for_designs = BusinessApplicationProcesses::where('business_status_id',1112)->where('design_status_id', 1111)
        ->where('production_status_id', 0)
        ->where('is_active',1)->count();
        $design_sent_for_production = BusinessApplicationProcesses::where('business_status_id',1116)->where('design_status_id', 1116)
        ->where('production_status_id', 1116)
        ->where('is_active',1)->count();
        $corected_design_need_to_upload = BusinessApplicationProcesses::where('business_status_id',1115)->where('design_status_id', 1115)
        ->where('production_status_id', 1115)
        ->where('is_active',1)->count();

        $design_recived_for_production = BusinessApplicationProcesses::where('business_status_id',1112)->where('design_status_id', 1113)
        ->where('production_status_id', 1113)
        ->where('is_active',1)->count();
        $accepted_and_sent_to_store = BusinessApplicationProcesses::where('business_status_id',1112)->where('design_status_id', 1114)
        ->where('production_status_id', 1114)
        ->where('is_active',1)->count();
        $rejected_design_list_sent = BusinessApplicationProcesses::where('business_status_id',1115)->where('design_status_id', 1115)
        ->where('production_status_id', 1115)
        ->where('is_active',1)->count();
        $corected_design_list_recived = BusinessApplicationProcesses::where('business_status_id',1116)->where('design_status_id', 1116)
        ->where('production_status_id', 1116)
        ->where('is_active',1)->count();

        $material_need_to_sent_to_production = BusinessApplicationProcesses::where('business_status_id',1112)->where('design_status_id', 1114)
        ->where('production_status_id', 1114)
        ->where('is_active',1)->count();
        $material_sent_to_production = BusinessApplicationProcesses::where('business_status_id',1118)->where('design_status_id', 1114)
        ->where('production_status_id', 1119)->where('store_status_id', 1118)
        ->where('is_active',1)->count();
        $material_for_purchase = BusinessApplicationProcesses::where('business_status_id',1123)->where('design_status_id', 1114)
        ->where('production_status_id', 1117)->where('store_status_id',1123)
        ->where('is_active',1)->count();
        $material_received_from_quality = BusinessApplicationProcesses::where('business_status_id',1123)->where('design_status_id', 1114)
        ->where('production_status_id', 1117)->where('store_status_id',1123)
        ->where('is_active',1)->count();
        $rejected_chalan = BusinessApplicationProcesses::where('business_status_id',1116)->where('design_status_id', 1116)
        ->where('production_status_id', 1116)
        ->where('is_active',1)->count();

        $BOM_recived_for_purchase= BusinessApplicationProcesses::where('business_status_id',1123)->where('design_status_id', 1114)
        ->where('production_status_id', 1117)->where('store_status_id',1123)
        ->where('is_active',1)->count();
        $vendor_list = Vendors::where('is_active',1)->count();
        $tax = Tax::where('is_active',1)->count();
        $part_item = PartItem::where('is_active',1)->count();
        $purchase_order_approved = PurchaseOrderModel::where('purchase_status_from_owner',1127)->where('purchase_status_from_purchase',1126)
        ->where('is_active',1)->count();
        $purchase_order_submited_by_vendor =PurchaseOrderModel::where('purchase_status_from_owner',1129)->where('purchase_status_from_purchase',1129)
        ->where('is_active',1)->count();
     
        $get_pass = Gatepass::where('is_active',1)->count();
      

        $GRN_genration= PurchaseOrderModel::where('purchase_status_from_owner',1129)->where('purchase_status_from_purchase',1129)
        ->where('security_status_id',1132)->where('quality_status_id', null)->where('is_active',1)->count();
        $material_need_to_sent_to_production = PurchaseOrderModel::where('purchase_status_from_owner',1129)->where('purchase_status_from_purchase',1129)
        ->where('security_status_id',1132)->where('quality_status_id', 1134)->where('is_active',1)->count();
        $rejected_chalan_po_wise = RejectedChalan::where('chalan_no', '!=', '')->where('is_active', 1)->count();

        $dispatch_received_from_finance= BusinessApplicationProcesses::where('business_status_id',1112)->where('design_status_id', 1114)
        ->where('production_status_id', 1114)
        ->where('is_active',1)->count();
        $dispatch_completed = Vendors::where('is_active',1)->count();


        
        $leave_request= Leaves::where(['is_active' => 1, 'is_approved' => 0])->count();
        $accepted_leave_request = Leaves::where(['is_active' => 1, 'is_approved' => 2])->count();
        $rejected__leave_request = Leaves::where(['is_active' => 1, 'is_approved' => 1])->count();
        $total_employee= User::where('is_active',1)->count();
        $total_leaves_type= LeaveManagement::where('is_active',1)->count();
        $total_notice= Notice::where('is_active',1)->count();

        $ses_userId = session()->get('user_id');

        $employee_leave_request = Leaves::leftJoin('users', function($join) {
            $join->on('tbl_leaves.employee_id', '=', 'users.id');
        })
        ->where('users.id', $ses_userId)
        ->where('tbl_leaves.is_active', 1)
        ->where('tbl_leaves.is_approved', 0)
        ->count();
        $employee_accepted_leave_request = Leaves::leftJoin('users', function($join) {
            $join->on('tbl_leaves.employee_id', '=', 'users.id');
        })
        ->where('users.id', $ses_userId)
        ->where('tbl_leaves.is_active', 1)
        ->where('tbl_leaves.is_approved', 2)
        ->count();
        $employee_rejected_leave_request = Leaves::leftJoin('users', function($join) {
            $join->on('tbl_leaves.employee_id', '=', 'users.id');
        })
        ->where('users.id', $ses_userId)
        ->where('tbl_leaves.is_active', 1)
        ->where('tbl_leaves.is_approved', 1)
        ->count();
        // $employee_leave_type= LeaveManagement::where('is_active',1)->get();
        $employee_leave_type = LeaveManagement::where('is_active', 1)
        ->select('name', 'leave_count') 
        ->get();

        // $progressPercentage = min(100, max(0, $directorDeskCount));


        $counts = [
            'user_active_count' => $user_active_count,
            'active_businesses' => $active_count,
            'business_details' => $business_details_count,
            'business_completed' => $business_completed_count,
            'product_completed' => $product_completed_count,
            'business_inprocess' => $business_inprocess_count,
            'product_inprocess' => $product_inprocess_count,
            'data_output_offcanvas' => $data_output_offcanvas,
            'product_count'=>$product_count,
            
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
            'design_sent_for_production' => $design_sent_for_production,
            'corected_design_need_to_upload' => $corected_design_need_to_upload,
         ];
         $production_dept_counts = [
            'design_recived_for_production' => $design_recived_for_production,
            'accepted_and_sent_to_store' => $accepted_and_sent_to_store,
            'rejected_design_list_sent' => $rejected_design_list_sent,
            'corected_design_list_recived' => $corected_design_list_recived,
         ];
         $store_dept_counts = [
            'material_need_to_sent_to_production' => $material_need_to_sent_to_production,
            'material_sent_to_production' => $material_sent_to_production,
            'material_for_purchase' => $material_for_purchase,
            'material_received_from_quality' => $material_received_from_quality,
            'rejected_chalan' => $rejected_chalan,
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
            'material_need_to_sent_to_production' => $material_need_to_sent_to_production,
            'rejected_chalan_po_wise' => $rejected_chalan_po_wise,
         ];
         $logistics_counts = [
            'logistics_list_count' => $logistics_list_count,
            'logistics_send_by_finance_count' => $logistics_send_by_finance_count,
            'vehicle_type_count' => $vehicle_type_count,
        ];
        $dispatch_counts = [
            'dispatch_received_from_finance' => $dispatch_received_from_finance,
            'dispatch_completed' => $dispatch_completed,
           
        ];
        $hr_counts = [
            'leave_request' => $leave_request,
            'accepted_leave_request' => $accepted_leave_request,
            'rejected__leave_request' => $rejected__leave_request,
            'total_employee'=>$total_employee,
            'total_leaves_type'=>$total_leaves_type,
            'total_notice'=>$total_notice

           
        ];
        $employee_counts = [
            'employee_leave_request' => $employee_leave_request,
            'employee_accepted_leave_request' => $employee_accepted_leave_request,
            'employee_rejected_leave_request' => $employee_rejected_leave_request,
        ];

        return view('admin.pages.dashboard.dashboard', ['return_data' => $counts, 'cms_counts' =>$cms_counts, 'logistics_counts'=>$logistics_counts, 'design_dept_counts'=>$design_dept_counts,
    'production_dept_counts'=>$production_dept_counts, 'store_dept_counts'=>$store_dept_counts,
'purchase_dept_counts'=>$purchase_dept_counts, 'secuirty_dept_counts'=>$secuirty_dept_counts, 'quality_dept_counts'=>$quality_dept_counts,
'dispatch_counts'=>$dispatch_counts, 'hr_counts'=>$hr_counts, 'employee_counts'=>$employee_counts, 'employee_leave_type'=>$employee_leave_type ]);
    } catch (\Exception $e) {
        \Log::error("Error fetching business data: " . $e->getMessage());
        return redirect()->back()->with('error', 'An error occurred while fetching data.');
    }
}


    public function getNotification(Request $request)
    {


        $ses_userId = session()->get('user_id');
        $ses_roleId = session()->get('role_id');
        // dd($ses_userId);
        
            if ($ses_roleId == '1') {
                // Fetch design data
                $design_data = AdminView::where('current_department', 1112)
                                        ->where('is_view', '0')
                                        ->select('id')
                                        ->get();
                $design_count = $design_data->count();
            
                // Create notifications array
                $notifications[] = ['admin_count' => $design_count,
                        'message' => 'Business Sent For Design',
                        'url' => 'list-forwarded-to-design',
                ];

                $design_resend_data = AdminView::where('current_department', 1116)
                                        ->where('is_view', '0')
                                        ->select('id')
                                        ->get();
                $design_resend_count = $design_resend_data->count();
            
                // Create notifications array
                $notifications[] = ['admin_count' => $design_resend_count,
                        'message' => 'Design Resend To Production Department',
                        'url' => 'list-forwarded-to-design',
                ];
            
                // Fetch production data
                $prod_data = AdminView::where('current_department', 1113)
                                        ->where('is_view', '0')
                                        ->select('id')
                                        ->get();
                $prod_count = $prod_data->count();
            
                // Add production notification to the array
                $notifications[] = [
                    'admin_count' => $prod_count,
                    'message' => 'Business Sent For Production',
                    'url' => 'list-design-uploaded-owner',
                ];

                // Fetch production data
                $design_rejected_data = AdminView::where('current_department', 1115)
                                        ->where('is_view', '0')
                                        ->select('id')
                                        ->get();
                $design_rejected_count = $design_rejected_data->count();
            
                // Add production notification to the array
                $notifications[] = [
                    'admin_count' => $design_rejected_count,
                    'message' => 'Design Received For Design Correction',
                    'url' => 'list-design-uploaded-owner',
                ];

                $design_sended_store_data = AdminView::where('current_department', 1114)
                                        ->where('is_view', '0')
                                        ->select('id')
                                        ->get();
                $design_sended_store_count = $design_sended_store_data->count();
            
                // Add production notification to the array
                $notifications[] = [
                    'admin_count' => $design_sended_store_count,
                    'message' => 'Design Received For Design Correction',
                    'url' => 'list-design-uploaded-owner',
                ];

                $purchase_material_req_from_store = AdminView::where('current_department', 1123)
                                        ->where('is_view', '0')
                                        ->select('id')
                                        ->get();
                $purchase_material_req_from_store_count = $purchase_material_req_from_store->count();
            
                // Add production notification to the array
                $notifications[] = [
                    'admin_count' => $purchase_material_req_from_store_count,
                    'message' => 'Material Req. Sended To Purchase',
                    'url' => 'list-design-uploaded-owner',
                ];

                
            
                // Log the notifications for debugging
                // Log::info($notifications);
            
                // Calculate the total count
                $count = $design_count + $prod_count + $design_rejected_count + $design_resend_count + $purchase_material_req_from_store_count;

             }elseif($ses_roleId == '3'){

                $sent_to_prod_data = BusinessApplicationProcesses::where('business_status_id',1112)
                            ->where('design_status_id',1111)
                            ->where('design_is_view','0')
                            ->select('id')
                            ->get();
                        $received_for_design = $sent_to_prod_data->count();

                        $notifications[] = ['admin_count' => $received_for_design,
                            'message' => 'Business Recrived For Design',
                            'url' => 'list-new-requirements-received-for-design'
                        ];

                $rejected_design_data = BusinessApplicationProcesses::where('production_status_id',1115)
                            ->where('business_status_id',1115)
                            ->where('design_is_view_rejected','0')
                            ->select('id')
                            ->get();
                        $rejected_count = $rejected_design_data->count();

                        $notifications[] = ['admin_count' => $rejected_count,
                             'message' => 'Business Recrived For Design Revised',
                             'url' => 'list-reject-design-from-prod'
                        ];
                        $count = $received_for_design + $rejected_count;
        }elseif($ses_roleId == '4'){
            $received_for_production = BusinessApplicationProcesses::where('production_status_id',1113)
                 ->where('design_status_id',1113)
                 ->where('prod_is_view','0')
                 ->select('id')
                 ->get();
                 $received_for_production_count = $received_for_production->count();
     
                 $notifications[] = ['admin_count' => $received_for_production_count,
                     'message' => 'New Design Received For Production',
                    'url' => 'list-new-requirements-received-for-production'
                    ];

                    $received_for_production_revised = BusinessApplicationProcesses::where('production_status_id',1116)
                 ->where('design_status_id',1116)
                 ->where('prod_is_view_revised','0')
                 ->select('id')
                 ->get();
                 $received_for_production_revised_count = $received_for_production_revised->count();
     
                 $notifications[] = ['admin_count' => $received_for_production_revised_count,
                     'message' => 'Revised Design Received For Production',
                    'url' => 'list-new-requirements-received-for-production'
                    ];

                    $material_received = BusinessApplicationProcesses::where('production_status_id',1119)
                    ->where('store_status_id', 1118)
                 ->where('prod_is_view_material_received','0')
                 ->select('id')
                 ->get();
                 $material_received_count = $material_received->count();
     
                 $notifications[] = ['admin_count' => $material_received_count,
                     'message' => 'Material Received From Store',
                    'url' => 'list-material-recived'
                    ];

                    $count = $received_for_production_count + $received_for_production_revised_count + $material_received_count;
             }elseif($ses_roleId == '7'){
                $received_to_store = BusinessApplicationProcesses::where('production_status_id',1114)
                     ->where('design_status_id',1114)
                     ->where('store_is_view','0')
                     ->select('id')
                     ->get();
                     $received_to_store_count = $received_to_store->count();
         
                     $notifications[] = ['admin_count' => $received_to_store_count,
                         'message' => 'New Design Received To Store',
                        'url' => 'list-new-requirements-received-for-production'
                        ];
    
                        $count = $received_to_store_count;
                 }elseif($ses_roleId == '2'){
                    $received_to_store = BusinessApplicationProcesses::where('store_status_id',1123)
                         ->where('business_status_id',1123)
                         ->where('purchase_is_view','0')
                         ->select('id')
                         ->get();
                         $received_to_store_count = $received_to_store->count();
             
                         $notifications[] = ['admin_count' => $received_to_store_count,
                             'message' => 'New Req. Received From Store',
                            'url' => 'list-new-requirements-received-for-production'
                            ];
        
                            $count = $received_to_store_count;
                     }



            return response()->json([
                'notification_count' => $count,
                'notifications' => $notifications
            ]);
        
    }
    

}