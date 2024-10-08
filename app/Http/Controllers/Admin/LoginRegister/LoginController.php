<?php

namespace App\Http\Controllers\Admin\LoginRegister;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Services\Admin\LoginRegister\LoginService;
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
        
        return view('admin.login');
    }

    public function submitLogin(Request $request) {
        $ses_userId = session()->get('user_id');
        $rules = [
            'email' => 'required | email', 
            'password' => 'required',
           
            ];
        $messages = [   
            'email.required' => 'Please Enter Email.',
            'email.email' => 'Please Enter a Valid Email Address.',
            'password.required' => 'Please Enter Password.',
        ];
    
        try {
            $validation = Validator::make($request->all(),$rules,$messages);
            if($validation->fails() )
            {
                return redirect('login')
                    ->withInput()
                    ->withErrors($validation);
            } else {
                $resp  = self::$loginServe->checkLogin($request);
                if($resp['status']=='success') {

                    if ($ses_userId == '1') {
                        return redirect('dashboard');
                    } elseif ($ses_userId == '2') {
                        return redirect('/owner/dashboard');
                    } elseif ($ses_userId == '3') {
                        return redirect('/designdept/dashboard');
                    } elseif ($ses_userId == '4') {
                        return redirect('/proddept/dashboard');
                    } elseif ($ses_userId == '5') {
                        return redirect('/storedept/dashboard');
                    } elseif ($ses_userId == '6') {
                        return redirect('/purchase/dashboard');
                    } elseif ($ses_userId == '7') {
                        return redirect('/securitydept/dashboard');
                    } elseif ($ses_userId == '8') {
                        return redirect('/quality/dashboard');
                    } elseif ($ses_userId == '9') {
                        return redirect('/financedept/dashboard');
                    } elseif ($ses_userId == '10') {
                        return redirect('/hr/dashboard');
                    } elseif ($ses_userId == '11') {
                        return redirect('/logisticsdept/dashboard');
                    }  elseif ($ses_userId == '12') {
                        return redirect('/dispatchdept/dashboard');
                    } elseif ($ses_userId == '13') {
                        return redirect('/cms/dashboard');
                    } else {
                        // return redirect('dashboard'); // Default dashboard
                    }
                    // return redirect('dashboard');
                } else {
                    return redirect('/login')->with('error', $resp['msg']);
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

        return redirect('/login');
    }
}
