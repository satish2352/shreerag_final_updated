<?php

namespace App\Http\Repository\Admin\LoginRegister;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ {
    User,
    RolesPermissions,
    Permissions
};
use App\dbmodel\Applicant;
use App\SuperAdmin;


class LoginRepository
{
	function __construct() {
		
    }

    //For Applicant 
    public function checkLogin($request) {
        // get school description
        $data = [];
        $data['user_details'] = User::where( [
                                        'u_email' => $request['email'],
                                        'is_active' =>true
                                        ])
                                        ->select('*')
                                        ->first();
       
        return $data;
    }
}