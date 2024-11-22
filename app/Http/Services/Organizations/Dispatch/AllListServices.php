<?php
namespace App\Http\Services\Organizations\Dispatch;
use App\Http\Repository\Organizations\Dispatch\AllListRepository;
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

   
    public function getAllReceivedFromFianance(){
        try {
          $data_output = $this->repo->getAllReceivedFromFianance();
    //  dd($data_output);
    //  die();
          return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getAllDispatch(){
        try {
          $data_output = $this->repo->getAllDispatch();
    //  dd($data_output);
    //  die();
          return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getAllDispatchClosedProduct(){
        try {
          $data_output = $this->repo->getAllDispatchClosedProduct();
    //  dd($data_output);
    //  die();
          return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    
    
}