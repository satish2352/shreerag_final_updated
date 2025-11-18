<?php

namespace App\Http\Services\Admin\LoginRegister;

use App\Http\Repository\Admin\LoginRegister\LoginRepository;
use Illuminate\Support\Facades\Hash;

class LoginService
{
    protected $repo;
    protected $service;

    public function __construct()
    {
        $this->repo = new LoginRepository();
    }

    public function checkLogin($request)
    {
        $response = $this->repo->checkLogin($request);

        if ($response['user_details']) {
            $password = $request['password'];
            if (Hash::check($password, $response['user_details']['u_password'])) {

                $request->session()->put('user_id', $response['user_details']['id']);
                $request->session()->put('org_id', $response['user_details']['id']);
                $request->session()->put('role_id', $response['user_details']['role_id']);
                $request->session()->put('u_email', $response['user_details']['u_email']);
                $request->session()->put('org_id', $response['user_details']['org_id']);
                $json = ['status' => 'success', 'msg' => $response['user_details'], 'role_id' => $response['user_details']['role_id']];
            } else {
                $json = ['status' => 'failed', 'msg' => 'These credentials do not match our records.'];
            }
        } else {
            $json = ['status' => 'failed', 'msg' => 'These credentials do not match our records.'];
        }
        return $json;
    }
}
