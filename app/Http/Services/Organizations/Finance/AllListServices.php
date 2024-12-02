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
            $data_output = $this->repo->getAllListSRAndGRNGeanrated();
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getAllListSRAndGRNGeanratedBusinessWise($id)
    {
        try {
            $data_output = $this->repo->getAllListSRAndGRNGeanratedBusinessWise($id);
            return $data_output;

        } catch (\Exception $e) {
            return $e;
        }
    } 
    public function listAcceptedGrnSrnFinance($purchase_orders_id){
        try {
            return $this->repo->listAcceptedGrnSrnFinance($purchase_orders_id);
        } catch (\Exception $e) {
            return $e;
        }
    }


    public function listPOSentForApprovaTowardsOwner(){
        try {
            $data_output = $this->repo->listPOSentForApprovaTowardsOwner();
            return $data_output; 
        } catch (\Exception $e) {
            return $e;
        }
    }

      

    public function listPOSanctionAndNeedToDoPaymentToVendor(){
        try {
            $data_output = $this->repo->listPOSanctionAndNeedToDoPaymentToVendor();
    
            return $data_output;

        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getAllListBusinessReceivedFromLogistics(){
        try {
            $data_output = $this->repo->getAllListBusinessReceivedFromLogistics();
            return $data_output;

        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getAllListBusinessFianaceSendToDispatch(){
        try {
            $data_output = $this->repo->getAllListBusinessFianaceSendToDispatch();
            return $data_output;

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