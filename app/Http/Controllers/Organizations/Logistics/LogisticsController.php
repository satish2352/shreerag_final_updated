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
    TransportName,
    Logistics
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
            $purchase_order_data = Logistics::where('quantity_tracking_id', $business_id)
            ->leftJoin('tbl_customer_product_quantity_tracking', function ($join) {
                $join->on('tbl_logistics.quantity_tracking_id', '=', 'tbl_customer_product_quantity_tracking.id');
            })
            ->leftJoin('businesses', function($join) {
                $join->on('tbl_logistics.business_id', '=', 'businesses.id');
            })
             ->leftJoin('businesses_details', function($join) {
                $join->on('tbl_logistics.business_details_id', '=', 'businesses_details.id');
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
        'vehicle_type_id' => 'required',
        'transport_name_id' => 'required',
        'truck_no' => 'required',
        'from_place' => 'required',
        'to_place' => 'required',
        ];

        $messages = [
            'vehicle_type_id.required' => 'The vehicle type is required.',
            'transport_name_id.required' => 'The transport name is required.',
            'truck_no.required' => 'The truck number is required.',
            'from_place.required' => 'The origin place is required.',
            'to_place.required' => 'The destination place is required.',
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
    
    
    public function sendToFianance($id,  $business_details_id){
        try {
            $accepted = base64_decode($id);
            $update_data = $this->service->sendToFianance($accepted,  $business_details_id);
            return redirect('logisticsdept/list-logistics');
        } catch (\Exception $e) {
            return $e;
        }
    } 
}