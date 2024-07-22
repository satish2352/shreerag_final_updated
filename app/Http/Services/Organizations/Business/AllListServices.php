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
            return $this->repo->getAllListForwardedToDesign();

        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getAllListCorrectionToDesignFromProduction()
    {
        try {
            return $this->repo->getAllListCorrectionToDesignFromProduction();
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function materialAskByProdToStore()
    {
        try {
            return $this->repo->materialAskByProdToStore();
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



    public function listPOReceivedForApprovaTowardsOwner()
    {
        try {
            $data_output = $this->repo->listPOReceivedForApprovaTowardsOwner();
        return $data_output;
    } catch (\Exception $e) {
            return $e;
        }
    }

    public function loadDesignSubmittedForProduction()
    {
        try {
            return $this->repo->loadDesignSubmittedForProduction();
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

}