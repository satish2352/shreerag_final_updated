<?php
namespace App\Http\Repository\Admin\CMS;
use Illuminate\Database\QueryException;
use DB;
use Illuminate\Support\Carbon;
// use Session;
use App\Models\ {
    Products
}
;
use Config;

class ProductRepository {

    public function getAll() {
        try {
            $data_output = Products::orderBy( 'updated_at', 'desc' )->get();
            return $data_output;
        } catch ( \Exception $e ) {
            return $e;
        }
    }

    public function addAll( $request ) {
        try {
            $data = array();
            $dataOutput = new Products();
            $dataOutput->title = $request[ 'title' ];
            $dataOutput->description = $request[ 'description' ];
            $dataOutput->save();

            $last_insert_id = $dataOutput->id;

            $ImageName = $last_insert_id .'_' . rand( 100000, 999999 ) . '_image.' . $request->image->extension();

            $finalOutput = Products::find( $last_insert_id );
            // Assuming $request directly contains the ID
            $finalOutput->image = $ImageName;
            // Save the image filename to the database
            $finalOutput->save();

            $data[ 'ImageName' ] = $ImageName;
            return $data;

        } catch ( \Exception $e ) {
            return [
                'msg' => $e,
                'status' => 'error'
            ];
        }
    }

    public function getById( $id ) {
        try {
            $dataOutputByid = Products::find( $id );

            if ( $dataOutputByid ) {
                return $dataOutputByid;
            } else {
                return null;
            }
        } catch ( \Exception $e ) {
            return $e;
            return [
                'msg' => 'Failed to get by id Data.',
                'status' => 'error'
            ];
        }
    }

    public function updateAll( $request ) {
        try {
            $return_data = array();
            $dataOutput = Products::find( $request->id );

            if ( !$dataOutput ) {
                return [
                    'msg' => 'Update Data not found.',
                    'status' => 'error'
                ];
            }
            // Store the previous image names
            $previousEnglishImage = $dataOutput->image;

            // Update the fields from the request
            $dataOutput->title = $request[ 'title' ];
            $dataOutput->description = $request[ 'description' ];
            $dataOutput->save();
            $last_insert_id = $dataOutput->id;

            $return_data[ 'last_insert_id' ] = $last_insert_id;
            $return_data[ 'image' ] = $previousEnglishImage;
            return  $return_data;

        } catch ( \Exception $e ) {
            return [
                'msg' => 'Failed to Update Data.',
                'status' => 'error',
                'error' => $e->getMessage() // Return the error message for debugging purposes
            ];
        }
    }

    public function updateOne( $request ) {
        try {
            $updateOutput = Products::find( $request );
            // Assuming $request directly contains the ID

            // Assuming 'is_active' is a field in the model
            if ( $updateOutput ) {
                $is_active = $updateOutput->is_active === '1' ? '0' : '1';
                $updateOutput->is_active = $is_active;
                $updateOutput->save();

                return [
                    'msg' => 'Data Updated Successfully.',
                    'status' => 'success'
                ];
            }
            return [
                'msg' => 'Data not Found.',
                'status' => 'error'
            ];
        } catch ( \Exception $e ) {
            return [
                'msg' => 'Failed to Update Data.',
                'status' => 'error'
            ];
        }
    }

    public function deleteById( $id ) {
        try {
            $deleteDataById = Products::find( $id );

            if ( $deleteDataById ) {
                $deleteDataById->delete();
                return $deleteDataById;
            } else {
                return null;
            }
        } catch ( \Exception $e ) {
            return $e;
        }
    }

}