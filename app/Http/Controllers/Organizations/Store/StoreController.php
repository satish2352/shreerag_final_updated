<?php

namespace App\Http\Controllers\Organizations\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Organizations\Store\StoreServices;
use Session;
use Validator;
use Config;
use Carbon;


class StoreController extends Controller
{
    public function __construct()
    {
        $this->service = new StoreServices();
    }



    public function orderAcceptedAndMaterialForwareded($id)
    {
        try {
            $acceptdesign = base64_decode($id);
            $update_data = $this->service->orderAcceptedAndMaterialForwareded($acceptdesign);
            return redirect('list-accepted-design-from-prod');
        } catch (\Exception $e) {
            return $e;
        }
    }


    public function createRequesition($createRequesition)
    {
        try {
            return view('organizations.store.requistion.add-requistion', compact('createRequesition'));
        } catch (\Exception $e) {
            return $e;
        }
    }



    public function storeRequesition(Request $request)
    {
        // $rules = [
        //     'production_id' => 'required',
        //     // 'bom_file_req' => 'required|image|mimes:jpeg,png,jpg|',
        // ];

        // $rules['bom_file_req'] = 'required|image|mimes:xls,xlsx|max:' . Config::get("AllFileValidation.REQUISITION_IMAGE_MAX_SIZE") . '|min:' . Config::get("AllFileValidation.REQUISITION_IMAGE_MIN_SIZE");


        // $messages = [
        //     'production_id.required' => '',

        //     // 'bom_file_req.required' => 'The image is required.',
        //     // 'bom_file_req.mimes' => 'The image must be excel format.',
        //     // 'bom_file_req.max' => 'The image size must not exceed ' . Config::get("AllFileValidation.REQUISITION_IMAGE_MAX_SIZE") . 'KB .',
        //     // 'bom_file_req.min' => 'The image size must not be less than ' . Config::get("AllFileValidation.REQUISITION_IMAGE_MIN_SIZE") . 'KB .',
        // ];


        try {
            // $validation = Validator::make($request->all(), $rules, $messages);

            // if ($validation->fails()) {
            //     return redirect('add-requistion')
            //         ->withInput()
            //         ->withErrors($validation);
            // } else {
                $add_record = $this->service->addAll($request);

                if ($add_record) {
                    $msg = $add_record['msg'];
                    $status = $add_record['status'];

                    if ($status == 'success') {
                        return redirect('list-requistion')->with(compact('msg', 'status'));
                    } else {
                        return redirect('add-requistion')->withInput()->with(compact('msg', 'status'));
                    }
                }
            // }
        } catch (Exception $e) {
            return redirect('add-requistion')->withInput()->with(['msg' => $e->getMessage(), 'status' => 'error']);
        }
    }



}
