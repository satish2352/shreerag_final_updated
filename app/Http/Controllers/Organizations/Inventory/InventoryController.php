<?php

namespace App\Http\Controllers\Organizations\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Organizations\Inventory\InventoryServices;
use Session;
use Validator;
use Config;
use Carbon;
use App\Models\ {
    UnitMaster,
    HSNMaster,
    GroupMaster,
    OrganizationModel,
    PartItem

}
;
use Illuminate\Validation\Rule;

class InventoryController extends Controller
 {

    public function __construct() {
        $this->service = new InventoryServices();
    }
    public function getMaterialList() {
        try {
            $getOutput = $this->service->getAll();
            return view( 'organizations.inventory.list-part-item', compact( 'getOutput' ) );
        } catch ( \Exception $e ) {
            return $e;
        }
    }
    public function add() {
        $dataOutputPartItem = PartItem::where( 'is_active', true )->get();
        return view( 'organizations.inventory.add-stock', compact(
            'dataOutputPartItem'
        ) );
    }
    public function store( Request $request ) {
        $rules = [
            // 'name' => 'required|max:255',

        ];

        $messages = [
            // 'name.required' => 'Please enter the department name.',
            // // 'name.unique' => 'Part Name already exist.',
            // 'name.max' => 'The name must not exceed 255 characters.',
        ];

        try {
            $validation = Validator::make( $request->all(), $rules, $messages );

            if ( $validation->fails() ) {
                return redirect( 'add-product-stock' )
                ->withInput()
                ->withErrors( $validation );
            } else {
                $add_record = $this->service->addAll( $request );
                if ( $add_record ) {
                    $msg = $add_record[ 'msg' ];
                    $status = $add_record[ 'status' ];

                    if ( $status == 'success' ) {
                        return redirect( 'storedept/list-inventory-material' )->with( compact( 'msg', 'status' ) );
                    } else {
                        return redirect( 'storedept/add-product-stock' )->withInput()->with( compact( 'msg', 'status' ) );
                    }
                }
            }
        } catch ( Exception $e ) {
            return redirect( 'storedept/add-product-stock' )->withInput()->with( [ 'msg' => $e->getMessage(), 'status' => 'error' ] );
        }
    }
    public function edit( Request $request ) {
        $edit_data_id = base64_decode( $request->id );
        $editData = $this->service->getById( $edit_data_id );
        $data = OrganizationModel::orderby( 'updated_at', 'desc' )->get();
        $dataOutputPartItem = PartItem::where( 'is_active', true )->get();
        $dataOutputGroupMaster = GroupMaster::where( 'is_active', true )->get();
        return view( 'organizations.inventory.edit-stock', compact( 'editData', 'data', 'dataOutputPartItem' ) );
    }
    public function update( Request $request ) {
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
                        return redirect( 'storedept/list-inventory-material' )->with( compact( 'msg', 'status' ) );
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
                    return redirect( 'purchase/list-part-item' )->with( compact( 'msg', 'status' ) );
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
