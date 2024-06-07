<?php
namespace App\Http\Services\Organizations\Business;
use App\Http\Repository\Organizations\Business\AllListRepositor;
use Carbon\Carbon;
// use App\Models\ {
//     DesignModel
//     };

use Config;
class AllListServices
{
    protected $repo;
    public function __construct() {

        $this->repo = new AllListRepositor();

    }

    public function getAllListForwardedToDesign(){
        try {
            return  $this->repo->getAllListForwardedToDesign();
        
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

    
    public function getAllListPurchaseOrder(){
        try {
            return $this->repo->getAllListPurchaseOrder();
        } catch (\Exception $e) {
            return $e;
        }
    } 


    
    public function getAllListApprovedPurchaseOrderOwnerlogin(){
        try {
            return $this->repo->getAllListApprovedPurchaseOrderOwnerlogin();
        } catch (\Exception $e) {
            return $e;
        }
    }

    

    public function listPOReceivedForApprovaTowardsOwner(){
        try {
            return $this->repo->listPOReceivedForApprovaTowardsOwner();
        } catch (\Exception $e) {
            return $e;
        }
    }



}