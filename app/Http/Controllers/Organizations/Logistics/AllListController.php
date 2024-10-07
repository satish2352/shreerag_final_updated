<?php

namespace App\Http\Controllers\Organizations\Logistics;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Organizations\Logistics\AllListServices;
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
  
    public function getAllCompletedProduction(){
        try {
            $data_output = $this->service->getAllCompletedProduction();
            if ($data_output->isNotEmpty()) {
                foreach ($data_output as $data) {
                    $business_details_id = $data->id; 
                    if (!empty($business_details_id)) {
                        $update_data['production_completed'] = '1';
                        NotificationStatus::where('production_completed', '0')
                            ->where('business_details_id', $business_details_id)
                            ->update($update_data);
                    }
                }
            } else {
                return view('organizations.logistics.logisticsdept.list-production-completed', [
                    'data_output' => [],
                    'message' => 'No data found for designs received for correction'
                ]);
            }


           
            return view('organizations.logistics.logisticsdept.list-production-completed', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getAllLogistics(){
        try {
            $data_output = $this->service->getAllLogistics();
            return view('organizations.logistics.logisticsdept.list-logistics', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getAllListSendToFiananceByLogistics(){
        try {
            $data_output = $this->service->getAllListSendToFiananceByLogistics();
            return view('organizations.logistics.logisticsdept.list-send-to-fianance-by-logistics', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }
    
    
}