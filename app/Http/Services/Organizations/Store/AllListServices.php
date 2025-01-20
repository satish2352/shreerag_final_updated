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

    public function getAllListDesignRecievedForMaterialBusinessWise($business_id)
    {
        try {
            $data_output = $this->repo->getAllListDesignRecievedForMaterialBusinessWise($business_id);
            // dd($data_output);
            // die();
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getAllListMaterialSentToProduction(){
        try {
            $data_output = $this->repo->getAllListMaterialSentToProduction();
         
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getAllListMaterialSentToPurchase(){
        try {
            $data_output = $this->repo->getAllListMaterialSentToPurchase();
         
            return $data_output;
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
    public function getAllListMaterialReceivedFromQualityPOTracking(){
        try {
            $return_data = $this->repo->getAllListMaterialReceivedFromQualityPOTracking();
         
            return $return_data;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getAllListMaterialReceivedFromQualityPOTrackingBusinessWise($purchase_order_id)
    {
        try {
            $data_output = $this->repo->getAllListMaterialReceivedFromQualityPOTrackingBusinessWise($purchase_order_id);
            // dd($data_output);
            // die();
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getAllInprocessProductProduction()
    {
        try {
            $data_output = $this->repo->getAllInprocessProductProduction();
      
            return $data_output;

        } catch (\Exception $e) {
            return $e;
        }
    }
    

}