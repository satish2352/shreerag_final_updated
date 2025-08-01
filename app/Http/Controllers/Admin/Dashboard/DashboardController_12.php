<?php

namespace App\Http\Controllers\Admin\Dashboard;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// use App\Http\Services\DashboardServices;
use DB;
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
    TransportName,
    NotificationStatus,
    RolesModel,
    ProductionModel,
    DeliveryChalan,
    ReturnableChalan,
    BusinessDetails,
    Logistics,
    CustomerProductQuantityTracking,
    PurchaseOrdersModel

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
   public function index() {
        try {
           
            return view( 'admin.pages.dashboard.dashboard');
        } catch ( \Exception $e ) {
            return $e;
        }
    }
}