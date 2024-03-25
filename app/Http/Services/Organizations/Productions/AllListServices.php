<?php
namespace App\Http\Services\Organizations\Productions;
use App\Http\Repository\Organizations\Productions\AllListRepository;
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

    // public function getAllListDesignRecievedForCorrection(){
    //     try {
    //         return  $this->repo->getAllListDesignRecievedForCorrection();
        
    //     } catch (\Exception $e) {
    //         return $e;
    //     }
    // } 

    // public function getAllListCorrectionToDesignFromProduction(){
    //     try {
    //         return $this->repo->getAllListCorrectionToDesignFromProduction();
    //     } catch (\Exception $e) {
    //         return $e;
    //     }
    // } 

    
    public function getAllNewRequirement(){
        try {
            return $this->repo->getAllNewRequirement();
        } catch (\Exception $e) {
            return $e;
        }
    }

    
    public function getAllacceptdesign(){
        try {
            return $this->repo->getAllacceptdesign();
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getAllrejectdesign(){
        try {
            return $this->repo->getAllrejectdesign();
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getAllreviseddesign(){
        try {
            return $this->repo->getAllreviseddesign();
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getAllListMaterialRecievedToProduction(){
        try {
            return $this->repo->getAllListMaterialRecievedToProduction();
        } catch (\Exception $e) {
            return $e;
        }
    }


}