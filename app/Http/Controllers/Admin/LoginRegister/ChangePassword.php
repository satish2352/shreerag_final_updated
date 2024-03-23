<?php

namespace App\Http\Controllers\Admin\LoginRegister;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Services\Admin\LoginRegister\LoginService;
use Session;
use Validator;
use PDO;
use App\Models\User;

class ChangePassword extends Controller
{
    public function index()
    {
        return view('admin.change-password');
    }

   
    public function updatePassword(Request $request)
    {   
        $validator = Validator::make($request->all(), [
        'new_password' => [
            'required',
            'string',
            'min:8',
        ],
    ]);
        // dd($request);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }
        $userId = Session::get('user_id');
        if (!$userId) {
           return redirect()->back()->with('error', 'Password not updated !');
        }
        $user = User::find($userId);
        if (!$user) {
            return redirect()->back()->with('error', 'Password not updated !');
        }
        if ($request->new_password != $request-> confirm_password) {
            return redirect()->back()->with('error', 'New Password & Confirm Password not match  !');
        }
    User::where('id', $userId)->update([
            'u_password' => bcrypt($request->new_password),
        ]);
        return redirect()->back()->with('success', 'Password updated successfully!');
    }
}
// $2y$10$NhPxtjUHtLPRo4E3zijkpuHhCdXsLfIvK/i8NoLPVlpp91YUdKokC