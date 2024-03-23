<?php
namespace App\Http\Repository\Admin\Roles;
use Illuminate\Database\QueryException;
use DB;
use Illuminate\Support\Carbon;
use App\Models\ {
RolesModel
};
use Config;

class RolesRepository  {


    public function getAll(){
        try {
          $data_output = RolesModel::get();
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }


    public function addAll($request)
    {   
        try {

            $dataOutput = new RolesModel();
            $dataOutput->role_name = $request->role_name;
            $dataOutput->save();

            return [
                'status' => 'success'
            ];

        } catch (\Exception $e) {
            return [
                'msg' => $e->getMessage(),
                'status' => 'error'
            ];
        }
    }

    public function getById($id){
    try {
            $dataOutputByid = RolesModel::find($id);
            if ($dataOutputByid) {
                return $dataOutputByid;
            } else {
                return null;
            }
        } catch (\Exception $e) {
            return [
                'msg' => $e,
                'status' => 'error'
            ];
        }
    }

      public function updateAll($request)
{
    try {
        $return_data = array();

        $dataOutput = RolesModel::find($request->id);

        if (!$dataOutput) {
            return [
                'msg' => 'Update Data not found.',
                'status' => 'error'
            ];
        }

        $dataOutput->role_name = $request->role_name;
        $dataOutput->save();
        $return_data['data'] = $dataOutput;
        $return_data['status'] = 'success';

        return $return_data;
    } catch (\Exception $e) {
        return [
            'msg' => 'Failed to Update Data.',
            'status' => 'error',
            'error' => $e->getMessage()
        ];
    }
}



    public function deleteById($id){
            try {
                $deleteDataById = RolesModel::find($id);
                // dd($deleteDataById);

                    $deleteDataById->delete();
                    return $deleteDataById;
            
            } catch (\Exception $e) {
                return $e;
            }    }

}