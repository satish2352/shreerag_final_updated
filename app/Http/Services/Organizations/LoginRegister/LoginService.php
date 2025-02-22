<?php
namespace App\Http\Services\Organizations\LoginRegister;

use Illuminate\Http\Request;
use App\Http\Repository\Organizations\LoginRegister\LoginRepository;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Session;
use App\Models\DepartmentsModel;
use App\Models\RolesModel;
class LoginService 
{
	protected $repo;
	public function __construct()
    {        
        $this->repo = new LoginRepository();
    }

    public function checkLogin($request) {
        // Fetch user details from the repository
        $response = $this->repo->checkLogin($request);
    
        // Fetch the role of the user from the 'tbl_roles' table
        $roleName = RolesModel::join('users', 'users.role_id', '=', 'tbl_roles.id')
                    ->where('users.email', $request->email)
                    ->select('tbl_roles.role_name')
                    ->first();  // Use first() since you only expect one result
    
        // Check if the role was found
        $role = $roleName ? $roleName->role_name : '';
    
        // Check if user details exist in the response
        if ($response['user_details']) {
            $password = $request['password'];
    
            // Verify if the provided password matches the stored hashed password
            if (Hash::check($password, $response['user_details']['u_password'])) {
                // Store user session information
                $request->session()->put('org_id', $response['user_details']['org_id']);
                $request->session()->put('role_name', $role);
                $request->session()->put('u_email', $response['user_details']['u_email']);
                $request->session()->put('user_id', $response['user_details']['id']);  // Store user ID for session
    
                // Return success response with user details and role ID
                return [
                    'status' => 'success',
                    'msg' => $response['user_details'],
                    'role_id' => $response['user_details']['role_id']
                ];
            } else {
                // Password mismatch error
                return [
                    'status' => 'failed',
                    'msg' => 'These credentials do not match our records.'
                ];
            }
        } else {
            // User not found error
            return [
                'status' => 'failed',
                'msg' => 'These credentials do not match our records.'
            ];
        }
    }
    
}