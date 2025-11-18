<?php

namespace App\Http\Repository\Website;

use Illuminate\Support\Facades\Log;
use App\Models\{
    Products,
    ProductServices,
};

class ProductServicesRepository
{


    // public function getAllProduct(){
    //     try {
    //         $data_output = Products::where('is_active','=',true) ->where('is_deleted', 0)
    //         ->limit(6)
    //         ->get();
    //         $data_output =  $data_output->select('id','title','description', 'image');
    //         $data_output =  $data_output->orderBy('updated_at', 'desc')->get()->toArray();
    //         return  $data_output;
    //     } catch (\Exception $e) {
    //         return $e;
    //     }
    // }

    public function getAllProductLimit()
    {
        try {
            return Products::where('is_active', true)
                ->where('is_deleted', 0)
                ->select('id', 'title', 'description', 'image')
                ->orderBy('updated_at', 'desc')
                ->limit(6)
                ->get()
                ->toArray(); // Convert to array if needed

        } catch (\Exception $e) {
            Log::error('Error in getAllProduct: ' . $e->getMessage());
            return []; // Return an empty array to prevent breaking the application
        }
    }
    public function getAllProduct()
    {
        try {
            return Products::where('is_active', true)
                ->where('is_deleted', 0)
                ->select('id', 'title', 'description', 'image')
                ->orderBy('updated_at', 'desc')
                ->get()
                ->toArray(); // Convert to array if needed

        } catch (\Exception $e) {
            Log::error('Error in getAllProduct: ' . $e->getMessage());
            return []; // Return an empty array to prevent breaking the application
        }
    }

    public function getAllServices()
    {
        try {
            $data_output = ProductServices::where('is_active', '=', true);
            $data_output =  $data_output->select('id', 'title', 'image');
            $data_output =  $data_output->orderBy('updated_at', 'desc')->get()->toArray();
            return  $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getByIdProducts($id)
    {
        try {
            $data_output = Products::where('is_active', '=', true);
            $data_output =  $data_output->select('title', 'description', 'image');
            $data_output = $data_output->where('id', $id)->get()->toArray();
            return  $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
}
