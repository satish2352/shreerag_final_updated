<?php
namespace App\Http\Services\Organizations\Designers;
use App\Http\Repository\Organizations\Designers\AllListRepositor;
use Carbon\Carbon;
// use App\Models\ {
//     DesignModel
//     };

use Config;
class AllListServices
{
    protected $repo;
    public function __construct() {

        $this->repo = new AllListRepositor();

    }

    public function getAllListDesignRecievedForCorrection(){
        try {
            return  $this->repo->getAllListDesignRecievedForCorrection();
        
        } catch (\Exception $e) {
            return $e;
        }
    } 

    public function getAllListCorrectionToDesignFromProduction(){
        try {
            return $this->repo->getAllListCorrectionToDesignFromProduction();
        } catch (\Exception $e) {
            return $e;
        }
    } 


}