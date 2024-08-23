<?php

namespace App\Http\Controllers\Admin\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// use App\Http\Services\DashboardServices;
use App\Models\ {
    Business,
    BusinessDetails,
    BusinessApplicationProcesses

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

    // public function index()
    // {
    //     try {
    //         // Get the counts
    //         $active_count = Business::where('is_active', 1)->count(); 
    //         $business_details_count = BusinessDetails::where('is_active', 1)->count(); 
    //         $business_completed_count = BusinessApplicationProcesses::where('is_active', 1)
    //             ->where('dispatch_status_id', 1148)
    //             ->count();
    //         $product_completed_count = BusinessApplicationProcesses::where('is_active', 1)
    //             ->where('dispatch_status_id', 1148)
    //             ->count();
    //         $business_inprocess_count = BusinessApplicationProcesses::where('is_active', 1)
    //             ->where('dispatch_status_id', 1148)
    //             ->count();
    //         $product_inprocess_count = BusinessApplicationProcesses::where('is_active', 1)
    //             ->where('dispatch_status_id', 1148)
    //             ->count();

    //             $data_output_offcanvas  = BusinessApplicationProcesses::leftJoin('businesses', function ($join) {
    //                 $join->on('business_application_processes.business_id', '=', 'businesses.id');
    //               })
    //             ->leftJoin('businesses_details', function($join) {
    //                 $join->on('business_application_processes.business_details_id', '=', 'businesses_details.id');
    //             })
    //             ->where('dispatch_status_id', 1148)
    //             ->get();
 
    //         // Prepare the data for the chart
    //         $counts = [
    //             'active_businesses' => $active_count,
    //             'business_details' => $business_details_count,
    //             'business_completed' => $business_completed_count,
    //             'product_completed' => $product_completed_count,
    //             'business_inprocess' => $business_inprocess_count,
    //             'product_inprocess' => $product_inprocess_count,
    //             'data_output_offcanvas' => $data_output_offcanvas,
    //         ];

    //         // dd($data_output_offcanvas);
    //         // die();
    
    //         return view('admin.pages.dashboard.dashboard', ['return_data' => $counts]);
    //     } catch (\Exception $e) {
    //         \Log::error("Error fetching business data: " . $e->getMessage());
    //         return redirect()->back()->with('error', 'An error occurred while fetching data.');
    //     }
    // }

    public function index()
{
    try {
        // Get the counts
        $active_count = Business::where('is_active', 1)->count(); 
        $business_details_count = BusinessDetails::where('is_active', 1)->count(); 
        $business_completed_count = BusinessApplicationProcesses::where('is_active', 1)
            ->where('dispatch_status_id', 1148)
            ->count();
        $product_completed_count = BusinessApplicationProcesses::where('is_active', 1)
            ->where('dispatch_status_id', 1148)
            ->count();
        $business_inprocess_count = BusinessApplicationProcesses::where('is_active', 1)
            ->where('dispatch_status_id', 1148)
            ->count();
        $product_inprocess_count = BusinessApplicationProcesses::where('is_active', 1)
            ->where('dispatch_status_id', 1148)
            ->count();

        $data_output_offcanvas = BusinessApplicationProcesses::leftJoin('businesses', function ($join) {
                $join->on('business_application_processes.business_id', '=', 'businesses.id');
            })
            ->leftJoin('businesses_details', function ($join) {
                $join->on('business_application_processes.business_details_id', '=', 'businesses_details.id');
            })
            ->where('businesses.is_active', 1)
            ->select('businesses.customer_po_number','businesses.title','businesses_details.product_name',
            'business_application_processes.business_status_id','businesses.updated_at', 'business_application_processes.design_status_id', 'business_application_processes.production_status_id') // Adjust if you need more fields
            ->orderBy('businesses.updated_at', 'desc')
            ->get();
        
        // Prepare the data for the chart
        $counts = [
            'active_businesses' => $active_count,
            'business_details' => $business_details_count,
            'business_completed' => $business_completed_count,
            'product_completed' => $product_completed_count,
            'business_inprocess' => $business_inprocess_count,
            'product_inprocess' => $product_inprocess_count,
            'data_output_offcanvas' => $data_output_offcanvas,
        ];

        return view('admin.pages.dashboard.dashboard', ['return_data' => $counts]);
    } catch (\Exception $e) {
        \Log::error("Error fetching business data: " . $e->getMessage());
        return redirect()->back()->with('error', 'An error occurred while fetching data.');
    }
}

    

}