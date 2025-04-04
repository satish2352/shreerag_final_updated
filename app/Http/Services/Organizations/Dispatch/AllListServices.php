<?php
namespace App\Http\Services\Organizations\Dispatch;
use App\Http\Repository\Organizations\Dispatch\AllListRepository;
use Carbon\Carbon;

use Config;
class AllListServices
{
    protected $repo;
    public function __construct() {

        $this->repo = new AllListRepository();

    }

   
    public function getAllReceivedFromFianance(){
        try {
          $data_output = $this->repo->getAllReceivedFromFianance();
          return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getAllDispatch(){
        try {
          $data_output = $this->repo->getAllDispatch();
          return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getAllDispatchClosedProduct(){
        try {
          $data_output = $this->repo->getAllDispatchClosedProduct();
          return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    
    
}