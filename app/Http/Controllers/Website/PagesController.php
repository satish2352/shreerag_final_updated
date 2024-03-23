<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function index()
    {
        try {
            return view('website.pages.index');
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function about()
    {
        try {
            return view('website.pages.about');
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function services()
    {
        try {
            return view('website.pages.services');
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function product()
    {
        try {
            return view('website.pages.product');
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function product_details()
    {
        try {
            return view('website.pages.product_details');
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function contact()
    {
        try {
            return view('website.pages.contact');
        } catch (\Exception $e) {
            return $e;
        }
    }
}
