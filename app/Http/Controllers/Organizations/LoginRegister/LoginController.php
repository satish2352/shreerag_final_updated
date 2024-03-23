<?php

namespace App\Http\Controllers\Organizations\LoginRegister;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Services\Organizations\LoginRegister\LoginService;
use Session;
use Validator;
use PDO;

class LoginController extends Controller
{
    public static $loginServe,$masterApi;
    public function __construct()
    {
        self::$loginServe = new LoginService();
    }

    public function index(){
        
        return view('organizations.login');
    }

  public function submitLogin(Request $request)
{   
    $rules = [
        'email' => 'required|email',
        'password' => 'required',
    ];

    $messages = [
        'email.required' => 'Please Enter Email.',
        'email.email' => 'Please Enter a Valid Email Address.',
        'password.required' => 'Please Enter Password.',
    ];

    try {
        $validation = Validator::make($request->all(), $rules, $messages);

        if ($validation->fails()) {
            return redirect('/organizations/login')
                ->withInput()
                ->withErrors($validation);
        } else {
            $resp = self::$loginServe->checkLogin($request);
            // dd($resp);
            if ($resp['status'] == 'success') {
                if (is_object($resp['msg']) && property_exists($resp['msg'], 'id')) {
                    $userId = $resp['msg']->id;
                    Session::put('user_id', $userId);
                }
                if ($resp['role_id'] == 1) 
                {
                    return redirect('/dashboard');
                } 
                else if ($request->session()->get('role_name') == "Hr. Departments")
                {
                    return redirect('/hr-dashboard');
                } else if ($request->session()->get('role_name') == "Admin")
                {
                    return redirect('/admin-dashboard');
                }
                else if ($request->session()->get('role_name') == "Designer")
                {
                    return redirect('/designer-dashboard');
                } 
                else if ($request->session()->get('role_name') == "Production")
                {
                    return redirect('/production-dashboard');
                }
                else if ($request->session()->get('role_name') == "Purchase")
                {
                    return redirect('/purchase-dashboard');
                }
                else if ($request->session()->get('role_name') == "Store")
                {
                    return redirect('/store-dashboard');
                }
            } 
            else {
                return redirect('/organizations/login')->with('error', $resp['msg']);
            }
        }
    } catch (Exception $e) {
        return redirect('feedback-suggestions')->withInput()->with(['msg' => $e->getMessage(), 'status' => 'error']);
    }
}


    public function logout(Request $request)
    {
 
        $request->session()->flush();

        $request->session()->regenerate();
        return redirect('/organizations/login');
    }
}
