<?php
namespace App\Http\Repository\Admin\Departments;
use Illuminate\Database\QueryException;
use DB;
use Illuminate\Support\Carbon;
// use Session;
use App\Models\ {
DepartmentsModel
};
use Config;

class DepartmentsRepository  {


    public function getAll(){
        try {
          $data_output = DepartmentsModel::get();
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }


    public function addAll($request)
    {   
        try {

            $dataOutput = new DepartmentsModel();
            $dataOutput->department_name = $request->department_name;
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
            $dataOutputByid = DepartmentsModel::find($id);
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
            $return_data = array();
          

            $dataOutput = DepartmentsModel::find($request->id);

            if (!$dataOutput) {
                return [
                    'msg' => 'Update Data not found.',
                    'status' => 'error'
                ];
            }

           
            $dataOutput->department_name = $request->department_name;

            $dataOutput->save();
            $return_data['image'] = $previousEnglishImage;
            return  $return_data;
        
        } catch (\Exception $e) {
            return [
                'msg' => 'Failed to Update Data.',
                'status' => 'error',
                'error' => $e->getMessage()
            ];
        }
    }



    // public function updateOne($id){
    //     try {
    //         $updateOutput = DepartmentsModel::find($id); // Assuming $request directly contains the ID

    //         if ($updateOutput) {
    //             $active =  $updateOutput->is_active;
    //             if($active == '1') {
    //                 DepartmentsModel::where('id',$id)
    //                 ->update([
    //                     'is_active' => '0' 
    //                 ]); 
    //             } else {
    //                 DepartmentsModel::where('id',$id)
    //                 ->update([
    //                     'is_active' => '1'
    //                 ]); 
    //             }

    //             return [
    //                 'msg' => 'Slide updated successfully.',
    //                 'status' => 'success'
    //             ];
                   
    //         } else {
    //             return [
    //                 'msg' => 'Data not found.',
    //                 'status' => 'error'
    //             ];
    //         }

          
    //     } catch (\Exception $e) {
    //         return [
    //             'msg' => 'Failed to Update Data.',
    //             'status' => 'error'
    //         ];
    //     }
    // }
    public function deleteById($id){
            try {
                $deleteDataById = DepartmentsModel::find($id);
                // dd($deleteDataById);

                    $deleteDataById->delete();
                    return $deleteDataById;
            
            } catch (\Exception $e) {
                return $e;
            }    }

}