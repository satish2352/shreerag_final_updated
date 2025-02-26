<?php
namespace App\Http\Services\Organizations\Productions;
use App\Http\Repository\Organizations\Productions\AllListRepository;
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
            return $e;
        }
    }
    public function getAllacceptdesign(){
        try {
            $data_output = $this->repo->getAllacceptdesign();
          
            return $data_output;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function acceptdesignlistBusinessWise($business_id){
        try {
            $data_output = $this->repo->acceptdesignlistBusinessWise($business_id);
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
            return $this->repo->getAllListMaterialRecievedToProductionBusinessWise($id);
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