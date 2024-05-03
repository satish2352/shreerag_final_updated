<?php
namespace App\Http\Services\Organizations\Purchase;
use App\Http\Repository\Organizations\Purchase\AllListRepository;
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

  
    
    // public function getAllListDesignRecievedForMaterial(){
    //     try {
    //         return $this->repo->getAllListDesignRecievedForMaterial();
    //     } catch (\Exception $e) {
    //         return $e;
    //     }
    // }

    // public function getAllListMaterialSentToProduction(){
    //     try {
    //         return $this->repo->getAllListMaterialSentToProduction();
    //     } catch (\Exception $e) {
    //         return $e;
    //     }
    // }


    public function getAllListMaterialReceivedForPurchase(){
        try {
            return $this->repo->getAllListMaterialReceivedForPurchase();
        } catch (\Exception $e) {
            return $e;
        }
    }



}