<?php
namespace App\Http\Services\Organizations\Store;
use App\Http\Repository\Organizations\Store\AllListRepository;
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

  
    
    public function getAllListDesignRecievedForMaterial(){
        try {
            $data_output = $this->repo->getAllListDesignRecievedForMaterial();
           
            return $data_output;

        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getAllListMaterialSentToProduction(){
        try {
            return $this->repo->getAllListMaterialSentToProduction();
        } catch (\Exception $e) {
            return $e;
        }
    }


    public function getAllListMaterialSentToPurchase(){
        try {
            return $this->repo->getAllListMaterialSentToPurchase();
        } catch (\Exception $e) {
            return $e;
        }
    }


    public function getAllListMaterialReceivedFromQuality(){
        try {
            $return_data = $this->repo->getAllListMaterialReceivedFromQuality();
            return $return_data;
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