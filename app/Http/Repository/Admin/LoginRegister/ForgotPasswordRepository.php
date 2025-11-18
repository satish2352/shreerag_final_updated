<?php

namespace App\Http\Repository\Admin\LoginRegister;

use App\User;

class ForgotPasswordRepository
{
    function __construct() {}

    public function checkCredentials($request)
    {
        return User::where([['name', '=', $request->userid], ['mobile', '=', $request->mobile]])->select('*')->get();
    }
}
