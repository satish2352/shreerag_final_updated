<?php
namespace App\Http\Services\Organizations\Estimation;
use App\Http\Repository\Organizations\Estimation\AllListRepository;
use Carbon\Carbon;

use Config;
class AllListServices
{
    protected $repo;
    public function __construct() {

        $this->repo = new AllListRepository();

    }
    public function getAllNewRequirement(){
        try {
            $data_output = $this->repo->getAllNewRequirement();
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getAllNewRequirementBusinessWise($business_id){
        try {
        $data_output = $this->repo->getAllNewRequirementBusinessWise($business_id);
            
        return $data_output;
        } catch (\Exception $e) {
            \Log::error('Service Error: ' . $e->getMessage());
            return []; 
        }
    }
 public function getAllEstimationSendToOwnerForApproval(){
        try {
            $data_output = $this->repo->getAllEstimationSendToOwnerForApproval();
           
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
   public function getAllEstimationSendToOwnerForApprovalBusinessWise($business_id)
{
    try {
      $data_output = $this->repo->getAllEstimationSendToOwnerForApprovalBusinessWise($business_id);
    
       return $data_output;
    } catch (\Exception $e) {
        \Log::error('Service Error: ' . $e->getMessage());
        return []; // Return safe fallback
    }
}
   public function getSendToProductionList()
{
    try {
      $data_output = $this->repo->getSendToProductionList();
   
       return $data_output;
    } catch (\Exception $e) {
        \Log::error('Service Error: ' . $e->getMessage());
        return []; // Return safe fallback
    }
}
    public function acceptBOMlist(){
        try {
            $data_output = $this->repo->acceptBOMlist();
          
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function acceptBOMlistBusinessWise($business_id){
        try {
            $data_output = $this->repo->acceptBOMlistBusinessWise($business_id);
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getAllrejectdesign(){
        try {
            $data_output = $this->repo->getAllrejectdesign();
            
            return $data_output; 
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getAllreviseddesign(){
        try {
            $data_output = $this->repo->getAllreviseddesign();
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getAllListMaterialRecievedToProduction(){
        try {
          $data_output = $this->repo->getAllListMaterialRecievedToProduction();
          return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getAllListMaterialRecievedToProductionBusinessWise($id)
    {
        try {
            $data_output = $this->repo->getAllListMaterialRecievedToProductionBusinessWise($id);
            
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    
    // public function getAllListMaterialRecievedToProductionBusinessWise($id)
    // {
    //     try {
    //         $data_output = $this->repo->getAllListMaterialRecievedToProductionBusinessWise($id);
    
    //         return $data_output;

    //     } catch (\Exception $e) {
    //         return $e;
    //     }
    // }

    public function getAllCompletedProduction(){
        try {
          $data_output = $this->repo->getAllCompletedProduction();
          return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getAllCompletedProductionSendToLogistics(){
        try {
          $data_output = $this->repo->getAllCompletedProductionSendToLogistics();
          return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getAllCompletedProductionSendToLogisticsProductWise($id) {
        try {
            $data_output = $this->repo->getAllCompletedProductionSendToLogisticsProductWise($id);
return $data_output;
        } catch (\Exception $e) {
            return ['status' => 'error', 'msg' => $e->getMessage()];
        }
    }
}