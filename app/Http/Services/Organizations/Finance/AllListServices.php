<?php
namespace App\Http\Services\Organizations\Finance;
use App\Http\Repository\Organizations\Finance\AllListRepository;
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

  
    
    public function getAllListSRAndGRNGeanrated(){
        try {
            return $this->repo->getAllListSRAndGRNGeanrated();
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function listAcceptedGrnSrnFinance(){
        try {
            return $this->repo->listAcceptedGrnSrnFinance();
        } catch (\Exception $e) {
            return $e;
        }
    }


    public function listPOSentForApprovaTowardsOwner(){
        try {
            return $this->repo->listPOSentForApprovaTowardsOwner();
        } catch (\Exception $e) {
            return $e;
        }
    }

      

    public function listPOSanctionAndNeedToDoPaymentToVendor(){
        try {
            return $this->repo->listPOSanctionAndNeedToDoPaymentToVendor();
        } catch (\Exception $e) {
            return $e;
        }
    }

    // public function getAllListMaterialSentToPurchase(){
    //     try {
    //         return $this->repo->getAllListMaterialSentToPurchase();
    //     } catch (\Exception $e) {
    //         return $e;
    //     }
    // }


    // public function getAllListMaterialReceivedFromQuality(){
    //     try {
    //         return $this->repo->getAllListMaterialReceivedFromQuality();
    //     } catch (\Exception $e) {
    //         return $e;
    //     }
    // }



}