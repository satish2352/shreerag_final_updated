<?php
namespace App\Http\Services\Organizations\Purchase;
use App\Http\Repository\Organizations\Purchase\AllListRepository;
use Carbon\Carbon;
use Config;
class AllListServices
{
    protected $repo;
    public function __construct() {

        $this->repo = new AllListRepository();

    }  
    public function getAllListMaterialReceivedForPurchase(){
        try {
            $data_output = $this->repo->getAllListMaterialReceivedForPurchase();
        return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getAllListApprovedPurchaseOrder(){
        try {
            $data_output = $this->repo->getAllListApprovedPurchaseOrder();
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
            $data_output = $this->repo->getAllListPurchaseOrderMailSentToVendor();
            return $data_output;
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
    public function getAllListSubmitedPurchaeOrderByVendor(){
        try {
           $data_output = $this->repo->getAllListSubmitedPurchaeOrderByVendor();
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
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    } 
    //   public function getPurchaseReport($request){
    //     try {
    //         $data_output = $this->repo->getPurchaseReport($request);
          
    //         return $data_output;
    //     } catch (\Exception $e) {
    //         return $e;
    //     }
    // } 
  public function getPurchaseReport($request)
{
    try {
        return $this->repo->getPurchaseReport($request);
    } catch (\Exception $e) {
        return ['status' => false, 'message' => $e->getMessage()];
    }
}

public function getPurchasePartyReport($request)
{
    try {
        return $this->repo->getPurchasePartyReport($request);
    } catch (\Exception $e) {
        return ['status' => false, 'message' => $e->getMessage()];
    }
}
public function getFollowUpReport($request)
{
    try {
       $data_output =$this->repo->getFollowUpReport($request);
    //    dd($data_output);
    //    die();
       return $data_output;
    } catch (\Exception $e) {
        return ['status' => false, 'message' => $e->getMessage()];
    }
}

}