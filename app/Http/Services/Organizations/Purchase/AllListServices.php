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

    

    public function getAllListApprovedPurchaseOrder(){
        try {
            return $this->repo->getAllListApprovedPurchaseOrder();
        } catch (\Exception $e) {
            return $e;
        }
    }

    
    public function getAllListPurchaseOrderMailSentToVendor(){
        try {
            return $this->repo->getAllListPurchaseOrderMailSentToVendor();
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getAllListPurchaseOrderMailSentToVendorBusinessWise($purchase_order_id)
    {
        try {
            $data_output = $this->repo->getAllListPurchaseOrderMailSentToVendorBusinessWise($purchase_order_id);
            return $data_output;

        } catch (\Exception $e) {
            return $e;
        }
    } 
    public function getAllListPurchaseOrderTowardsOwner(){
        try {
            return $this->repo->getAllListPurchaseOrderTowardsOwner();
        } catch (\Exception $e) {
            return $e;
        }
    } 

   
   


}