<?php

namespace App\Http\Controllers\Organizations\Purchase;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Organizations\Purchase\ItemServices;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use Carbon\Carbon;
use Exception;
use App\Models\{
    UnitMaster,
    HSNMaster,
    GroupMaster,
    RackMaster,
    OrganizationModel
};
use Illuminate\Validation\Rule;

class ItemController extends Controller
{

    protected $service;

    public function __construct()
    {
        $this->service = new ItemServices();
    }

    public function index()
    {
        try {
            $getOutput = $this->service->getAll();
            return view('organizations.purchase.part-item.list-part-item', compact('getOutput'));
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function add()
    {
        $dataOutputUnitMaster = UnitMaster::where('is_active', true)->get();
        $dataOutputHSNMaster = HSNMaster::where('is_active', true)->get();
        $dataOutputGroupMaster = GroupMaster::where('is_active', true)->get();
        $dataRackMaster = RackMaster::where('is_active', true)->get();
        return view('organizations.purchase.part-item.add-part-item', compact(
            'dataOutputUnitMaster',
            'dataOutputHSNMaster',
            'dataOutputGroupMaster',
            'dataRackMaster'
        ));
    }

    public function store(Request $request)
    {
        $rules = [
            'part_number' => 'required|max:255',
            'description' => 'required|unique:tbl_part_item|max:255',
            'unit_id' => 'required',
            'hsn_id' => 'required',
            'group_type_id' => 'required',
            // 'rack_id' => 'required',
            'basic_rate' => 'required|numeric|min:0',
            'opening_stock' => 'required|numeric|min:0',
        ];

        $messages = [
            'part_number.required' => 'Please enter the part number.',
            'description.required' => 'Please enter a description.',
            'description.unique' => 'This description already exists.',
            'description.max' => 'This description  max 255.',
            'unit_id.required' => 'Please select a unit.',
            'hsn_id.required' => 'Please select an HSN.',
            'group_type_id.required' => 'Please select a group type.',
            // 'rack_id.required' => 'Please select a rack number.',
            'basic_rate.required' => 'Please enter the basic rate.',
            'basic_rate.numeric' => 'The basic rate must be a number.',
            'basic_rate.min' => 'The basic rate cannot be negative.',
            'opening_stock.required' => 'Please enter the opening stock.',
            'opening_stock.numeric' => 'The opening stock must be a number.',
            'opening_stock.min' => 'The opening stock cannot be negative.',
        ];

        try {
            $validation = Validator::make($request->all(), $rules, $messages);

            if ($validation->fails()) {
                return redirect('purchase/add-part-item')
                    ->withInput()
                    ->withErrors($validation);
            } else {
                $add_record = $this->service->addAll($request);

                if ($add_record) {
                    $msg = $add_record['msg'];
                    $status = $add_record['status'];

                    if ($status == 'success') {
                        return redirect('purchase/list-part-item')->with(compact('msg', 'status'));
                    } else {
                        return redirect('purchase/add-part-item')->withInput()->with(compact('msg', 'status'));
                    }
                }
            }
        } catch (Exception $e) {
            return redirect('purchase/add-part-item')->withInput()->with(['msg' => $e->getMessage(), 'status' => 'error']);
        }
    }

    public function edit(Request $request)
    {
        // $edit_data_id = base64_decode( $request->id );
        $edit_data_id = base64_decode($request->id);

        $editData = $this->service->getById($edit_data_id);
        $data = OrganizationModel::orderby('updated_at', 'desc')->get();
        $dataOutputUnitMaster = UnitMaster::where('is_active', true)->get();
        $dataOutputHSNMaster = HSNMaster::where('is_active', true)->get();
        $dataOutputGroupMaster = GroupMaster::where('is_active', true)->get();
        $dataRackMaster = RackMaster::where('is_active', true)->get();
        return view('organizations.purchase.part-item.edit-part-item', compact('editData', 'data', 'dataOutputUnitMaster', 'dataOutputHSNMaster', 'dataOutputGroupMaster', 'dataRackMaster'));
    }

    public function update(Request $request)
    {
        // $id = $request->edit_id;
        $id = $request->input('id');
        $rules = [
            // 'part_number' => 'required|max:255',
            // 'description' => [
            //     'required',
            //     'max:255',
            //     Rule::unique( 'tbl_part_item' )->ignore( $id )
            // ],
            // 'unit_id' => 'required',
            // 'hsn_id' => 'required',
            // 'group_type_id' => 'required',
            // 'basic_rate' => 'required|numeric|min:0',
            // 'opening_stock' => 'required|numeric|min:0',
        ];
        $messages = [
            // 'part_number.required' => 'Please enter the part number.',
            // 'description.required' => 'Please enter a description.',
            // 'description.unique' => 'This description already exists.',
            // 'description.max' => 'This description max 255.',
            // 'unit_id.required' => 'Please select a unit.',
            // 'hsn_id.required' => 'Please select an HSN.',
            // 'group_type_id.required' => 'Please select a group type.',
            // 'basic_rate.required' => 'Please enter the basic rate.',
            // 'basic_rate.numeric' => 'The basic rate must be a number.',
            // 'basic_rate.min' => 'The basic rate cannot be negative.',
            // 'opening_stock.required' => 'Please enter the opening stock.',
            // 'opening_stock.numeric' => 'The opening stock must be a number.',
            // 'opening_stock.min' => 'The opening stock cannot be negative.',
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
                        return redirect('purchase/list-part-item')->with(compact('msg', 'status'));
                    } else {
                        return redirect()->back()
                            ->withInput()
                            ->with(compact('msg', 'status'));
                    }
                }
            }
        } catch (\Exception $e) {
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
                    return redirect('purchase/list-part-item')->with(compact('msg', 'status'));
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
