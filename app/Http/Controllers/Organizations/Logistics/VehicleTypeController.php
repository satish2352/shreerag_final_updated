<?php

namespace App\Http\Controllers\Organizations\Logistics;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Organizations\Logistics\VehicleTypeServices;
use Illuminate\Support\Facades\Validator;
use Exception;
use App\Models\OrganizationModel;

class VehicleTypeController extends Controller
{

    protected $service;

    public function __construct()
    {
        $this->service = new VehicleTypeServices();
    }

    public function index()
    {
        try {
            $getOutput = $this->service->getAll();
            return view('organizations.logistics.vehicle-type.list-vehicle-type', compact('getOutput'));
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function add()
    {
        return view('organizations.logistics.vehicle-type.add-vehicle-type');
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|max:255',

        ];

        $messages = [
            'name.required' => 'Please enter the department name.',
            // 'name.unique' => 'Part Name already exist.',
            'name.max' => 'The name must not exceed 255 characters.',
        ];

        try {
            $validation = Validator::make($request->all(), $rules, $messages);

            if ($validation->fails()) {
                return redirect('add-vehicle-type')
                    ->withInput()
                    ->withErrors($validation);
            } else {
                $add_record = $this->service->addAll($request);
                if ($add_record) {
                    $msg = $add_record['msg'];
                    $status = $add_record['status'];

                    if ($status == 'success') {
                        return redirect('logisticsdept/list-vehicle-type')->with(compact('msg', 'status'));
                    } else {
                        return redirect('logisticsdept/add-vehicle-type')->withInput()->with(compact('msg', 'status'));
                    }
                }
            }
        } catch (Exception $e) {
            return redirect('logisticsdept/add-vehicle-type')->withInput()->with(['msg' => $e->getMessage(), 'status' => 'error']);
        }
    }

    public function edit(Request $request)
    {
        $edit_data_id = base64_decode($request->id);
        $editData = $this->service->getById($edit_data_id);
        $data = OrganizationModel::orderby('updated_at', 'desc')->get();
        return view('organizations.logistics.vehicle-type.edit-vehicle-type', compact('editData', 'data'));
    }

    public function update(Request $request)
    {
        $id = $request->edit_id;
        $rules = [
            // 'name' => [ 'required', 'max:255', Rule::unique( 'tbl_part_item', 'name' )->ignore( $id, 'id' ) ],
        ];

        $messages = [
            // 'name.required' => 'Please enter the department name.',
            // 'name.string' => 'The company name must be a valid string.',
            // 'name.max' => 'The company name must not exceed 255 characters.',
            // 'name.unique' => 'Part Name Already Exist.',
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
                        return redirect('logisticsdept/list-vehicle-type')->with(compact('msg', 'status'));
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
                    return redirect('logisticsdept/list-vehicle-type')->with(compact('msg', 'status'));
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
