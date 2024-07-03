<?php
namespace App\Http\Repository\Website;

use Illuminate\Database\QueryException;
use DB;
use Illuminate\Support\Carbon;
use Session;
use App\Models\ {
    Testimonial

};

class IndexRepository  {


    public function getAllTestimonial(){
        try {
            $data_output = Testimonial::where('is_active','=',true);
            $data_output =  $data_output->select('title','description','position', 'image');
            $data_output =  $data_output->orderBy('updated_at', 'desc')->get()->toArray();
            return  $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }

}