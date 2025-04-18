<?php
namespace App\Http\Repository\Admin\LoginRegister;

use Illuminate\Database\QueryException;
use DB;
use Illuminate\Support\Carbon;
use Session;
use App\Models\{
	User,
	Permissions,
	RolesPermissions,
	Roles
};
use Illuminate\Support\Facades\Mail;

class RegisterRepository
{

	public function getUsersList() {
        $data_users = User::join('roles', function($join) {
							$join->on('users.role_id', '=', 'roles.id');
						})
						// ->where('users.is_active','=',true)
						->select('roles.role_name',
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
							)->get();
							// ->toArray();

		return $data_users;
	}
	public function register($request)
	{
		$ipAddress = getIPAddress($request);
		$user_data = new User();
		$user_data->u_email = $request['u_email'];
		// $user_data->u_uname = $request['u_uname'];
		$user_data->u_password = bcrypt($request['u_password']);
		$user_data->role_id = $request['role_id'];
		$user_data->f_name = $request['f_name'];
		$user_data->m_name = $request['m_name'];
		$user_data->l_name = $request['l_name'];
		$user_data->number = $request['number'];
		$user_data->designation = $request['designation'];
		$user_data->address = $request['address'];
		$user_data->state = $request['state'];
		$user_data->city = $request['city'];
		$user_data->pincode = $request['pincode'];
		$user_data->ip_address = $ipAddress;
		$user_data->is_active = isset($request['is_active']) ? true : false;
		$user_data->save();

		$last_insert_id = $user_data->id;
		// $this->insertRolesPermissions($request, $last_insert_id);

		$imageProfile = $last_insert_id . '_english.' . $request->user_profile->getClientOriginalExtension();
        
        $user_detail = User::find($last_insert_id); // Assuming $request directly contains the ID
        $user_detail->user_profile = $imageProfile; // Save the image filename to the database
        $user_detail->save();
        return $last_insert_id;

	}

	public function update($request)
	{
        $ipAddress = getIPAddress($request);
		$user_data = User::where('id',$request['edit_id']) 
						->update([
							// 'u_uname' => $request['u_uname'],
							'role_id' => $request['role_id'],
							'f_name' => $request['f_name'],
							'm_name' => $request['m_name'],
							'l_name' => $request['l_name'],
							'number' => $request['number'],
							'designation' => $request['designation'],
							'address' => $request['address'],
							'state' => $request['state'],
							'city' => $request['city'],
							'pincode' => $request['pincode'],
							'is_active' => isset($request['is_active']) ? true :false,
						]);
		
		
		return $request->edit_id;
	}
	public function checkDupCredentials($request)
	{
		return User::where('u_email', '=', $request['u_email'])
			// ->orWhere('u_uname','=',$request['u_uname'])
			->select('id')->get();
	}

