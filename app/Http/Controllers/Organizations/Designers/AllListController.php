<?php

namespace App\Http\Controllers\Organizations\Designers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Organizations\Designers\AllListServices;
use Session;
use Validator;
use Config;
use Carbon;
use App\Models\{
    BusinessApplicationProcesses,
    AdminView,
    NotificationStatus
};

class AllListController extends Controller
{ 
    public function __construct(){
        $this->service = new AllListServices();
    }
    public function acceptdesignbyProduct(){
        try {
            // $acceptdesign = base64_decode($id);
           
            $data_output = $this->service->acceptdesignbyProduct();
            
            // dd($data_output );
            // die();

        if ($data_output->isNotEmpty()) {
            foreach ($data_output as $data) {
                $business_id = $data->id; 
                if (!empty($business_id)) {
                    $update_data['designer_is_view_accepted_design'] = '1';
                    NotificationStatus::where('designer_is_view_accepted_design', '0')
                        ->where('id', $business_id)
                        ->update($update_data);
                }
            }
        } else {
            return view('organizations.designer.list.list-accept-design-by-production', [
                'data_output' => [],
                'message' => 'No data found'
            ]);
        }
            return view('organizations.designer.list.list-accept-design-by-production', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    } 
    public function getAllListDesignRecievedForCorrection(Request $request)
    {
        try {
            // Retrieve the list of designs received for correction
            $data_output = $this->service->getAllListDesignRecievedForCorrection();
    // dd($data_output);
    // die();
            // Check if $data_output is not empty
            if ($data_output->isNotEmpty()) {
                // Loop through each item in $data_output to process multiple business_ids
                foreach ($data_output as $data) {
                    $business_id = $data->id; // Get each business_id from the data
    // dd($business_id);
    // die();
                    // Check if business_id is valid (not null or empty)
                    if (!empty($business_id)) {
                        // Prepare the update data
                        $update_data['prod_design_rejected'] = '1';
    
                        // Update the NotificationStatus where the prod_design_rejected is '0' and business_id matches
                        NotificationStatus::where('prod_design_rejected', '0')
                            ->where('id', $business_id)
                            ->update($update_data);
                    }
                }
            } else {
                // If no data is found, just return the view with an empty result and handle in the view
                return view('organizations.designer.list.list_design_received_from_production_for_correction', [
                    'data_output' => [],
                    'message' => 'No data found for designs received for correction'
                ]);
            }
    
            // Return the view with the data_output
            return view('organizations.designer.list.list_design_received_from_production_for_correction', compact('data_output'));
    
        } catch (\Exception $e) {
            // Handle the exception properly, log the error, and return a user-friendly message
            \Log::error('Error in getting design corrections: ' . $e->getMessage());
            return back()->withErrors('Something went wrong while fetching design corrections. Please try again later.');
        }
    }
    

}