<?php
namespace App\Http\Services\Organizations\Report;
use App\Http\Repository\Organizations\Report\ReportRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
       public function getCompletedProductList($request){
        try {
            $data_output = $this->repo->getCompletedProductList($request);
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }   
      public function listDesignReport($request){
        try {
            $data_output = $this->repo->listDesignReport($request);
          
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    
}