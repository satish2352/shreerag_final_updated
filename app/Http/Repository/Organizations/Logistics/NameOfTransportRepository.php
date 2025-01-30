<?php
namespace App\Http\Repository\Organizations\Logistics;
use Illuminate\Database\QueryException;
use DB;
use Illuminate\Support\Carbon;
use App\Models\ {
    TransportName
}
;
use Config;

class NameOfTransportRepository {

    public function getAll() {
        try {
            $data_output = TransportName::get();
            return $data_output;
        } catch ( \Exception $e ) {
            return $e;
        }
    }

    public function addAll( $request )
 {

        try {

            $dataOutput = new TransportName();
            $dataOutput->name = $request->name;
            $dataOutput->save();

            return [
                'status' => 'success'
            ];

        } catch ( \Exception $e ) {
            return [
                'msg' => $e->getMessage(),
                'status' => 'error'
            ];
        }
    }

    public function getById( $id ) {
        try {
            $dataOutputByid = TransportName::find( $id );
            if ( $dataOutputByid ) {
                return $dataOutputByid;
            } else {
                return null;
            }
        } catch ( \Exception $e ) {
            return [
                'msg' => $e,
                'status' => 'error'
            ];
        }
    }

    public function updateAll( $request )
 {
        try {
            $return_data = array();

            $dataOutput = TransportName::find( $request->id );

            if ( !$dataOutput ) {
                return [
                    'msg' => 'Update Data not found.',
                    'status' => 'error'
                ];
            }

            $dataOutput->name = $request->name;
            $dataOutput->save();
            $return_data[ 'data' ] = $dataOutput;
            $return_data[ 'status' ] = 'success';

            return $return_data;
        } catch ( \Exception $e ) {
            return [
                'msg' => 'Failed to Update Data.',
                'status' => 'error',
                'error' => $e->getMessage()
            ];
        }
    }

    public function deleteById( $id ) {
        try {
            $deleteDataById = TransportName::find( $id );
            $deleteDataById->delete();
            return $deleteDataById;

        } catch ( \Exception $e ) {
            return $e;
        }
    }

}