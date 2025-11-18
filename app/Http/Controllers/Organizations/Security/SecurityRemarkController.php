<?php

namespace App\Http\Controllers\Organizations\Security;

use App\Http\Controllers\Controller;
use Exception;

class SecurityRemarkController extends Controller
{
    public function index()
    {
        try {
            return view('organizations.security.remark.list-remark');
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function add()
    {
        try {
            return view('organizations.security.remark.add-remark');
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function edit()
    {
        try {
            return view('organizations.security.remark.edit-remark');
        } catch (\Exception $e) {
            return $e;
        }
    }
}
