<?php

namespace App\Http\Controllers\Organizations\Designers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Exports\DesignReportExport;
use App\Http\Services\Organizations\Designers\AllListServices;
use Session;
use Validator;
use Config;
use Carbon;
use App\Models\ {
    BusinessApplicationProcesses,
    AdminView,
    NotificationStatus,
    Business
}
;

class AllListController extends Controller
 {

    public function __construct() {
        $this->service = new AllListServices();
    }
    public function acceptdesignbyProduct() {
        try {
            $data_output = $this->service->acceptdesignbyProduct();
            
            if ($data_output->isNotEmpty()) {
                // Collect all IDs for updating
                $business_ids = $data_output->pluck('business_details_id')->all();
                if (!empty($business_ids)) {
                    $update_data = ['designer_is_view_accepted_design' => '1'];
    
                    // Update status for all records in one query
                    NotificationStatus::whereIn('business_details_id', $business_ids)
                        ->where('designer_is_view_accepted_design', '0')
                        ->update($update_data);
                }
    
                return view('organizations.designer.list.list-accept-design-by-production', compact('data_output'));
            } else {
                return view('organizations.designer.list.list-accept-design-by-production', [
                    'data_output' => [],
                    'message' => 'No data found',
                ]);
            }
        } catch (\Exception $e) {
            return $e;
        }
    }
   public function getAllListDesignRecievedForCorrection( Request $request )
 {
        try {
            // Retrieve the list of designs received for correction
            $data_output = $this->service->getAllListDesignRecievedForCorrection();
            // Check if $data_output is not empty
            if ( $data_output->isNotEmpty() ) {
                // Loop through each item in $data_output to process multiple business_ids
                foreach ( $data_output as $data ) {
                    $business_id = $data->id;
                    // Get each business_id from the data
                    // Check if business_id is valid ( not null or empty )
                    if ( !empty( $business_id ) ) {
                        // Prepare the update data
                        $update_data[ 'prod_design_rejected' ] = '1';

                        // Update the NotificationStatus where the prod_design_rejected is '0' and business_id matches
                        NotificationStatus::where( 'prod_design_rejected', '0' )
                        ->where( 'id', $business_id )
                        ->update( $update_data );
                    }
                }
            } else {
                // If no data is found, just return the view with an empty result and handle in the view
                return view( 'organizations.designer.list.list_design_received_from_production_for_correction', [
                    'data_output' => [],
                    'message' => 'No data found for designs received for correction'
                ] );
            }

            // Return the view with the data_output
            return view( 'organizations.designer.list.list_design_received_from_production_for_correction', compact( 'data_output' ) );

        } catch ( \Exception $e ) {
            // Handle the exception properly, log the error, and return a user-friendly message
            \Log::error( 'Error in getting design corrections: ' . $e->getMessage() );
            return back()->withErrors( 'Something went wrong while fetching design corrections. Please try again later.' );
        }
    }

}