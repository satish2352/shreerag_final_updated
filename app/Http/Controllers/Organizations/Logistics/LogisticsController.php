<?php

namespace App\Http\Controllers\Organizations\Logistics;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Organizations\Logistics\LogisticsServices;
use App\Http\Services\Organizations\Business\BusinessServices;
use App\Models\ {
    
    BusinessApplicationProcesses,
    Vendors,
    Business,
    VehicleType,
    TransportName
    };
use Session;
use Validator;
use Config;
use Carbon;

class LogisticsController extends Controller
{ 
    public function __construct(){
        $this->service = new LogisticsServices();
        $this->business_service = new BusinessServices();
    }
  
    public function addLogistics($business_id)
    {
        try {
            
            $purchase_order_data = BusinessApplicationProcesses::where('business_details_id', $business_id)
            ->leftJoin('businesses', function ($join) {
                $join->on('business_application_processes.business_id', '=', 'businesses.id');
            })
            
            ->leftJoin('businesses_details', function($join) {
                $join->on('business_application_processes.business_details_id', '=', 'businesses_details.id');
            })
            ->first();
            $dataOutputVendor = Vendors::where('is_active', 1)->get();
            $dataOutputVehicleType = VehicleType::where('is_active', 1)->get();
            $dataOutputTransportName = TransportName::where('is_active', 1)->get();
            $editData = $purchase_order_data;
            return view('organizations.logistics.logisticsdept.add-logistics'
            , compact('dataOutputVendor', 'editData', 'dataOutputVehicleType', 'dataOutputTransportName')
        );
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function storeLogistics(Request $request)
    {
        $rules = [
            // 'chalan_no' => 'required',
            // 'reference_no' => 'required',
            // 'remark' => 'required',
        ];

        $messages = [
            // 'chalan_no.required' => 'The chalan number is required.',
            // 'reference_no.required' => 'The reference number is required.',
            // 'remark.required' => 'The remark is required.',
        ];

        try {
            $validation = Validator::make($request->all(), $rules, $messages);

            if ($validation->fails()) {
                return redirect('logisticsdept/add-logistics')
                    ->withInput()
                    ->withErrors($validation);
            } else {
                $add_record = $this->service->storeLogistics($request);
                if ($add_record) {
                    $msg = $add_record['msg'];
                    $status = $add_record['status'];

                    if ($status == 'success') {
                        return redirect('logisticsdept/list-logistics')->with(compact('msg', 'status'));
                    } else {
                        return redirect('logisticsdept/list-logistics')->withInput()->with(compact('msg', 'status'));
                    }
                }
            }
        } catch (Exception $e) {
            return redirect('logisticsdept/add-logistics')->withInput()->with(['msg' => $e->getMessage(), 'status' => 'error']);
        }
    }
    
    
    public function sendToFianance($id){
        try {
            $accepted = base64_decode($id);
            $update_data = $this->service->sendToFianance($accepted);
            return redirect('logisticsdept/list-logistics');
        } catch (\Exception $e) {
            return $e;
        }
    } 
}