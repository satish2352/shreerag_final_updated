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
        //  dd($data_output);
        //  die();
            return $data_output;

        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getAllListCorrectionToDesignFromProduction()
    {
        try {
            $data_output = $this->repo->getAllListCorrectionToDesignFromProduction();
            // dd($data_output);
            // die();
            return $data_output; 
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function materialAskByProdToStore()
    {
        try {
            $data_output = $this->repo->materialAskByProdToStore();
            // dd($data_output);
            // die();
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }


    public function getAllStoreDeptSentForPurchaseMaterials()
    {
        try {
            $data_output =  $this->repo->getAllStoreDeptSentForPurchaseMaterials();
        //  dd( $data_output);
        //  die();
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }



    public function getAllListPurchaseOrder()
    {
        try {
            $data_output = $this->repo->getAllListPurchaseOrder();
            // dd($data_output);
            // die();
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



    public function listPOReceivedForApprovaTowardsOwner()
    {
        try {
            $data_output = $this->repo->listPOReceivedForApprovaTowardsOwner();
            // dd( $data_output);
            // die();
        return $data_output;
    } catch (\Exception $e) {
            return $e;
        }
    }

    public function loadDesignSubmittedForProduction()
    {
        try {
            $data_output = $this->repo->loadDesignSubmittedForProduction();
        // dd($data_output);
        // die();
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function loadDesignSubmittedForProductionBusinessWise($business_id)
    {
        try {
            $data_output = $this->repo->loadDesignSubmittedForProductionBusinessWise($business_id);
        // dd($data_output);
        // die();
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getPurchaseOrderBusinessWise($purchase_order_id)
    {
        try {
            $data_output = $this->repo->getPurchaseOrderBusinessWise($purchase_order_id);
        //    dd($data_output);
        //    die();
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
        //  dd($data_output);
        //  die();
          return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getOwnerFinalAllCompletedProductionLogistics(){
        try {
          $data_output = $this->repo->getOwnerFinalAllCompletedProductionLogistics();
        //  dd($data_output);
        //  die();
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