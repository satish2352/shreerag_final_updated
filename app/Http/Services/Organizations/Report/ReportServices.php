<?php
namespace App\Http\Services\Organizations\Report;
use App\Http\Repository\Organizations\Report\ReportRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
// use App\Models\ {
//     DesignModel
//     };

use Config;
class ReportServices
{
    protected $repo;
    public function __construct() {

        $this->repo = new ReportRepository();

    }
    public function getAllReceivedFromFianance(){
        try {
          $data_output = $this->repo->getAllReceivedFromFianance();
     
          return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    // public function getCompletedProductList(Request $request){
    //     try {
    //       $data_output = $this->repo->getCompletedProductList($request);
    //       return $data_output;
    //     } catch (\Exception $e) {
    //         return $e;
    //     }
    // }



    public function getCompletedProductList($request)
    {
        try {
            $data_output = $this->repo->getCompletedProductList($request);
            // dd($data_output);
            // die();
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    
    
}