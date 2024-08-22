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
    //         // Get total count of all active business records (where is_active = 1)
    //         $active_count = Business::where('is_active', 1)->count(); 
    //         $business_details_count = BusinessDetails::where('is_active', 1)->count(); 
    //         $business_completed_count = BusinessApplicationProcesses::where('is_active', 1)
    //         ->where('dispatch_status_id', 1148)
    //         ->count();
    //         $product_completed_count = BusinessApplicationProcesses::where('is_active', 1)
    //         ->where('dispatch_status_id', 1148)
    //         ->count();
    //         $business_inprocess_count = BusinessApplicationProcesses::where('is_active', 1)
    //         ->where('dispatch_status_id', 1148)
    //         ->count();
    //         $product_inprocess_count = BusinessApplicationProcesses::where('is_active', 1)
    //         ->where('dispatch_status_id', 1148)
    //         ->count();
    //         // Return the view with the active count
    //         return view('admin.pages.dashboard.dashboard', compact('active_count', 'business_details_count',
    //         'business_completed_count','product_completed_count', 'business_inprocess_count', 'product_inprocess_count'));
            
    //     } catch (\Exception $e) {
    //         // Handle the exception (log it, return a response, etc.)
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
    
            // Prepare the data for the chart
            $counts = [
                'active_businesses' => $active_count,
                'business_details' => $business_details_count,
                'business_completed' => $business_completed_count,
                'product_completed' => $product_completed_count,
                'business_inprocess' => $business_inprocess_count,
                'product_inprocess' => $product_inprocess_count,
            ];
    
            return view('admin.pages.dashboard.dashboard', ['return_data' => $counts]);
        } catch (\Exception $e) {
            \Log::error("Error fetching business data: " . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while fetching data.');
        }
    }
    

}