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
            $data_output = $this->repo->getAllListMaterialReceivedForPurchase();
        //   dd($data_output);
        //   die();
        return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }

    

    public function getAllListApprovedPurchaseOrder(){
        try {
            $data_output = $this->repo->getAllListApprovedPurchaseOrder();
            // dd($data_output);
            // die();
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getPurchaseOrderSentToOwnerForApprovalBusinesWise($id)
    {
        try {
            $data_output = $this->repo->getPurchaseOrderSentToOwnerForApprovalBusinesWise($id);
          
            return $data_output;
        } catch (Exception $e) {
            return ['status' => 'error', 'msg' => $e->getMessage()];
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
            // dd($data_output);
            // die();
            return $data_output;

        } catch (\Exception $e) {
            return $e;
        }
    } 
    public function getAllListSubmitedPurchaeOrderByVendor(){
        try {
           $data_output = $this->repo->getAllListSubmitedPurchaeOrderByVendor();
        // dd($data_output);
        //    die();
        return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getAllListSubmitedPurchaeOrderByVendorBusinessWise($id)
    {
        try {
            $data_output = $this->repo->getAllListSubmitedPurchaeOrderByVendorBusinessWise($id);
          
            return $data_output;

        } catch (\Exception $e) {
            return $e;
        }
    } 
    public function getAllListPurchaseOrderTowardsOwner(){
        try {
            $data_output = $this->repo->getAllListPurchaseOrderTowardsOwner();
            // dd($data_output);
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    } 

   
   


}