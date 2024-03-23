<?php
namespace App\Http\Services\Organizations\LoginRegister;

use Illuminate\Http\Request;
use App\Http\Repository\Organizations\LoginRegister\LoginRepository;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Session;
use App\Models\DepartmentsModel;
class LoginService 
{
	protected $repo;
	public function __construct()
    {        
        $this->repo = new LoginRepository();
    }

    public function checkLogin($request) {
        $response = $this->repo->checkLogin($request);
        // dd($response);
        $roleName = DepartmentsModel::join('tbl_employees', 'tbl_employees.department_id', '=', 'tbl_departments.id')
                ->where('tbl_employees.email',$request->email )
                ->select('tbl_departments.department_name')
                ->get();    
        $role="";
        foreach($roleName as $name)
        {
            $role=$name->department_name;
        }
        // dd($role);
            if($response['user_details']) {
            $password = $request['password'];
            if (Hash::check($password, $response['user_details']['u_password'])) {
                $request->session()->put('org_id',$response['user_details']['org_id']);
                $request->session()->put('role_name',$role);
                $request->session()->put('u_email',$response['user_details']['u_email']);
                $json = ['status'=>'success','msg'=>$response['user_details'],'role_id'=>$response['user_details']['role_id']];
            } else {
                $json = ['status'=>'failed','msg'=>'These credentials do not match our records.'];
            }
            
        } else {
            $json = ['status'=>'failed','msg'=>'These credentials do not match our records.'];
        }
        return $json;
    }
}