	public function editUsers($reuest)
	{

		$data_users = [];

		$data_users['roles'] = Roles::where('is_active', true)
			->select('id', 'role_name')
			->get()
			->toArray();
		$data_users['permissions'] = Permissions::where('is_active', true)
			->select('id', 'route_name', 'permission_name', 'url')
			->get()
			->toArray();

		$data_users_data = User::join('roles', function ($join) {
			$join->on('users.role_id', '=', 'roles.id');
		})
			// ->join('roles_permissions', function($join) {
			// 	$join->on('users.id', '=', 'roles_permissions.user_id');
			// })
			->where('users.id', '=', $reuest->edit_id)
			// ->where('roles_permissions.is_active','=',true)
			// ->where('users.is_active','=',true)
			->select(
				'roles.id as role_id',
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

	$data_users_data = User::join('roles', function($join) {
						$join->on('users.role_id', '=', 'roles.id');
					})
					// ->join('roles_permissions', function($join) {
					// 	$join->on('users.id', '=', 'roles_permissions.user_id');
					// })
					->where('users.id','=',$reuest->edit_id)
					// ->where('roles_permissions.is_active','=',true)
					// ->where('users.is_active','=',true)
					->select('roles.id as role_id',
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

	public function delete($id)
    {
        try {
            $user = User::find($id);
            if ($user) {
              
                $user->delete();
               
                return $user;
            } else {
                return null;
            }
        } catch (\Exception $e) {
            return $e;
        }
    }

	public function getById($id)
	{
		try {
			$user = User::leftJoin('roles', 'roles.id', '=', 'users.role_id')
				->leftJoin('tbl_area as state_user', 'users.state', '=', 'state_user.location_id')
				->leftJoin('tbl_area as city_user', 'users.city', '=', 'city_user.location_id')
				->where('users.id', $id)
				->select('users.f_name','users.m_name','users.l_name','users.u_email','users.number','users.designation','users.address','users.pincode','users.user_profile','roles.role_name','state_user.name as state','city_user.name as city')
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

	public function getProfile()
	{
		$user_detail = User::where('is_active', true)
			->where('id', session()->get('user_id'))
			->select('id', 'f_name', 'm_name', 'l_name', 'u_email', 'u_password', 'number', 'designation','user_profile')
			->first();
		return $user_detail;
	}


	public function updateProfile($request)
	{
		try {
			
			$return_data = array();
			$otp = rand(6, 999999);

			
			$update_data = [
				'f_name' => $request->f_name,
				'm_name' => $request->m_name,
				'l_name' => $request->l_name,
				'designation' => $request->designation,
			];
			
			if (isset($return_data['user_profile'])) {
				$previousUserProfile = $update_data->user_profile;
			}
			if (($request->number != $request->old_number) && !isset($request->u_password)) {
				$this->sendOTPEMAIL($otp, $request);
				info("only mobile change");
				$return_data['password_change'] = 'no';
				$update_data['otp'] = $otp;
				$return_data['mobile_change'] = 'yes';
				$return_data['user_id'] = $request->edit_user_id;
				$return_data['new_mobile_number'] = $request->number;
				$return_data['u_password_new'] = '';
				$return_data['msg'] = "OTP sent on registered on email";
				$return_data['msg_alert'] = "green";

			}

			if ((isset($request->u_password) && $request->u_password !== '') && ($request->number == $request->old_number)) {
				info("only password change");
				// $update_data['u_password'] = bcrypt($request->u_password);
				$return_data['password_change'] = 'yes';
				$return_data['mobile_change'] = 'no';
				$update_data['otp'] = $otp;
				$return_data['user_id'] = $request->edit_user_id;
				$return_data['u_password_new'] = bcrypt($request->u_password);
				$return_data['new_mobile_number'] = '';
				$return_data['msg'] = "OTP sent on registered on email";
				$return_data['msg_alert'] = "green";

				$this->sendOTPEMAIL($otp, $request);
			}

			if ((isset($request->u_password) && $request->u_password !== '') && ($request->number != $request->old_number)) {
				info("only password and mobile number changed");
				$update_data['otp'] = $otp;
				$return_data['u_password_new'] = bcrypt($request->u_password);
				$return_data['password_change'] = 'yes';
				$return_data['mobile_change'] = 'yes';
				$return_data['user_id'] = $request->edit_user_id;
				$return_data['new_mobile_number'] = $request->number;
				$return_data['msg'] = "OTP sent on registered on email";
				$return_data['msg_alert'] = "green";

				$this->sendOTPEMAIL($otp, $request);
			}
			
			User::where('id', $request->edit_user_id)->update($update_data);

			$user_data = User::find($request->edit_user_id);
			$previousUserProfile = $user_data->english_image;
			$last_insert_id = $user_data->id;

            $return_data['last_insert_id'] = $last_insert_id;
            $return_data['user_profile'] = $previousUserProfile;
			return $return_data;


		} catch (\Exception $e) {
			info($e);
		}

		// return $update_data;
	}

	public function sendOTPEMAIL($otp, $request) {
		try {
			$email_data = [
				'otp' => $otp,
			];
			$toEmail = $request->u_email;
			$senderSubject = 'Disaster Management OTP ' . date('d-m-Y H:i:s');
			$fromEmail = env('MAIL_USERNAME');
			Mail::send('admin.email.emailotp', ['email_data' => $email_data], function ($message) use ($toEmail, $fromEmail, $senderSubject) {
				$message->to($toEmail)->subject($senderSubject);
				$message->from($fromEmail, 'Disaster Management OTP');
			});
			return 'ok';
		} catch (\Exception $e) {
			info($e);
		}
	}
	
}