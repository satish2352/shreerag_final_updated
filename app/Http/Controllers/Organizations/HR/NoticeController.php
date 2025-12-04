<?php

namespace App\Http\Controllers\Organizations\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Organizations\HR\NoticeServices;
use Illuminate\Support\Facades\Validator;
use Exception;
use App\Models\{
    DepartmentsModel
};

class NoticeController extends Controller
{
    protected $service;

    public function __construct()
    {
        $this->service = new NoticeServices();
    }
    public function index()
    {
        try {
            $getOutput = $this->service->getAll();
            return view('organizations.hr.notice.list-notice', compact('getOutput'));
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function add()
    {
        $departments = DepartmentsModel::where('is_active', true)->orderBy('department_name', 'asc')->get();
        return view('organizations.hr.notice.add-notice', compact('departments'));
    }

    public function store(Request $request)
    {
        $rules = ['title' => 'required'];
        $messages = ['title.required' => 'Please enter a title.'];

        $validation = Validator::make($request->all(), $rules, $messages);
        if ($validation->fails()) {
            return redirect('hr/add-notice')->withInput()->withErrors($validation);
        }

        $departments = in_array('all', $request->department_id)
            ? DepartmentsModel::where('is_active', true)->pluck('id')->toArray()
            : $request->department_id;

        $add_record = $this->service->addAll($request, $departments);

        return $add_record['status'] === 'success'
            ? redirect('hr/list-notice')->with(['msg' => $add_record['msg'], 'status' => 'success'])
            : redirect('hr/add-notice')->withInput()->with(['msg' => $add_record['msg'], 'status' => 'error']);
    }

    // public function add() {
    //     // Fetch all active departments
    //     $departments = DepartmentsModel::where('is_active', true)->orderBy('department_name', 'asc')->get();

    //     return view('organizations.hr.notice.add-notice', compact('departments'));
    // }

    // public function store(Request $request) {
    //     $rules = [
    //         'title' => 'required',
    //     ];
    //     $messages = [    
    //         'title.required' => 'Please enter a title.',
    //     ];

    //     try {
    //         $validation = Validator::make($request->all(), $rules, $messages);

    //         if ($validation->fails()) {
    //             return redirect('hr/add-notice')->withInput()->withErrors($validation);
    //         }

    //                     // If "All Departments" is selected, get all active department IDs
    //         $departments = $request->department_id === 'all'
    //         ? DepartmentsModel::where('is_active', true)->pluck('id')->toArray()
    //         : [$request->department_id];

    //         // Pass all selected departments to the service
    //         $add_record = $this->service->addAll($request, $departments);


    //         if ($add_record['status'] == 'success') {
    //             return redirect('hr/list-notice')->with(['msg' => $add_record['msg'], 'status' => 'success']);
    //         }

    //         return redirect('hr/add-notice')->withInput()->with(['msg' => $add_record['msg'], 'status' => 'error']);

    //     } catch (Exception $e) {
    //         return redirect('hr/add-notice')->withInput()->with(['msg' => $e->getMessage(), 'status' => 'error']);
    //     }
    // }

    // public function add(){
    //     $dept=DepartmentsModel::where('is_active', true)->get();
    //     return view('organizations.hr.notice.add-notice',compact('dept'));
    // }
    // public function store(Request $request){
    //     $rules = [
    //         'title' => 'required',
    //         // 'image' => 'required|image|mimes:jpeg,png,jpg|max:'.Config::get("AllFileValidation.NOTICE_IMAGE_MAX_SIZE").'|min:'.Config::get("AllFileValidation.NOTICE_IMAGE_MIN_SIZE").'',

    //     ];
    //     $messages = [    
    //         'title.required'=>'Please enter title.',
    //         // 'image.required' => 'The image is required.',
    //         // 'image.image' => 'The image must be a valid image file.',
    //         // 'image.mimes' => 'The image must be in JPEG, PNG, JPG format.',
    //         // 'image.max' => 'The image size must not exceed '.Config::get("AllFileValidation.NOTICE_IMAGE_MAX_SIZE").'KB .',
    //         // 'image.min' => 'The image size must not be less than '.Config::get("AllFileValidation.NOTICE_IMAGE_MIN_SIZE").'KB .',
    //         // 'image.dimensions' => 'The image dimensions must be between 200X200 and 1000x1000 pixels.',
    //     ];

    //     try {
    //         $validation = Validator::make($request->all(), $rules, $messages);

    //         if ($validation->fails()) {
    //             return redirect('hr/add-notice')
    //                 ->withInput()
    //                 ->withErrors($validation);
    //         } else {
    //             $add_record = $this->service->addAll($request);

    //             if ($add_record) {
    //                 $msg = $add_record['msg'];
    //                 $status = $add_record['status'];

    //                 if ($status == 'success') {
    //                     return redirect('hr/list-notice')->with(compact('msg', 'status'));
    //                 } else {
    //                     return redirect('hr/add-notice')->withInput()->with(compact('msg', 'status'));
    //                 }
    //             }
    //         }
    //     } catch (Exception $e) {
    //         return redirect('add-notice')->withInput()->with(['msg' => $e->getMessage(), 'status' => 'error']);
    //     }
    // }
    public function show(Request $request)
    {
        try {
            $showData = $this->service->getById($request->show_id);
            return view('organizations.hr.notice.show-notice', compact('showData'));
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function departmentWiseNotice(Request $request)
    {
        try {
            $showData = $this->service->departmentWiseNotice();

            return view('organizations.hr.notice.particular-notice-department-wise', compact('showData'));
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function edit(Request $request)
    {
    
        $edit_data_id = base64_decode($request->id);

        $editData = $this->service->getById($edit_data_id);

        $dept = DepartmentsModel::where('is_active', true)->get();
        return view('organizations.hr.notice.edit-notice', compact('editData', 'dept'));
    }
    public function update(Request $request)
    {
        $rules = [
            'title' => 'required',

        ];

        if ($request->has('image')) {
            // $rules['image'] = 'required|image|mimes:jpeg,png,jpg|max:'.Config::get("AllFileValidation.NOTICE_IMAGE_MAX_SIZE").'|min:'.Config::get("AllFileValidation.NOTICE_IMAGE_MIN_SIZE");
        }

        $messages = [
            'title.required' => 'Please enter Title.',
            // 'image.required' => 'The image is required.',
            // 'image.image' => 'The image must be a valid image file.',
            // 'image.mimes' => 'The image must be in JPEG, PNG, JPG format.',
            // 'image.max' => 'The image size must not exceed '.Config::get("AllFileValidation.NOTICE_IMAGE_MAX_SIZE").'KB .',
            // 'image.min' => 'The image size must not be less than '.Config::get("AllFileValidation.NOTICE_IMAGE_MIN_SIZE").'KB .',
            // 'image.dimensions' => 'The image dimensions must be between 200X200 and 1000x1000 pixels.',

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
                        return redirect('hr/list-notice')->with(compact('msg', 'status'));
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
    public function updateOne(Request $request)
    {
        try {
            $active_id = $request->active_id;
            $result = $this->service->updateOne($active_id);
            return redirect('list-notice')->with('flash_message', 'Updated!');
        } catch (\Exception $e) {
            return $e;
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
                    return redirect('hr/list-notice')->with(compact('msg', 'status'));
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
