<?php
namespace App\Http\Repository\Admin\Employees;
use Illuminate\Database\QueryException;
use DB;
use Illuminate\Support\Carbon;
// use Session;
use App\Models\ {
EmployeesModel,USer
};
use Config;

class EmployeesRepository  {


    public function getAll(){
        try {
            // dd(session()->get('role_id') );
          $data_output = EmployeesModel::select(
                'tbl_employees.id as emp_id',
                'tbl_employees.email as emp_email',
                'tbl_employees.mobile_number as emp_mobile_number',
                'tbl_employees.address as emp_address',
                'tbl_employees.*',
                'tbl_organizations.*'
            )
            ->leftJoin('tbl_organizations', 'tbl_employees.organization_id', '=', 'tbl_organizations.id')
            ->orderBy('tbl_employees.updated_at', 'desc');

           
            $data_output = $data_output->get();

            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }


    public function addAll($request)
    {   

        try {
            $userData=new User();
            $userData->u_email= $request->email;
            $userData->role_id= config('constants.ROLE_ID.HIGHER_AUTHORITY');
            $userData->org_id = $request->company_id;
            $userData->u_password= bcrypt($request->password);
            $userData->save();

            
            $dataOutput = new EmployeesModel();
            $dataOutput->employee_name = $request->employee_name;
            $dataOutput->email = $request->email;
            $dataOutput->mobile_number = $request->mobile_number;
            $dataOutput->address = $request->address;
            $dataOutput->emp_image = 'null';
            $dataOutput->organization_id = $request->company_id;
            $dataOutput->role_id= config('constants.ROLE_ID.HIGHER_AUTHORITY');
            $dataOutput->department_id= config('constants.ROLE_ID.HIGHER_AUTHORITY');
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

        } catch (QueryException $e) {
            dd($e);
            return [
                'msg' => $e->getMessage(),
                'status' => 'error'
            ];
        }
    }

    public function getById($emp_id){
    try {
            $dataOutputByid = EmployeesModel::find($emp_id);
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
            $userData = User::where('u_email', $request->email)->first();
            // dd($userData);
            $userData->org_id = $request->company_id;
            $userData->u_email= $request->email;
            $userData->role_id= "2";
            $userData->role_id= "2";
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
            $dataOutput->mobile_number = $request->mobile_number;
            $dataOutput->address = $request->address;
            $dataOutput->emp_image = 'null';
            $dataOutput->organization_id = $request->organization_id;

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

    public function deleteById($emp_id){
            try {
                $deleteDataById = EmployeesModel::find($emp_id);
                $userData = User::where('u_email', $deleteDataById->email)->first();
                $userData->delete();
                // dd($deleteDataById);

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