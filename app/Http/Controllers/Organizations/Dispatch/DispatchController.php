<?php

namespace App\Http\Controllers\Organizations\Dispatch;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Organizations\Dispatch\DispatchServices;
use App\Http\Services\Organizations\Business\BusinessServices;
use App\Models\ {
    
    BusinessApplicationProcesses,
    Vendors
    };
use Session;
use Validator;
use Config;
use Carbon;

class DispatchController extends Controller
{ 
    public function __construct(){
        $this->service = new DispatchServices();
        $this->business_service = new BusinessServices();
    }
  
    public function addDispatch($business_id)
    {
        try {
            
            $purchase_order_data = BusinessApplicationProcesses::where('business_id', '=', $business_id)->first();
            $edit_data_id = $business_id;
            $editData = $this->business_service->getById($edit_data_id);
            return view('organizations.dispatch.dispatchdept.add-dispatch', compact('editData')
        );
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function storeDispatch(Request $request)
    {
        $rules = [
            'outdoor_no' => 'required',
            'gate_entry' => 'required',
            'remark' => 'required',
        ];

        $messages = [
            'outdoor_no.required' => 'The outdoor number is required.',
            'gate_entry.required' => 'The gate entry is required.',
            'remark.required' => 'The remark is required.',
        ];

        try {
            $validation = Validator::make($request->all(), $rules, $messages);

            if ($validation->fails()) {
                return redirect('dispatchdept/list-dispatch')
                    ->withInput()
                    ->withErrors($validation);
            } else {
                $add_record = $this->service->storeDispatch($request);
                if ($add_record) {
                    $msg = $add_record['msg'];
                    $status = $add_record['status'];

                    if ($status == 'success') {
                        return redirect('dispatchdept/list-dispatch')->with(compact('msg', 'status'));
                    } else {
                        return redirect('dispatchdept/list-dispatch')->withInput()->with(compact('msg', 'status'));
                    }
                }
            }
        } catch (Exception $e) {
            return redirect('dispatch/dispatchdept/add-dispatch')->withInput()->with(['msg' => $e->getMessage(), 'status' => 'error']);
        }
    }
        
}