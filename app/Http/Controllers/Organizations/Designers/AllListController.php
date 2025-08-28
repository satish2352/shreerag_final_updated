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
                $business_ids = $data_output->pluck('business_details_id')->all();
                if (!empty($business_ids)) {
                    $update_data = ['designer_is_view_accepted_design' => '1'];
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
   public function getAllListDesignReceivedForCorrection( Request $request ){ //checked
        try {
            $data_output = $this->service->getAllListDesignRecievedForCorrection();
            if ( $data_output->isNotEmpty() ) {
                foreach ( $data_output as $data ) {
                    $business_id = $data->id;
                    if ( !empty( $business_id ) ) {
                        $update_data[ 'prod_design_rejected' ] = '1';
                        NotificationStatus::where( 'prod_design_rejected', '0' )
                        ->where( 'id', $business_id )
                        ->update( $update_data );
                    }
                }
            } else {
                return view( 'organizations.designer.list.list_design_received_from_production_for_correction', [
                    'data_output' => [],
                    'message' => 'No data found for designs received for correction'
                ] );
            }
            return view( 'organizations.designer.list.list_design_received_from_production_for_correction', compact( 'data_output' ) );
        } catch ( \Exception $e ) {
            \Log::error( 'Error in getting design corrections: ' . $e->getMessage() );
            return back()->withErrors( 'Something went wrong while fetching design corrections. Please try again later.' );
        }
    }

  public function getAllListCorrectedDesignSendToProduction() {
        try {
            $data_output = $this->service->getAllListCorrectedDesignSendToProduction();
            if ($data_output->isNotEmpty()) {
                $business_ids = $data_output->pluck('business_details_id')->all();
                if (!empty($business_ids)) {
                    $update_data = ['designer_is_view_accepted_design' => '1'];
                    NotificationStatus::whereIn('business_details_id', $business_ids)
                        ->where('designer_is_view_accepted_design', '0')
                        ->update($update_data);
                }
    
                return view('organizations.designer.list.list-corrected-design-send-to-production', compact('data_output'));
            } else {
                return view('organizations.designer.list.list-corrected-design-send-to-production', [
                    'data_output' => [],
                    'message' => 'No data found',
                ]);
            }
        } catch (\Exception $e) {
            return $e;
        }
    }


}