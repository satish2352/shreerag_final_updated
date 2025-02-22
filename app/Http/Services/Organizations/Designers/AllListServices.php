<?php
namespace App\Http\Services\Organizations\Designers;
use App\Http\Repository\Organizations\Designers\AllListRepositor;
use Carbon\Carbon;

use Config;
class AllListServices
{
    protected $repo;
    public function __construct() {

        $this->repo = new AllListRepositor();

    }
    public function acceptdesignbyProduct(){
        try {
            $data_output = $this->repo->acceptdesignbyProduct();
        //   dd($data_output);
        //   die();
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function listDesignReport(){
        try {
            $data_output = $this->repo->listDesignReport();
          
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getAllListDesignRecievedForCorrection(){
        try {
            $data_output =  $this->repo->getAllListDesignRecievedForCorrection();
        //   dd($data_output);
        //   die();
           return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    } 

    public function getAllListCorrectionToDesignFromProduction(){
        try {
            return $this->repo->getAllListCorrectionToDesignFromProduction();
        } catch (\Exception $e) {
            return $e;
        }
    } 


}