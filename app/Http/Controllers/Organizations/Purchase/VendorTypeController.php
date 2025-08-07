<?php

namespace App\Http\Controllers\Organizations\Purchase;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Organizations\Purchase\VendorTypeServices;
use Session;
use Validator;
use Config;
use Carbon;
use App\Models\OrganizationModel;

class VendorTypeController extends Controller
 {

    public function __construct() {
        $this->service = new VendorTypeServices();
    }

    public function index() {
        try {
            $getOutput = $this->service->getAll();
            return view( 'organizations.purchase.vendor-type.list-vendor-type', compact( 'getOutput' ) );
        } catch ( \Exception $e ) {
            return $e;
        }
    }

    public function add() {
        return view( 'organizations.purchase.vendor-type.add-vendor-type' );
    }

    public function store( Request $request ) {
        $rules = [
            'name' => 'required|unique:tbl_unit|string|max:255',

        ];

        $messages = [
            'name.required' => 'Please enter the department name.',
            'name.string' => 'The company name must be a valid string.',
            'name.max' => 'The company name must not exceed 255 characters.',
            'name.unique' => 'unit already exist.',
        ];

        try {
            $validation = Validator::make( $request->all(), $rules, $messages );

            if ( $validation->fails() ) {
                return redirect( 'add-vendor-type' )
                ->withInput()
                ->withErrors( $validation );
            } else {
                $add_record = $this->service->addAll( $request );
                if ( $add_record ) {
                    $msg = $add_record[ 'msg' ];
                    $status = $add_record[ 'status' ];

                    if ( $status == 'success' ) {
                        return redirect( 'purchase/list-vendor-type' )->with( compact( 'msg', 'status' ) );
                    } else {
                        return redirect( 'purchase/add-vendor-type' )->withInput()->with( compact( 'msg', 'status' ) );
                    }
                }
            }
        } catch ( Exception $e ) {
            return redirect( 'purchase/add-vendor-type' )->withInput()->with( [ 'msg' => $e->getMessage(), 'status' => 'error' ] );
        }
    }

    public function edit( Request $request ) {
        $edit_data_id = base64_decode( $request->id );
        $editData = $this->service->getById( $edit_data_id );
        $data = OrganizationModel::orderby( 'updated_at', 'desc' )->get();
        return view( 'organizations.purchase.vendor-type.edit-vendor-type', compact( 'editData', 'data' ) );
    }

    public function update( Request $request ) {
        $rules = [
            'name' => 'required|string|max:255',

        ];

        $messages = [
            'name.required' => 'Please enter the department name.',
            'name.string' => 'The company name must be a valid string.',
            'name.max' => 'The company name must not exceed 255 characters.',
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
                        return redirect( 'purchase/list-vendor-type' )->with( compact( 'msg', 'status' ) );
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

    public function destroy( Request $request ) {
        $delete_data_id = base64_decode( $request->id );
        try {
            $delete_record = $this->service->deleteById( $delete_data_id );
            if ( $delete_record ) {
                $msg = $delete_record[ 'msg' ];
                $status = $delete_record[ 'status' ];
                if ( $status == 'success' ) {
                    return redirect( 'purchase/list-vendor-type' )->with( compact( 'msg', 'status' ) );
                } else {
                    return redirect()->back()
                    ->withInput()
                    ->with( compact( 'msg', 'status' ) );
                }
            }
        } catch ( \Exception $e ) {
            return $e;
        }
    }

}
