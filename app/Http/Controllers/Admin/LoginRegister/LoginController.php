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

     // ✅ Get user IP address
        // $ipAddress = $request->ip();
            $systemIp = getHostByName(getHostName());
            $ipAddress = getHostByName(getHostName());
   // ✅ Update location if provided
        if ($request->filled(['latitude', 'longitude'])) {
            $latitude  = $request->latitude;
            $longitude = $request->longitude;

            $user->update([
                'latitude'  => $latitude,
                'longitude' => $longitude,
            ]);

            $address = null;

            // 🔑 LocationIQ Reverse Geocoding (with addressdetails=1)
            try {
                $apiKey = "pk.1657d640f433dbcd0b009e097699adc6"; // your token
                $url = "https://us1.locationiq.com/v1/reverse.php?key={$apiKey}&lat={$latitude}&lon={$longitude}&format=json&addressdetails=1";

                $response = Http::get($url);
                $json = $response->json();

                if (!empty($json['address'])) {
                    $addressParts = [
                        $json['address']['house_number'] ?? null,
                        $json['address']['road'] ?? null,
                        $json['address']['neighbourhood'] ?? null,
                        $json['address']['suburb'] ?? null,
                        $json['address']['city'] ?? null,
                        $json['address']['state'] ?? null,
                        $json['address']['postcode'] ?? null,
                        $json['address']['country'] ?? null,
                    ];

                    $address = implode(', ', array_filter($addressParts));
                } else {
                    $address = $json['display_name'] ?? null;
                }

                if ($address) {
                    $user->update(['location_address' => $address]);
                }
            } catch (\Exception $e) {
                Log::error("LocationIQ Reverse Geocoding failed: " . $e->getMessage());
            }

            // ✅ Save login history AFTER fetching address
            $loginHistory = new LoginHistory();
            $loginHistory->user_id          = $user->id;
            $loginHistory->latitude         = $latitude;
             $loginHistory->ip_address         = $ipAddress;
            $loginHistory->longitude        = $longitude;
            $loginHistory->location_address = $address;
            $loginHistory->save();
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
