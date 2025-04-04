<?php
namespace App\Http\Services\Organizations\Logistics;
use App\Http\Repository\Organizations\Logistics\AllListRepository;
use Carbon\Carbon;

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
          return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getAllLogistics(){
        try {
          $data_output = $this->repo->getAllLogistics();
          return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getAllListSendToFiananceByLogistics(){
      try {
        $data_output = $this->repo->getAllListSendToFiananceByLogistics();
    
        return $data_output;
      } catch (\Exception $e) {
          return $e;
      }
  }
    
}