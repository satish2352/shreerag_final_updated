<?php

namespace App\Http\Controllers\Organizations\HR\LeaveManagment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Organizations\HR\LeaveManagment\LeaveManagmentServices;
use Illuminate\Validation\Rule;
use Session;
use Validator;
use Config;
use Carbon;
use App\Models\DepartmentsModel;
use App\Models\
 {
    RolesModel, EmployeesModel, LeaveManagement
}
;

class LeaveManagmentController extends Controller
 {

    public function __construct() {
        $this->service = new LeaveManagmentServices();
    }

    public function index() {
        try {
            $getOutput = $this->service->getAll();
            return view( 'organizations.hr.yearly-leave-management.list-yearly-leave-management', compact( 'getOutput' ) );
        } catch ( \Exception $e ) {
            return $e;
        }
    }

    public function add() {
        $dept = DepartmentsModel::get();
        $roles = RolesModel::get();
        return view( 'organizations.hr.yearly-leave-management.add-yearly-leave-management', compact( 'dept', 'roles' ) );
    }

    public function store( Request $request ) {
        $rules = [
            'name' => 'required|unique:tbl_leave_management|regex:/^[a-zA-Z\s]+$/u|max:255',
            'leave_year' => 'required',
            'leave_count' => 'required|integer',

        ];

        $messages = [
            'name.required' => 'Please enter name.',
            'name.regex' => 'Please  enter text only.',
            'name.max' => 'Please  enter text length upto 255 character only.',
            'name.unique' => 'Name already exist.',
            'leave_year.required' => 'Please select year.',
            'leave_count.required' => 'Please enter leave count.',
            'leave_count.integer' => 'Please enter a valid number for leave count.',

        ];

        try {
            $validation = Validator::make( $request->all(), $rules, $messages );

            if ( $validation->fails() ) {
                return redirect( 'hr/add-yearly-leave-management' )
                ->withInput()
                ->withErrors( $validation );
            } else {
                $add_record = $this->service->addAll( $request );

                if ( $add_record ) {
                    $msg = $add_record[ 'msg' ];
                    $status = $add_record[ 'status' ];

                    if ( $status == 'success' ) {
                        return redirect( 'hr/list-yearly-leave-management' )->with( compact( 'msg', 'status' ) );
                    } else {
                        return redirect( 'hr/add-yearly-leave-management' )->withInput()->with( compact( 'msg', 'status' ) );
                    }
                }
            }
        } catch ( Exception $e ) {
            return redirect( 'hr/add-yearly-leave-management' )->withInput()->with( [ 'msg' => $e->getMessage(), 'status' => 'error' ] );
        }
    }

    public function edit( Request $request ) {
        $edit_data_id = base64_decode( $request->id );
        $editData = $this->service->getById( $edit_data_id );
        return view( 'organizations.hr.yearly-leave-management.edit-yearly-leave-management', compact( 'editData' ) );
    }

    public function update( Request $request ) {
        $id = $request->id;

        $rules = [
            'name' => [ 'required', 'max:255', 'regex:/^[a-zA-Z\s]+$/u', Rule::unique( 'tbl_leave_management', 'name' )->ignore( $id, 'id' ) ],
            'leave_year' => 'required',
            'leave_count' => 'required',

        ];

        $messages = [
            'name.required' => 'Please enter name.',
            'name.regex' => 'Please  enter text only.',
            'name.max' => 'Please  enter text length upto 255 character only.',
            'name.unique' => 'Name already exist.',
            'leave_year.required' => 'Please select year.',
            'leave_count.required' => 'Please enter leave count.',

        ];

        try {
            $validation = Validator::make( $request->all(), $rules, $messages );
            if ( $validation->fails() ) {
                return redirect()->back()
                ->withInput()
                ->withErrors( $validation );
            } else {
                $update_data = $this->service->updateAll( $request );

                if ( $update_data ) {
                    $msg = $update_data[ 'msg' ];
                    $status = $update_data[ 'status' ];
                    if ( $status == 'success' ) {
                        return redirect( 'hr/list-yearly-leave-management' )->with( compact( 'msg', 'status' ) );
                    } else {
                        return redirect()->back()
                        ->withInput()
                        ->with( compact( 'msg', 'status' ) );
                    }
                }
            }
        } catch ( Exception $e ) {
            return redirect()->back()
            ->withInput()
            ->with( [ 'msg' => $e->getMessage(), 'status' => 'error' ] );
        }
    }

    public function updateOne( Request $request ) {
        try {
            $active_id = $request->active_id;
            $result = $this->service->updateOne( $active_id );
            return redirect( 'hr/list-yearly-leave-management' )->with( 'flash_message', 'Updated!' );

        } catch ( \Exception $e ) {
            return $e;
        }
    }

    public function destroy( Request $request )
 {
        $delete_data_year = base64_decode( $request->id );

        try {
            $delete_record = $this->service->deleteByYear( $delete_data_year );

            if ( $delete_record ) {
                $msg = $delete_record[ 'msg' ];
                $status = $delete_record[ 'status' ];
                if ( $status == 'success' ) {
                    return redirect( 'hr/list-yearly-leave-management' )->with( compact( 'msg', 'status' ) );
                } else {
                    return redirect()->back()
                    ->withInput()
                    ->with( compact( 'msg', 'status' ) );
                }
            }
        } catch ( \Exception $e ) {
            return redirect()->back()->with( [ 'status' => 'error', 'msg' => $e->getMessage() ] );
        }
    }

}
