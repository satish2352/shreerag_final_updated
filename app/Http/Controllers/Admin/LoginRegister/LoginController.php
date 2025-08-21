<?php

namespace App\Http\Controllers\Admin\LoginRegister;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;
use App\Http\Services\Admin\LoginRegister\LoginService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\ {
   User,
   LoginHistory
    };
use Session;
use Validator;
use PDO;

class LoginController extends Controller
 {
    public static $loginServe, $masterApi;

    public function __construct(){
        self::$loginServe = new LoginService();
    }
    public function index() {

        return view( 'admin.login' );
    }
    // public function submitLogin( Request $request ) {
    //     $rules = [
    //         'email' => 'required|email',
    //         'password' => 'required',
    //         'g-recaptcha-response' => 'required|captcha',
    //     ];
    //     $messages = [
    //         'email.required' => 'Please Enter Email.',
    //         'email.email' => 'Please Enter a Valid Email Address.',
    //         'password.required' => 'Please Enter Password.',
    //         'g-recaptcha-response.captcha' => 'Captcha error! Try again later or contact site admin.',
    //         'g-recaptcha-response.required' => 'Please verify that you are not a robot.',
    //     ];

    //     $validation = Validator::make( $request->all(), $rules, $messages );
    //     if ( $validation->fails() ) {
    //         return redirect( 'login' )->withInput()->withErrors( $validation );
    //     }

    //     // Check login credentials
    //     $resp = self::$loginServe->checkLogin( $request );
    //     if ( $resp[ 'status' ] == 'success' ) {
    //         // Assuming `checkLogin` function stores `user_id` in session on success
    //         $ses_userId = session()->get( 'user_id' );
    //         // Route based on the user's role
    //         switch ($ses_userId) {
    //             case 2:
    //                 return redirect('/owner/dashboard');
    //             case 3:
    //                 return redirect('/designdept/dashboard');
    //             case 4:
    //                 return redirect('/proddept/dashboard');
    //             case 5:
    //                 return redirect('/storedept/dashboard');
    //             case 6:
    //                 return redirect('/purchase/dashboard');
    //             case 7:
    //                 return redirect('/securitydept/dashboard');
    //             case 8:
    //                 return redirect('/quality/dashboard');
    //             case 9:
    //                 return redirect('/financedept/dashboard');
    //             case 10:
    //                 return redirect('/hr/dashboard');
    //             case 11:
    //                 return redirect('/logisticsdept/dashboard');
    //             case 12:
    //                 return redirect('/dispatchdept/dashboard');
    //             case 13:
    //                     return redirect('/cms/dashboard');
    //             case 14:
    //                         return redirect('/inventory/dashboard');
    //                 default:
    //                 return redirect('/dashboard'); // Default dashboard
    //         }
    //     } else {
    //         return redirect('/login')->with('error', $resp['msg']);
    //     }
    // }
     public function submitLogin( Request $request ) {
        $rules = [
            'email' => 'required|email',
            'password' => 'required',
            'g-recaptcha-response' => 'required|captcha',
        ];
        $messages = [
            'email.required' => 'Please Enter Email.',
            'email.email' => 'Please Enter a Valid Email Address.',
            'password.required' => 'Please Enter Password.',
            'g-recaptcha-response.captcha' => 'Captcha error! Try again later or contact site admin.',
            'g-recaptcha-response.required' => 'Please verify that you are not a robot.',
        ];

        $validation = Validator::make( $request->all(), $rules, $messages );
        if ( $validation->fails() ) {
            return redirect( 'login' )->withInput()->withErrors( $validation );
        }

        // Check login credentials
        $resp = self::$loginServe->checkLogin( $request );
        if ( $resp[ 'status' ] == 'success' ) {
            // Assuming `checkLogin` function stores `user_id` in session on success
            $ses_userId = session()->get( 'user_id' );


           $user = User::find($ses_userId);

    if (!$user) {
        return redirect('/login')->with('error', 'User not found.');
    }

    // ✅ Update location if provided
    if ($request->filled(['latitude', 'longitude'])) {
        $latitude  = $request->latitude;
        $longitude = $request->longitude;

        $user->update([
            'latitude'  => $latitude,
            'longitude' => $longitude,
        ]);

        // Save login history
        $loginHistory = new LoginHistory();
        $loginHistory->user_id          = $user->id;
        $loginHistory->location_address = $request->location_address ?? null;
        $loginHistory->latitude         = $latitude;
        $loginHistory->longitude        = $longitude;
        $loginHistory->save();
    

            // Google Geocoding API
            // $apiKey = 'YOUR_GOOGLE_API_KEY'; // Replace this
            // $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=$latitude,$longitude&key=$apiKey";

            try {
                $response = Http::get($url);
                $json = $response->json();
                $address = $json['results'][0]['formatted_address'] ?? null;

                if ($address) {
                    User::where('id', $ses_userId)->update([
                        'location_address' => $address
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Geocoding failed: ' . $e->getMessage());
            }
        }

            // Route based on the user's role
            switch ($ses_userId) {
                case 2:
                    return redirect('/owner/dashboard');
                case 3:
                    return redirect('/designdept/dashboard');
                case 4:
                    return redirect('/proddept/dashboard');
                case 5:
                    return redirect('/storedept/dashboard');
                case 6:
                    return redirect('/purchase/dashboard');
                case 7:
                    return redirect('/securitydept/dashboard');
                case 8:
                    return redirect('/quality/dashboard');
                case 9:
                    return redirect('/financedept/dashboard');
                case 10:
                    return redirect('/hr/dashboard');
                case 11:
                    return redirect('/logisticsdept/dashboard');
                case 12:
                    return redirect('/dispatchdept/dashboard');
                case 13:
                        return redirect('/cms/dashboard');
                case 14:
                            return redirect('/inventory/dashboard');
                // case 15:
                //         return redirect('/estimation/dashboard');            
                    default:
                    return redirect('/dashboard'); // Default dashboard
            }
        } else {
            return redirect('/login')->with('error', $resp['msg']);
        }
    }
    public function logout(Request $request)
    {
        $email = session()->get('u_email');
    
        if ($email) {
            Cache::forget("dashboard_{$email}");
            Cache::forget("user_id_{$email}");
        }
    
        Auth::logout();
        session()->flush();
    
        return redirect('/login');
    }
}
