<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Website\ProductServices;
use Session;
use Validator;


class ProductServicesController extends Controller
{
    public function __construct()
    {
        $this->service = new ProductServices();
    }


    public function index(Request $request)
    {
        try {
            $data_output_product = $this->service->getAllProduct();
            
            
                        
            return view('website.pages.product', compact('data_output_product'));
        } catch (\Exception $e) {
            return $e;
        }
    }


    public function getAllServices()
    {
        try {
            $data_output_services = $this->service->getAllServices();
        

        } catch (\Exception $e) {
            return $e;
        }
        return view('website.pages.services',compact('data_output_services'));
    } 

    public function showParticularPrdouct(Request $request)
    {
        try {
            
            $data_output_product_detail = $this->service->getByIdProducts($request->show_id);
           
            return view('website.pages.product_details', compact('data_output_product_detail'));
        } catch (\Exception $e) {
            return $e;
        }
    } 
}

