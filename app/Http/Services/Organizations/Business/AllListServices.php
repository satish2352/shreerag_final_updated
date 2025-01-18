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
    public function __construct()
    {

        $this->repo = new AllListRepositor();

    }

    public function getAllListForwardedToDesign()
    {
        try {
            $data_output = $this->repo->getAllListForwardedToDesign();
            return $data_output;

        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getAllListCorrectionToDesignFromProduction()
    {
        try {
            $data_output = $this->repo->getAllListCorrectionToDesignFromProduction();
            return $data_output; 
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function materialAskByProdToStore()
    {
        try {
            $data_output = $this->repo->materialAskByProdToStore();
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }


    public function getAllStoreDeptSentForPurchaseMaterials()
    {
        try {
            $data_output =  $this->repo->getAllStoreDeptSentForPurchaseMaterials();
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }



    public function getAllListPurchaseOrder()
    {
        try {
            $data_output = $this->repo->getAllListPurchaseOrder();
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }



    public function getAllListApprovedPurchaseOrderOwnerlogin()
    {
        try {
            $data_output = $this->repo->getAllListApprovedPurchaseOrderOwnerlogin();
      
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getAllListRejectedPurchaseOrderOwnerlogin()
    {
        try {
            $data_output = $this->repo->getAllListRejectedPurchaseOrderOwnerlogin();
    
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }


    public function listPOReceivedForApprovaTowardsOwner()
    {
        try {
            $data_output = $this->repo->listPOReceivedForApprovaTowardsOwner();
        return $data_output;
    } catch (\Exception $e) {
            return $e;
        }
    }
    public function listPOPaymentReleaseByVendor()
    {
        try {
            $data_output = $this->repo->listPOPaymentReleaseByVendor();
        return $data_output;
    } catch (\Exception $e) {
            return $e;
        }
    }

    public function loadDesignSubmittedForProduction()
    {
        try {
            $data_output = $this->repo->loadDesignSubmittedForProduction();
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function loadDesignSubmittedForProductionBusinessWise($business_id)
    {
        try {
            $data_output = $this->repo->loadDesignSubmittedForProductionBusinessWise($business_id);
        //    dd($data_output);
        //    die();
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getPurchaseOrderBusinessWise($purchase_order_id)
    {
        try {
            $data_output = $this->repo->getPurchaseOrderBusinessWise($purchase_order_id);
            return $data_output;

        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getPurchaseOrderRejectedBusinessWise($purchase_order_id)
    {
        try {
            $data_output = $this->repo->getPurchaseOrderRejectedBusinessWise($purchase_order_id);
            return $data_output;

        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getAllListSubmitedPurchaeOrderByVendorOwnerside()
    {
        try {
            $data_output = $this->repo->getAllListSubmitedPurchaeOrderByVendorOwnerside();
            return $data_output;

        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getOwnerReceivedGatePass()
    {
        try {
            $data_output = $this->repo->getOwnerReceivedGatePass();
            return $data_output;

        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getOwnerGRN()
    {
        try {
            $data = $this->repo->getOwnerGRN();
            return $data;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getAllListMaterialSentFromQualityToStoreGeneratedGRN()
    {
        try {
            $data = $this->repo->getAllListMaterialSentFromQualityToStoreGeneratedGRN();
            return $data;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getAllListMaterialSentFromQualityToStoreGeneratedGRNBusinessWise($id)
    {
        try {
            $data = $this->repo->getAllListMaterialSentFromQualityToStoreGeneratedGRNBusinessWise($id);
            return $data;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getOwnerAllListMaterialRecievedToProduction()
    {
        try {
            $data = $this->repo->getOwnerAllListMaterialRecievedToProduction();
            return $data;
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getOwnerAllCompletedProduction(){
        try {
          $data_output = $this->repo->getOwnerAllCompletedProduction();
       
          return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getOwnerFinalAllCompletedProductionLogistics(){
        try {
          $data_output = $this->repo->getOwnerFinalAllCompletedProductionLogistics();
          return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getOwnerAllListBusinessReceivedFromLogistics(){
        try {
            $data_output = $this->repo->getOwnerAllListBusinessReceivedFromLogistics();
            return $data_output;

        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getOwnerAllListBusinessFianaceSendToDispatch(){
        try {
            $data_output = $this->repo->getOwnerAllListBusinessFianaceSendToDispatch();
            return $data_output;

        } catch (\Exception $e) {
            return $e;
        }
    }
    public function listProductDispatchCompletedFromDispatch()
    {
        try {
            $data_output = $this->repo->listProductDispatchCompletedFromDispatch();
            return $data_output;

        } catch (\Exception $e) {
            return $e;
        }
    }
}