<?php

namespace App\Http\Controllers\Organizations\Purchase;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Organizations\Purchase\VendorServices;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use Carbon\Carbon;
use Exception;
    // use App\Models\ {
    //     DesignModel,
    //     DesignDetailsModel
    //     }
;

class VendorController extends Controller
{

    protected $service;

    public function __construct()
    {
        $this->service = new VendorServices();
    }

    public function index()
    {
        try {

            $data_output = $this->service->getAll();
            return view('organizations.purchase.vendor.list-vendor', compact('data_output'));
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function add()
    {
        try {

            return view('organizations.purchase.vendor.add-vendor');
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function store(Request $request)
    {
        $rules = [
            'vendor_name' => 'required|string|max:255',
            'vendor_email' => 'required',
            'contact_no' => 'required',
            'gst_no' => 'required',
            'quote_no' => 'required',
            'payment_terms' => 'required',
            'vendor_address' => 'required',
        ];

        $messages = [
            'vendor_name.required' => 'The design vendor_name is required.',
            'vendor_name.string' => 'The design vendor_name must be a valid string.',
            'vendor_name.max' => 'The design vendor_name must not exceed 255 characters.',

            'vendor_email.required' => 'The vendor_email is required.',
            'contact_no.required' => 'The contact_no is required.',
            'gst_no.required' => 'The gst_no is required.',
            'quote_no.required' => 'The gst_no is required.',
            'vendor_address.required' => 'The gst_no is required.',
        ];

        try {
            $validation = Validator::make($request->all(), $rules, $messages);

            if ($validation->fails()) {
                return redirect('purchase/add-vendor')
                    ->withInput()
                    ->withErrors($validation);
            } else {
                $add_record = $this->service->addAll($request);

                if ($add_record) {
                    $msg = $add_record['msg'];
                    $status = $add_record['status'];

                    if ($status == 'success') {
                        return redirect('purchase/list-vendor')->with(compact('msg', 'status'));
                    } else {
                        return redirect('purchase/add-vendor')->withInput()->with(compact('msg', 'status'));
                    }
                }
            }
        } catch (Exception $e) {
            return redirect('purchase/add-vendor')->withInput()->with(['msg' => $e->getMessage(), 'status' => 'error']);
        }
    }

    public function edit(Request $request)
    {
        try {

            $edit_data_id = base64_decode($request->id);
            $editData = $this->service->getById($edit_data_id);
            return view('organizations.purchase.vendor.edit-vendor', compact('editData'));
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function update(Request $request)
    {
        $rules = [
            'vendor_name' => 'required|string|max:255',
            'vendor_email' => 'required',
            'contact_no' => 'required',
            'gst_no' => 'required',
            'quote_no' => 'required',
            'payment_terms' => 'required',
            'vendor_address' => 'required',
        ];

        $messages = [
            'vendor_name.required' => 'The design vendor_name is required.',
            'vendor_name.string' => 'The design vendor_name must be a valid string.',
            'vendor_name.max' => 'The design vendor_name must not exceed 255 characters.',

            'vendor_email.required' => 'The vendor_email is required.',
            'contact_no.required' => 'The contact_no is required.',
            'gst_no.required' => 'The gst_no is required.',
            'quote_no.required' => 'The gst_no is required.',
            'vendor_address.required' => 'The gst_no is required.',
        ];

        try {
            $validation = Validator::make($request->all(), $rules, $messages);
            if ($validation->fails()) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors($validation);
            } else {
                $update_data = $this->service->updateAll($request);
                if ($update_data) {
                    $msg = $update_data['msg'];
                    $status = $update_data['status'];
                    if ($status == 'success') {
                        return redirect('purchase/list-vendor')->with(compact('msg', 'status'));
                    } else {
                        return redirect()->back()
                            ->withInput()
                            ->with(compact('msg', 'status'));
                    }
                }
            }
        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with(['msg' => $e->getMessage(), 'status' => 'error']);
        }
    }

    public function destroy(Request $request)
    {
        $delete_data_id = base64_decode($request->id);
        try {
            $delete_record = $this->service->deleteById($delete_data_id);

            if ($delete_record) {
                $msg = $delete_record['msg'];
                $status = $delete_record['status'];
                if ($status == 'success') {
                    return redirect('purchase/list-vendor')->with(compact('msg', 'status'));
                } else {
                    return redirect()->back()
                        ->withInput()
                        ->with(compact('msg', 'status'));
                }
            }
        } catch (\Exception $e) {
            return $e;
        }
    }
}
