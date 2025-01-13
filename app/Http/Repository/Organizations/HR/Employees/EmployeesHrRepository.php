<?php
namespace App\Http\Repository\Organizations\HR\Employees;
use Illuminate\Database\QueryException;
use DB;
use Illuminate\Support\Carbon;
use App\Models\ {
HREmployee,User,EmployeesModel,RolesModel,FinancialYearLeaveRecord,LeaveManagement
};
use Config;

class EmployeesHrRepository  {


    public function getAll(){
        try {
            $data_output= EmployeesModel::with(['role', 'department'])
                        ->where('organization_id', session()->get('org_id'))
                        ->orderBy('updated_at', 'desc')
                        ->get();
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
	
    public function getUsersList() {
        $data_users = User::join('tbl_roles', function($join) {
							$join->on('users.role_id', '=', 'tbl_roles.id');
						})
						// ->where('users.is_active','=',true)
						->select('tbl_roles.role_name',
								'users.u_email',
								'users.f_name',
								'users.m_name',
								'users.l_name',
								'users.number',
								'users.designation',
								'users.address',
								'users.state',
								'users.city',
								'users.pincode',
								'users.id',
								'users.is_active'
							) ->orderBy('users.id', 'desc')->get();
							// ->toArray();

		return $data_users;
	}
	// public function getById($id)
	// {
	// 	try {
	// 		$data_users = User::join('tbl_roles', function($join) {
	// 			$join->on('users.role_id', '=', 'tbl_roles.id');
	// 		})
	// 		// ->where('users.is_active','=',true)
	// 		->select('tbl_roles.role_name',
	// 				'users.u_email',
	// 				'users.f_name',
	// 				'users.m_name',
	// 				'users.l_name',
	// 				'users.number',
	// 				'users.designation',
	// 				'users.address',
	// 				'users.state',
	// 				'users.city',
	// 				'users.pincode',
	// 				'users.id',
	// 				'users.is_active'
	// 			) ->orderBy('users.id', 'desc')->where('users.id', $id)->first();		
	// 		if ($data_users) {
	// 			return $user;
	// 		} else {
	// 			return null;
	// 		}
	// 	} catch (\Exception $e) {
	// 		return [
	// 			'msg' => $e->getMessage(),
	// 			'status' => 'error'
	// 		];
	// 	}
	// }
    public function checkDupCredentials($request){
		return User::where('u_email', '=', $request['u_email'])
			// ->orWhere('u_uname','=',$request['u_uname'])
			->select('id')->get();
	}

    // public function register($request){
	// 	$data =array();
	// 	// $ipAddress = getIPAddress($request);
	// 	$user_data = new User();
	// 	$user_data->u_email = $request['u_email'];
	// 	// $user_data->u_uname = $request['u_uname'];
	// 	$user_data->u_password = bcrypt($request['u_password']);
	// 	$user_data->role_id = $request['role_id'];
	// 	$user_data->f_name = $request['f_name'];
	// 	$user_data->m_name = $request['m_name'];
	// 	$user_data->l_name = $request['l_name'];
	// 	$user_data->number = $request['number'];
	// 	$user_data->designation = $request['designation'];
	// 	$user_data->address = $request['address'];
	// 	$user_data->state = $request['state'];
	// 	$user_data->city = $request['city'];
	// 	$user_data->pincode = $request['pincode'];
	// 	$user_data->ip_address = 'null';
    //     $user_data->org_id = $request->session()->get('org_id');
	// 	$user_data->is_active = isset($request['is_active']) ? true : false;
	// 	$user_data->save();

	// 	$financial_year_leave_record = new FinancialYearLeaveRecord();
	// 	$financial_year_leave_record->user_id = $user_data->id;
	// 	// Add other necessary fields to FinancialYearLeaveRecord
	// 	$financial_year_leave_record->save();

	// 	$last_insert_id = $user_data->id;
	// 	// $this->insertRolesPermissions($request, $last_insert_id);

	// 	// $imageProfile = $last_insert_id .'_' . rand(100000, 999999) . '_english.' . $request->user_profile->extension();
        
    //     $user_detail = User::find($last_insert_id);
    //     // $user_detail->user_profile = $imageProfile; 
    //     $user_detail->save();
    //     // $data['imageProfile'] =$imageProfile;
    //     return $data;

	// }

	public function register($request) {
		$data = array();
	
		// Create a new User instance and populate it with the request data
		$user_data = new User();
		$user_data->u_email = $request['u_email'];
		$user_data->u_password = bcrypt($request['u_password']);
		$user_data->role_id = $request['role_id'];
		// $user_data->department_id = $request['department_id'];
		$user_data->f_name = $request['f_name'];
		$user_data->m_name = $request['m_name'];
		$user_data->l_name = $request['l_name'];
		$user_data->number = $request['number'];
		$user_data->designation = $request['designation'];
		$user_data->address = $request['address'];
		$user_data->state = $request['state'];
		$user_data->city = $request['city'];
		$user_data->pincode = $request['pincode'];
		$user_data->ip_address = 'null';
		$user_data->org_id = $request->session()->get('org_id');
		$user_data->is_active = isset($request['is_active']) ? true : false;
		$user_data->save();
	
		// Get the last inserted user's ID
		$last_insert_id = $user_data->id;
	
		// Fetch data from LeaveManagement
		$leave_management_data = LeaveManagement::all();

	
		// Loop through each record in LeaveManagement and add it to FinancialYearLeaveRecord
		foreach ($leave_management_data as $leave) {
			$financial_year_leave_record = new FinancialYearLeaveRecord();
			$financial_year_leave_record->user_id = $last_insert_id;
			$financial_year_leave_record->leave_management_id = $leave->id;
			$financial_year_leave_record->leave_type_name = $leave->name;
			$financial_year_leave_record->leave_balance = $leave->leave_count;
			// Add other necessary fields from LeaveManagement to FinancialYearLeaveRecord
			$financial_year_leave_record->save();
		}
	
		// Update user details if necessary
		$user_detail = User::find($last_insert_id);
		// If you have an image profile or other updates, do them here
		// $user_detail->user_profile = $imageProfile; 
		$user_detail->save();

		// Return the data array
		  // Return the last inserted ID
        return $last_insert_id;
	}
	

    public function editUsers($reuest){

		$data_users = [];

		$data_users['roles'] = RolesModel::where('is_active', true)
			->select('id', 'role_name')
			->get()
			->toArray();
		// $data_users['permissions'] = Permissions::where('is_active', true)
		// 	->select('id', 'route_name', 'permission_name', 'url')
		// 	->get()
		// 	->toArray();

			$data_users_data = User::join('tbl_roles', function ($join) {
				$join->on('users.role_id', '=', 'tbl_roles.id');
			})
			// ->join('roles_permissions', function($join) {
			// 	$join->on('users.id', '=', 'roles_permissions.user_id');
			// })
			->where('users.id', '=', base64_decode($reuest->edit_id))
			// ->where('roles_permissions.is_active','=',true)
			// ->where('users.is_active','=',true)
			->select(
				'tbl_roles.id as role_id',
				// 'users.u_uname',
				'users.u_password',
				'users.u_email',
				'users.f_name',
				'users.m_name',
				'users.l_name',
				'users.number',
				'users.designation',
				'users.address',
				'users.state',
				'users.city',
				'users.pincode',
				'users.id',
				'users.is_active',
			)->get()
			->toArray();

	      $data_users_data = User::join('tbl_roles', function($join) {
						$join->on('users.role_id', '=', 'tbl_roles.id');
					})
					// ->join('roles_permissions', function($join) {
					// 	$join->on('users.id', '=', 'roles_permissions.user_id');
					// })
					->where('users.id','=',base64_decode($reuest->edit_id))
					// ->where('roles_permissions.is_active','=',true)
					// ->where('users.is_active','=',true)
					->select('tbl_roles.id as role_id',
							// 'users.u_uname',
							'users.u_password',
							'users.u_email',
							'users.f_name',
							'users.m_name',
							'users.l_name',
							'users.number',
							'users.designation',
							'users.address',
							'users.state',
							'users.city',
							'users.pincode',
							'users.id',
							'users.is_active',
						)->get()
						->toArray();
						
		$data_users['data_users'] = $data_users_data[0];
		

		return $data_users;
	}

    public function update($request){
        // $ipAddress = getIPAddress($request);
		// $user_data = User::where('id',$request['edit_id']) 
		// 				->update([
		// 					// 'u_uname' => $request['u_uname'],
		// 					// 'role_id' => $request['role_id'],
		// 					'f_name' => $request['f_name'],
		// 					'm_name' => $request['m_name'],
		// 					'l_name' => $request['l_name'],
		// 					'number' => $request['number'],
		// 					'designation' => $request['designation'],
		// 					'address' => $request['address'],
		// 					'state' => $request['state'],
		// 					'city' => $request['city'],
		// 					'pincode' => $request['pincode'],
		// 					'is_active' => isset($request['is_active']) ? true :false,
		// 				]);
		$updateData = [
			'f_name' => $request['f_name'],
			'm_name' => $request['m_name'],
			'l_name' => $request['l_name'],
			'number' => $request['number'],
			'designation' => $request['designation'],
			'address' => $request['address'],
			'state' => $request['state'],
			'city' => $request['city'],
			'pincode' => $request['pincode'],
			'is_active' => isset($request['is_active']) ? true : false,
		];
	
		// Add 'role_id' to the update data only if it exists in the request and is not null
		if (!empty($request['role_id'])) {
			$updateData['role_id'] = $request['role_id'];
		}
	
		$user_data = User::where('id', $request['edit_id'])->update($updateData);
		// $this->updateRolesPermissions($request, $request->edit_id);
		return $request->edit_id;
	}

	public function deleteById($id){
		try {   
			$deleteDataById = User::find($id);
			
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

	public function getById($id){
		try {
			
			$user = User::leftJoin('tbl_roles', 'tbl_roles.id', '=', 'users.role_id')
				->leftJoin('tbl_area as state_user', 'users.state', '=', 'state_user.location_id')
				->leftJoin('tbl_area as city_user', 'users.city', '=', 'city_user.location_id')
				->where('users.id', $id)
				->select('users.f_name','users.m_name','users.l_name','users.u_email','users.number','users.designation','users.address','users.pincode','tbl_roles.role_name','state_user.name as state','city_user.name as city')
				->first();
				
			if ($user) {
				return $user;
			} else {
				return null;
			}
		} catch (\Exception $e) {
			return [
				'msg' => $e->getMessage(),
				'status' => 'error'
			];
		}
	}
	public function showParticularDetails($id){
		try {
			
			$user = User::leftJoin('tbl_roles', 'tbl_roles.id', '=', 'users.role_id')
				->leftJoin('tbl_area as state_user', 'users.state', '=', 'state_user.location_id')
				->leftJoin('tbl_area as city_user', 'users.city', '=', 'city_user.location_id')
				->where('users.id', $id)
				->select('users.f_name','users.m_name','users.l_name','users.u_email','users.number','users.designation','users.address','users.pincode','tbl_roles.role_name','state_user.name as state','city_user.name as city')
				->first();
				
			if ($user) {
				return $user;
			} else {
				return null;
			}
		} catch (\Exception $e) {
			return [
				'msg' => $e->getMessage(),
				'status' => 'error'
			];
		}
	}
	public function updateOne($request){
        try {
            $user = User::find($request); // Assuming $request directly contains the ID

            // Assuming 'is_active' is a field in the userr model
            if ($user) {
                $is_active = $user->is_active === 1 ? 0 : 1;
                $user->is_active = $is_active;
                $user->save();

                return [
                    'msg' => 'User updated successfully.',
                    'status' => 'success'
                ];
            }

            return [
                'msg' => 'User not found.',
                'status' => 'error'
            ];
        } catch (\Exception $e) {
            return [
                'msg' => 'Failed to update User.',
                'status' => 'error'
            ];
        }
    }
}
