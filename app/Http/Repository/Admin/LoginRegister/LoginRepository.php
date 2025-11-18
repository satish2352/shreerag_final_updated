<?php

namespace App\Http\Repository\Admin\LoginRegister;

use App\Models\{
    User,
};


class LoginRepository
{
    function __construct() {}

    public function checkLogin($request)
    {
        $data = [];
        $data['user_details'] = User::where([
            'u_email' => $request['email'],
            'is_active' => true
        ])
            ->select('*')
            ->first();

        return $data;
    }
}
