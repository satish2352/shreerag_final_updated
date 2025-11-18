<?php

namespace App\Http\Controllers\Website;

use Exception;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Website\ContactUsServices;
use Illuminate\Support\Facades\Validator;

class ContactUsController extends Controller
{
    protected $service;

    public function __construct()
    {
        $this->service = new ContactUsServices();
    }

    public function getContactUs()
    {

        try {
            return view('website.pages.contactus');
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function addContactUs(Request $request)
    {
        $rules = [
            'full_name' => 'required',
            'email' => 'required|email',
            'mobile_number' => 'required|regex:/^(\+\d{1,3}[- ]?)?\d{10}$/',
            'subject' => 'required',
            'message' => 'required',
            'g-recaptcha-response' => 'required|captcha',
        ];

        $messages = [
            'full_name.required' => 'Please Enter Full Name.',
            'email.required' => 'Please Enter Email.',
            'email.email' => 'Please Enter a Valid Email Id.',
            'mobile_number.required' => 'Please Enter Mobile Number.',
            'mobile_number.regex' => 'Please Enter a Valid Mobile Number.',
            'subject.required' => 'Please Enter Company Name.',
            'message.required' => 'Please Enter Message.',
            'g-recaptcha-response.captcha' => 'Captcha error! try again later or contact site admin.',
            'g-recaptcha-response.required' => 'Please verify that you are not a robot.',
        ];

        try {
            $validation = Validator::make($request->all(), $rules, $messages);

            if ($validation->fails()) {
                return redirect('contactus')
                    ->withInput()
                    ->withErrors($validation);
            }

            $add_contact = $this->service->addAll($request);

            if ($add_contact) {
                $request->session()->flash('success', 'Contact Us Information Submitted Successfully!!');
                return redirect('contactus')->with('sweet_success', 'Contact Us Information Submitted Successfully!!');
            } else {
                $request->session()->flash('error', 'Failed to Submit Contact Us Information.');
                return redirect('contactus')->with('sweet_error', 'Failed to Submit Contact Us Information.');
            }
        } catch (Exception $e) {
            return redirect('contactus')->withInput()->with([
                'sweet_error' => 'An error occurred: ' . $e->getMessage(),
            ]);
        }
    }
}
