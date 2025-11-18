<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Http\Services\Website\ProductServices;
use App\Http\Services\Website\IndexServices;

class PagesController extends Controller
{
    protected $service;
    protected $service_index;
    public function __construct()
    {
        $this->service = new ProductServices();
        $this->service_index = new IndexServices();
    }

    public function index()
    {
        try {

            $data_output_product_limit = $this->service->getAllProductLimit();
            $data_output_product = $this->service->getAllProduct();
            $data_output_testimonial = $this->service_index->getAllTestimonial();
            return view('website.pages.index', compact('data_output_product_limit', 'data_output_product', 'data_output_testimonial'));
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
