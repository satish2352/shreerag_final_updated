<?php
namespace App\Http\Services\Organizations\Logistics;
use App\Http\Repository\Organizations\Logistics\AllListRepository;
use Carbon\Carbon;
// use App\Models\ {
//     DesignModel
//     };

use Config;
class AllListServices
{
    protected $repo;
    public function __construct() {

        $this->repo = new AllListRepository();

    }
    public function getAllCompletedProduction(){
        try {
          $data_output = $this->repo->getAllCompletedProduction();
    // dd($data_output);
    // die();
          return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getAllLogistics(){
        try {
          $data_output = $this->repo->getAllLogistics();
    //      dd($data_output);
    // die();
          return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getAllListSendToFiananceByLogistics(){
      try {
        $data_output = $this->repo->getAllListSendToFiananceByLogistics();
    // dd($data_output);
    // die();
        return $data_output;
      } catch (\Exception $e) {
          return $e;
      }
  }
    
}