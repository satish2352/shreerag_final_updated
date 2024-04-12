<?php
namespace App\Http\Repository\Organizations\Employees;
use Illuminate\Database\QueryException;
use DB;
use Illuminate\Support\Carbon;
use App\Models\ {
EmployeesModel,User
};
use Config;

class EmployeesRepository  {


    public function getAll(){
        try {
            $data_output= EmployeesModel::with(['role', 'department'])
                        ->where('organization_id', session()->get('org_id'))
                        ->orderBy('updated_at', 'desc');
                        
            if (session()->get('role_id') ==  config('constants.ROLE_ID.HIGHER_AUTHORITY')) {
                $data_output = $data_output->where('tbl_employees.role_id', '<>',config('constants.ROLE_ID.HIGHER_AUTHORITY'));
            } 
            $data_output = $data_output->get();
            
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }


    public function addAll($request)
    {   
        try {
            // dd($request->session()->get('org_id'));

           //insert data to users table
            $userData=new User();
            $userData->u_email= $request->email;
            $userData->u_email= $request->email;
            $userData->role_id=$request->role_id;
            $userData->org_id = $request->session()->get('org_id');
            $userData->u_password= bcrypt($request->password);
            $userData->save();


            $dataOutput = new EmployeesModel();
            $dataOutput->employee_name = $request->employee_name;
            $dataOutput->email = $request->email;
            $dataOutput->mobile_number = $request->mobile_number;
            $dataOutput->role_id=$request->role_id;
            $dataOutput->department_id = $request->department_id;
            $dataOutput->address = $request->address;
            $dataOutput->emp_image = 'null';
            $dataOutput->organization_id = $request->session()->get('org_id');
            $dataOutput->save();
            $last_insert_id = $dataOutput->id;
            $imageName = $last_insert_id . '_' . rand(100000, 999999) . '_image.' . $request->image->getClientOriginalExtension();

            $finalOutput = EmployeesModel::find($last_insert_id);
            $finalOutput->emp_image = $imageName;
            $finalOutput->save();

            return [
                'ImageName' => $imageName,
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
            $dataOutputByid = EmployeesModel::find($id);
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
            // dd($request->session()->get('org_id'));
            $return_data = array();
            $userData = User::where('u_email', $request->email)->first();
            $userData->u_email= $request->email;
            $userData->role_id=$request->role_id;
            // $dataOutput->org_id =session()->get('org_id');

            $userData->u_password= bcrypt($request->password);
            $userData->save();

            $dataOutput = EmployeesModel::find($request->id);

            if (!$dataOutput) {
                return [
                    'msg' => 'Update Data not found.',
                    'status' => 'error'
                ];
            }

            $previousEnglishImage = $dataOutput->image;
            $dataOutput->employee_name = $request->employee_name;
            $dataOutput->email = $request->email;
            $dataOutput->department_id = $request->department_id;
            $dataOutput->role_id=$request->role_id;
            $dataOutput->mobile_number = $request->mobile_number;
            $dataOutput->address = $request->address;
            $dataOutput->emp_image = 'null';
            $dataOutput->organization_id = $request->session()->get('org_id');

            $dataOutput->save();
            $last_insert_id = $dataOutput->id;

            $return_data['last_insert_id'] = $last_insert_id;
            $return_data['image'] = $previousEnglishImage;
            return  $return_data;
        
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
                $deleteDataById = EmployeesModel::find($id);
                $userData = User::where('u_email', $deleteDataById->email)->first();
                $userData->delete();
                if ($deleteDataById) {
                    if (file_exists_view(Config::get('DocumentConstant.EMPLOYEES_DELETE') . $deleteDataById->emp_image)){
                        removeImage(Config::get('DocumentConstant.EMPLOYEES_DELETE') . $deleteDataById->emp_image);
                    }
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