<?php
namespace App\Http\Repository\Organizations\Store;
use Illuminate\Database\QueryException;
use DB;
use Illuminate\Support\Carbon;
use App\Models\ {
PurchaseModel
};
use Config;

class PurchaseRepository  {


    public function getAll(){
        try {
            $data_output= PurchaseModel::get();
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }


public function addAll($request)
{
    try {
        $dataOutput = new PurchaseModel();
        $dataOutput->name = $request->name;
        $dataOutput->email = $request->email;
        $dataOutput->price = $request->price;
        $dataOutput->contact=$request->contact;
        $dataOutput->save();

        return [
            'msg' => 'Data Added Successfully',
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
            $dataOutputByid = PurchaseModel::find($id);
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

        public function updateAll($request){
        try { 
            $dataOutput = PurchaseModel::find($request->id);
            // dd($dataOutput);

            if (!$dataOutput) {
                return [
                    'msg' => 'Update Data not found.',
                    'status' => 'error'
                ];
            }

            $dataOutput->name = $request->name;
            $dataOutput->email = $request->email;
            $dataOutput->price = $request->price;
            $dataOutput->contact=$request->contact;
            

            $dataOutput->save();

            return [
            'msg' => 'Data Added Successfully',
            'status' => 'success'
        ];
        
        } catch (\Exception $e) {
            return [
                'msg' => 'Failed to Update Data.',
                'status' => 'error',
                'error' => $e->getMessage() // Return the error message for debugging purposes
            ];
        }
    }


    public function deleteById($id){
            try {
                $deleteDataById = PurchaseModel::find($id);
                
                if ($deleteDataById) {
                    $deleteDataById->delete();
                    return $deleteDataById;
                } else {
                    return null;
                }
            } catch (\Exception $e) {
                return $e;
            }
    }

}