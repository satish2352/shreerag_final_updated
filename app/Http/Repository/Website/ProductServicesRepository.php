<?php
namespace App\Http\Repository\Website;

use Illuminate\Database\QueryException;
use DB;
use Illuminate\Support\Carbon;
use Session;
use App\Models\ {
    Products,
    ProductServices,
    Team

};

class ProductServicesRepository  {


    public function getAllProduct(){
        try {
            $data_output = Products::where('is_active','=',true);
            $data_output =  $data_output->select('id','title','description', 'image');
            $data_output =  $data_output->orderBy('updated_at', 'desc')->get()->toArray();
            return  $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getAllServices(){
        try {
            $data_output = ProductServices::where('is_active','=',true);
            $data_output =  $data_output->select('id','title', 'image');
            $data_output =  $data_output->orderBy('updated_at', 'desc')->get()->toArray();
            return  $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getByIdProducts($id)
    {
        try {
            $data_output = Products::where('is_active','=',true);
            $data_output =  $data_output->select('title','description', 'image');
             $data_output = $data_output->where('id', $id)->get()->toArray();
            return  $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }

}