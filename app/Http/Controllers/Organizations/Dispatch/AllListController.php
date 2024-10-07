<?php

namespace App\Http\Controllers\Organizations\Dispatch;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Organizations\Dispatch\AllListServices;
use Session;
use Validator;
use Config;
use Carbon;
use App\Models\{
    NotificationStatus
};


class AllListController extends Controller
{ 
    public function __construct(){
        $this->service = new AllListServices();
    }
  
    public function getAllReceivedFromFianance(){
        try {
            $data_output = $this->service->getAllReceivedFromFianance();
            if ($data_output->isNotEmpty()) {
                foreach ($data_output as $data) {
                    $business_details_id = $data->id; 
                    if (!empty($business_details_id)) {
                        $update_data['fianance_to_dispatch_visible'] = '1';
                        NotificationStatus::where('fianance_to_dispatch_visible', '0')
                            ->where('business_details_id', $business_details_id)
                            ->update($update_data);
                    }
                }
            } else {
                return view('organizations.dispatch.dispatchdept.list-business-received-from-fianance', [
                    'data_output' => [],
                    'message' => 'No data found for designs received for correction'
                ]);
            }
            return view('organizations.dispatch.dispatchdept.list-business-received-from-fianance', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getAllDispatch(){
        try {
            $data_output = $this->service->getAllDispatch();
            return view('organizations.dispatch.dispatchdept.list-dispatch', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }
    
}